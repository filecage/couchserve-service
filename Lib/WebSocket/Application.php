<?php

    namespace couchServe\service\Lib\WebSocket;

    /**
     * WebSocket Server Application
     *
     * @author Nico Kaiser <nico@kaiser.me>
     */
    abstract class Application {

        /**
         * @var Application[]
         */
        protected static $instances = array();

        /**
         * Singleton
         */
        protected function __construct() { }

        final private function __clone() { }

        /**
         * @return Application
         */
        final public static function getInstance() {
            $calledClassName = get_called_class();
            if(!isset(self::$instances[$calledClassName])) {
                self::$instances[$calledClassName] = new $calledClassName();
            }
            return self::$instances[$calledClassName];
        }

        abstract public function onConnect(Connection $connection);

        abstract public function onDisconnect(Connection $connection);

        abstract public function onData($data, Connection $client);


        /**
         * @param Connection $client
         * @param array $data
         * @return bool
         */
        protected function send(Connection $client, Array $data) {
            if(!$client->socketIsOpen()) {
                return false;
            }

            return $client->send(json_encode($data));
        }

    }