<?php

    namespace couchServe\service\Lib\WebSocket;
    use couchServe\service\Lib\WebSocket\Lib\HandlerInterface;
    use couchServe\service\Lib\WebSocket\Lib\Connection;
    use couchServe\service\Lib\CommandPool;
    use couchServe\service\Lib\Registries\ModuleRegistry;
    use couchServe\service\Lib\Registries\SensorRegistry;
    use couchServe\service\Lib\Registries\GroupRegistry;

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
         * @var GroupRegistry
         */
        protected $groupRegistry;

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
         * @param GroupRegistry $groupRegistry
         */
        public function setGroupRegistry(GroupRegistry $groupRegistry) {
            $this->groupRegistry = $groupRegistry;
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
         * @return GroupRegistry
         */
        public function getGroupRegistry() {
            return $this->groupRegistry;
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
            if (!isset($data['action'])) {
                return;
            }

            switch (strtoupper($data['action'])) {
                case 'MODULE_COMMAND':
                    $member = new ProtocolMember\ModuleCommand;
                    break;

                case 'SYNC':
                    $member = new ProtocolMember\Sync;
                    break;

                default:
                    $member = new ProtocolMember\UnknownCommand;
                    break;
            }

            $member->setHandler($this)->act($data, $client);

        }
    }