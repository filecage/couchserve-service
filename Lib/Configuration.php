<?php

    namespace couchService\Service\Lib;

    class Configuration {

        protected static $prepared = false;
        protected static $environment = [];
        protected static $modules = [];

        /**
         * @param Database $db
         */
        public static function prepare($db) {
            if (static::$prepared) {
                return;
            }

            static::loadEnvironment($db);
            static::loadModules($db);
            static::$prepared = true;
        }

        /**
         * @param Database $db
         */
        protected static function loadEnvironment($db) {
            foreach ($db->query('SELECT envKey, value FROM environment')->getArray() as $row) {
                static::$environment[$row['envKey']] = $row['value'];
            }
        }

        /**
         * @param Database $db
         */
        protected static function loadModules($db) {
            foreach ($db->query('SELECT id, name, type FROM modules WHERE active = 1')->getArray() as $row) {
                static::$modules[$row['id']] = $row;
            }
        }

        /**
         * @return Array
         */
        public static function getModules() {
            return static::$modules;
        }

        /**
         * @param string $key
         * @return mixed
         */
        public static function getEnvironmentKey($key) {
            return (isset(static::$environment[$key])) ? static::$environment : null;
        }

    }