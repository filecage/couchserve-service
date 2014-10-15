<?php

    namespace couchServe\service\Lib\WebSocket\Lib;

    interface ApplicationInterface {
        function onConnect(Connection $connection);

        function onDisconnect(Connection $connection);

        function onData(Array $data, Connection $client);
    }