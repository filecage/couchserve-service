<?php
    namespace couchServe\service\Lib\WebSocket\Lib;

    /**
     * WebSocket Connection class
     *
     * @author Nico Kaiser <nico@kaiser.me>
     * @author Simon Samtleben <web@lemmingzshadow.net>
     */
    class Connection {

        /**
         * @var Server
         */
        private $server;

        /**
         * @var Resource
         */
        private $socket;
        private $handshaked = false;

        /**
         * @var Handler
         */
        private $handler = null;

        private $ip;
        private $port;
        private $connectionId = null;


        public $waitingForData = false;
        private $_dataBuffer = '';


        public function __construct($server, $socket) {

            $this->server = $server;
            $this->socket = $socket;

            // set some client-information:
            $socketName = stream_socket_get_name($socket, true);
            $tmp = explode(':', $socketName);
            $this->ip = $tmp[0];
            $this->port = $tmp[1];
            $this->connectionId = md5($this->ip . $this->port . spl_object_hash($this));

            $this->log('Connected');
        }

        private function handshake($data) {
            $this->log('Performing handshake');
            $lines = preg_split("/\r\n/", $data);

            // check for valid http-header:
            if(!preg_match('/\AGET (\S+) HTTP\/1.1\z/', $lines[0], $matches)) {
                $this->log('Invalid request: ' . $lines[0]);
                $this->sendHttpResponse(400);
                stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);

                return false;
            }

            // check for valid handler:
            $path = $matches[1];
            $this->handler = $this->server->getHandler(substr($path, 1));
            if(!$this->handler) {
                $this->log('Invalid handler: ' . $path);
                $this->sendHttpResponse(404);
                stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
                $this->server->removeClientOnError($this);

                return false;
            }

            // generate headers array:
            $headers = [];
            foreach ($lines as $line) {
                $line = chop($line);
                if(preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                    $headers[$matches[1]] = $matches[2];
                }
            }

            // check for supported websocket version:
            if(!isset($headers['Sec-WebSocket-Version']) || $headers['Sec-WebSocket-Version'] < 6) {
                $this->log('Unsupported websocket version.');
                $this->sendHttpResponse(501);
                stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
                $this->server->removeClientOnError($this);

                return false;
            }

            // check origin:
            if($this->server->getCheckOrigin() === true) {
                $origin = (isset($headers['Sec-WebSocket-Origin'])) ? $headers['Sec-WebSocket-Origin'] : false;
                $origin = (isset($headers['Origin'])) ? $headers['Origin'] : $origin;
                if($origin === false) {
                    $this->log('No origin provided.');
                    $this->sendHttpResponse(401);
                    stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
                    $this->server->removeClientOnError($this);

                    return false;
                }

                if(empty($origin)) {
                    $this->log('Empty origin provided.');
                    $this->sendHttpResponse(401);
                    stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
                    $this->server->removeClientOnError($this);

                    return false;
                }

                if($this->server->checkOrigin($origin) === false) {
                    $this->log('Invalid origin provided.');
                    $this->sendHttpResponse(401);
                    stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
                    $this->server->removeClientOnError($this);

                    return false;
                }
            }

            // do handyshake: (hybi-10)
            $secKey = $headers['Sec-WebSocket-Key'];
            $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
            $response = "HTTP/1.1 101 Switching Protocols\r\n";
            $response .= "Upgrade: websocket\r\n";
            $response .= "Connection: Upgrade\r\n";
            $response .= "Sec-WebSocket-Accept: " . $secAccept . "\r\n";
            if(isset($headers['Sec-WebSocket-Protocol']) && !empty($headers['Sec-WebSocket-Protocol'])) {
                $response .= "Sec-WebSocket-Protocol: " . substr($path, 1) . "\r\n";
            }
            $response .= "\r\n";
            if(false === ($this->server->writeBuffer($this->socket, $response))) {
                return false;
            }
            $this->handshaked = true;
            $this->log('Handshake sent');
            $this->handler->onConnect($this);

            return true;
        }

        public function sendHttpResponse($httpStatusCode = 400) {
            $httpHeader = 'HTTP/1.1 ';
            switch ($httpStatusCode) {
                case 400:
                    $httpHeader .= '400 Bad Request';
                    break;

                case 401:
                    $httpHeader .= '401 Unauthorized';
                    break;

                case 403:
                    $httpHeader .= '403 Forbidden';
                    break;

                case 404:
                    $httpHeader .= '404 Not Found';
                    break;

                case 501:
                    $httpHeader .= '501 Not Implemented';
                    break;
            }
            $httpHeader .= "\r\n";
            $this->server->writeBuffer($this->socket, $httpHeader);
        }

        public function onData($data) {
            if($this->handshaked) {
                return $this->handle($data);
            } else {
                return $this->handshake($data);
            }
        }

        private function handle($data) {
            if($this->waitingForData === true) {
                $data = $this->_dataBuffer . $data;
                $this->_dataBuffer = '';
                $this->waitingForData = false;
            }

            $decodedData = $this->hybi10Decode($data);

            if($decodedData === false) {
                $this->waitingForData = true;
                $this->_dataBuffer .= $data;

                return false;
            } else {
                $this->_dataBuffer = '';
                $this->waitingForData = false;
            }

            if(!isset($decodedData['type'])) {
                $this->sendHttpResponse(401);
                stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
                $this->server->removeClientOnError($this);

                return false;
            }

            switch ($decodedData['type']) {
                case 'text':
                    $payloadData = json_decode($decodedData['payload'], true);
                    if (is_array($payloadData)) {
                        $this->handler->onData($payloadData, $this);
                    }
                    break;

                case 'binary':
                    if(method_exists($this->handler, 'onBinaryData')) {
                        $this->handler->onBinaryData($decodedData['payload'], $this);
                    } else {
                        $this->close(1003);
                    }
                    break;

                case 'ping':
                    $this->send($decodedData['payload'], 'pong', false);
                    $this->log('Ping? Pong!');
                    break;

                case 'pong':
                    // server currently not sending pings, so no pong should be received.
                    break;

                case 'close':
                    $this->close();
                    $this->log('Disconnected');
                    break;
            }

            return true;
        }

        public function send($payload, $type = 'text', $masked = false) {
            $encodedData = $this->hybi10Encode($payload, $type, $masked);
            if(!$this->server->writeBuffer($this->socket, $encodedData)) {
                $this->server->removeClientOnError($this);
                return false;
            }

            return true;
        }

        public function close($statusCode = 1000) {
            $payload = str_split(sprintf('%016b', $statusCode), 8);
            $payload[0] = chr(bindec($payload[0]));
            $payload[1] = chr(bindec($payload[1]));
            $payload = implode('', $payload);

            switch ($statusCode) {
                case 1000:
                    $payload .= 'normal closure';
                    break;

                case 1001:
                    $payload .= 'going away';
                    break;

                case 1002:
                    $payload .= 'protocol error';
                    break;

                case 1003:
                    $payload .= 'unknown data (opcode)';
                    break;

                case 1004:
                    $payload .= 'frame too large';
                    break;

                case 1007:
                    $payload .= 'utf8 expected';
                    break;

                case 1008:
                    $payload .= 'message violates server policy';
                    break;
            }

            if($this->send($payload, 'close', false) === false) {
                return false;
            }

            if($this->handler) {
                $this->handler->onDisconnect($this);
            }
            stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
            $this->server->removeClientOnClose($this);
            return true;
        }


        public function onDisconnect() {
            $this->log('Disconnected', 'info');
            $this->close(1000);
        }

        public function log($message, $type = 'info') {
            $this->server->log('[client ' . $this->ip . ':' . $this->port . '] ' . $message, $type);
        }

        private function hybi10Encode($payload, $type = 'text', $masked = true) {
            $frameHead = [];
            $frame = '';
            $payloadLength = strlen($payload);

            switch ($type) {
                case 'text':
                    // first byte indicates FIN, Text-Frame (10000001):
                    $frameHead[0] = 129;
                    break;

                case 'close':
                    // first byte indicates FIN, Close Frame(10001000):
                    $frameHead[0] = 136;
                    break;

                case 'ping':
                    // first byte indicates FIN, Ping frame (10001001):
                    $frameHead[0] = 137;
                    break;

                case 'pong':
                    // first byte indicates FIN, Pong frame (10001010):
                    $frameHead[0] = 138;
                    break;
            }

            // set mask and payload length (using 1, 3 or 9 bytes)
            if($payloadLength > 65535) {
                $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
                $frameHead[1] = ($masked === true) ? 255 : 127;
                for ($i = 0; $i < 8; $i++) {
                    $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
                }
                // most significant bit MUST be 0 (close connection if frame too big)
                if($frameHead[2] > 127) {
                    $this->close(1004);

                    return false;
                }
            } elseif($payloadLength > 125) {
                $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
                $frameHead[1] = ($masked === true) ? 254 : 126;
                $frameHead[2] = bindec($payloadLengthBin[0]);
                $frameHead[3] = bindec($payloadLengthBin[1]);
            } else {
                $frameHead[1] = ($masked === true) ? $payloadLength + 128 : $payloadLength;
            }

            // convert frame-head to string:
            foreach (array_keys($frameHead) as $i) {
                $frameHead[$i] = chr($frameHead[$i]);
            }
            $mask = [];
            if($masked === true) {
                // generate a random mask:
                for ($i = 0; $i < 4; $i++) {
                    $mask[$i] = chr(rand(0, 255));
                }

                $frameHead = array_merge($frameHead, $mask);
            }
            $frame = implode('', $frameHead);

            // append payload to frame:
            for ($i = 0; $i < $payloadLength; $i++) {
                $frame .= ($masked === true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
            }

            return $frame;
        }

        private function hybi10Decode($data) {
            $unmaskedPayload = '';
            $decodedData = [];

            // estimate frame type:
            $firstByteBinary = sprintf('%08b', ord($data[0]));
            $secondByteBinary = sprintf('%08b', ord($data[1]));
            $opcode = bindec(substr($firstByteBinary, 4, 4));
            $isMasked = ($secondByteBinary[0] == '1') ? true : false;
            $payloadLength = ord($data[1]) & 127;

            // close connection if unmasked frame is received:
            if($isMasked === false) {
                $this->close(1002);
            }

            switch ($opcode) {
                // text frame:
                case 1:
                    $decodedData['type'] = 'text';
                    break;

                case 2:
                    $decodedData['type'] = 'binary';
                    break;

                // connection close frame:
                case 8:
                    $decodedData['type'] = 'close';
                    break;

                // ping frame:
                case 9:
                    $decodedData['type'] = 'ping';
                    break;

                // pong frame:
                case 10:
                    $decodedData['type'] = 'pong';
                    break;

                default:
                    // Close connection on unknown opcode:
                    $this->close(1003);
                    break;
            }

            if($payloadLength === 126) {
                $mask = substr($data, 4, 4);
                $payloadOffset = 8;
                $dataLength = bindec(sprintf('%08b', ord($data[2])) . sprintf('%08b', ord($data[3]))) + $payloadOffset;
            } elseif($payloadLength === 127) {
                $mask = substr($data, 10, 4);
                $payloadOffset = 14;
                $tmp = '';
                for ($i = 0; $i < 8; $i++) {
                    $tmp .= sprintf('%08b', ord($data[$i + 2]));
                }
                $dataLength = bindec($tmp) + $payloadOffset;
                unset($tmp);
            } else {
                $mask = substr($data, 2, 4);
                $payloadOffset = 6;
                $dataLength = $payloadLength + $payloadOffset;
            }

            /**
             * We have to check for large frames here. socket_recv cuts at 1024 bytes
             * so if websocket-frame is > 1024 bytes we have to wait until whole
             * data is transferd.
             */
            if(strlen($data) < $dataLength) {
                return false;
            }

            if($isMasked === true) {
                for ($i = $payloadOffset; $i < $dataLength; $i++) {
                    $j = $i - $payloadOffset;
                    if(isset($data[$i])) {
                        $unmaskedPayload .= $data[$i] ^ $mask[$j % 4];
                    }
                }
                $decodedData['payload'] = $unmaskedPayload;
            } else {
                $payloadOffset = $payloadOffset - 4;
                $decodedData['payload'] = substr($data, $payloadOffset);
            }

            return $decodedData;
        }

        public function getClientIp() {
            return $this->ip;
        }

        public function getClientPort() {
            return $this->port;
        }

        public function getClientId() {
            return $this->connectionId;
        }

        /**
         * Alias of getClientId
         *
         * @see Connection::getClientId
         * @return null|string
         */
        public function getId() {
            return $this->getClientId();
        }

        public function getClientSocket() {
            return $this->socket;
        }

        public function getClientHandler() {
            return (isset($this->handler)) ? $this->handler : false;
        }

        /**
         * @return bool
         */
        public function socketIsOpen() {
            $meta = stream_get_meta_data($this->getClientSocket());
            return (!$meta['eof'] && !$meta['timed_out']);
        }

    }