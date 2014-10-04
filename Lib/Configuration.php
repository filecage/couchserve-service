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
            foreach ($db->getArray('SELECT envKey, value FROM environment') as $row) {
                static::$environment[$row['envKey']] = $row['value'];
            }
        }

        /**
         * @param Database $db
         */
        protected static function loadModules($db) {
            foreach ($db->getArray('SELECT id, name, type FROM modules WHERE active = 1') as $row) {
                static::$modules[$row['id']] = $row;
            }
        }

    }