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
         * @var Connection[]
         */
        protected $connections = [];

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
            $this->connections[] = $connection;
        }

        public function onDisconnect(Connection $connection) {
            foreach ($this->connections as $key => $compareConnection) {
                if ($connection == $compareConnection) {
                    unset($this->connections[$key]);
                    break;
                }
            }
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

        /**
         * @param Array $data
         * @return $this
         */
        public function broadcast(Array $data) {
            foreach ($this->connections as $connection) {
                $this->send($connection, $data);
            }
            return $this;
        }

        /**
         * @param Connection $connection
         * @param Array $data
         * @return $this
         */
        public function send(Connection $connection, Array $data) {
            $connection->send(json_encode($data));
            return $this;
        }
        
    }