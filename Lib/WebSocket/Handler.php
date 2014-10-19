<?php

    namespace couchServe\service\Lib\WebSocket;
    use couchServe\Service\Lib\Command;
    use couchServe\service\Lib\WebSocket\Lib\HandlerInterface;
    use couchServe\service\Lib\WebSocket\Lib\Connection;
    use couchServe\service\Lib\CommandPool;
    use couchServe\service\Lib\ModuleRegistry;
    use couchServe\service\Lib\SensorRegistry;

    class Handler implements HandlerInterface {

        /**
         * @var ModuleRegistry
         */
        protected $moduleRegistry;

        /**
         * @var SensorRegistry
         */
        protected $sensorRegistry;

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

        function setModuleRegistry(ModuleRegistry $moduleRegistry) {
            $this->moduleRegistry = $moduleRegistry;
        }

        function setSensorRegistry(SensorRegistry $sensorRegistry) {
            $this->sensorRegistry = $sensorRegistry;
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