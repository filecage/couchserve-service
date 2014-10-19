<?php

    namespace couchServe\service\Lib\WebSocket\ProtocolMember;
    use couchServe\service\Lib\WebSocket\Lib\Connection;
    use couchServe\service\Lib\WebSocket\Lib\ProtocolMember;
    use couchServe\service\Lib\WebSocket\Handler;

    class UnknownCommand extends ProtocolMember {

        /**
         * @var Handler
         */
        protected $handler;

        /**
         * @param Array $data
         * @param Connection $connection
         */
        public function act(Array $data, Connection $connection) {
            $this->send($connection, [
                'type' => 'ERROR',
                'code' => 400,
                'message' => 'Command unknown'
            ]);
        }

    }