<?php

    namespace couchServe\service\Lib\WebSocket\Lib;
    use couchServe\service\Lib\WebSocket\Handler;

    abstract class ProtocolMember {

        /**
         * @var Handler
         */
        protected $handler;

        /**
         * @param Array $data
         * @param Connection $connection
         */
        public abstract function act(Array $data, Connection $connection);

        /**
         * @param Handler $handler
         * @return $this
         */
        public function setHandler(Handler $handler) {
            $this->handler = $handler;
            return $this;
        }

        /**
         * @param Connection $connection
         * @param Array $data
         */
        protected function send($connection, Array $data) {
            $connection->send(json_encode($data));
        }

        /**
         * @return \couchServe\service\Lib\ModuleRegistry
         */
        protected function getModuleRegistry() {
            return $this->handler->getModuleRegistry();
        }

        /**
         * @return \couchServe\service\Lib\SensorRegistry
         */
        protected function getSensorRegistry() {
            return $this->handler->getSensorRegistry();
        }

        /**
         * @return \couchServe\service\Lib\CommandPool
         */
        protected function getCommandPool() {
            return $this->handler->getCommandPool();
        }

    }