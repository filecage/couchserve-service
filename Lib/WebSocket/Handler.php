<?php

    namespace couchServe\service\Lib\WebSocket;
    use couchServe\service\Lib\WebSocket\Lib\HandlerInterface;
    use couchServe\service\Lib\WebSocket\Lib\Connection;
    use couchServe\service\Lib\CommandPool;

    class Handler implements HandlerInterface {

        /**
         * @var CommandPool
         */
        protected $commandPool;

        /**
         * @param CommandPool $commandPool
         */
        function setCommandPool(CommandPool $commandPool) {
            $this->commandPool = $commandPool;
        }

        function onConnect(Connection $connection) {
            // TODO: Implement onConnect() method.
        }

        function onDisconnect(Connection $connection) {
            // TODO: Implement onDisconnect() method.
        }

        function onData(Array $data, Connection $client) {
            // TODO: Implement onData() method.
        }
    }