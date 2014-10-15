<?php

    namespace couchServe\service\Lib\WebSocket;
    use couchServe\service\Lib\WebSocket\Lib\ApplicationInterface;
    use couchServe\service\Lib\WebSocket\Lib\Connection;

    class Handler implements ApplicationInterface {
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