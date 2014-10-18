<?php

    namespace couchServe\service\Lib\WebSocket;
    use couchServe\service\Lib\WebSocket\Lib\Server;

    class Container {

        const HANDLER_APP_KEY = 'couchServe';

        /**
         * @var Server
         */
        protected $server;

        /**
         * @var Handler
         */
        protected $handler;

        /**
         * @param Server $server
         * @param Handler $handler
         */
        public function __construct(Server $server, Handler $handler) {
            $server->registerHandler(self::HANDLER_APP_KEY, $handler);
            $this->server = $server;
            $this->handler = $handler;
        }

        /**
         * @return Handler
         */
        public function getHandler() {
            return $this->handler;
        }

        /**
         * @return Server
         */
        public function getServer() {
            return $this->server;
        }

    }