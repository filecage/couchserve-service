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
        public function setCommandPool(CommandPool $commandPool) {
            $this->commandPool = $commandPool;
        }

        /**
         * @param ModuleRegistry $moduleRegistry
         */
        public function setModuleRegistry(ModuleRegistry $moduleRegistry) {
            $this->moduleRegistry = $moduleRegistry;
        }

        /**
         * @param SensorRegistry $sensorRegistry
         */
        public function setSensorRegistry(SensorRegistry $sensorRegistry) {
            $this->sensorRegistry = $sensorRegistry;
        }

        /**
         * @return CommandPool
         */
        public function getCommandPool() {
            return $this->commandPool;
        }

        /**
         * @return ModuleRegistry
         */
        public function getModuleRegistry() {
            return $this->moduleRegistry;
        }

        /**
         * @return SensorRegistry
         */
        public function getSensorRegistry() {
            return $this->sensorRegistry;
        }

        public function onConnect(Connection $connection) {
            // TODO: Implement onConnect() method.
        }

        public function onDisconnect(Connection $connection) {
            // TODO: Implement onDisconnect() method.
        }

        public function onData(Array $data, Connection $client) {
            // TODO: Implement onData() method.
        }
    }