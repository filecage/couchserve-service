<?php

    namespace couchServe\Service\Lib;

    class Configuration {

        protected static $prepared = false;
        protected static $environment = [];
        protected static $modules = [];
        protected static $sensors = [];
        protected static $controller = [];
        protected static $groups = [];

        /**
         * @param Database $db
         */
        public static function prepare($db) {
            if (static::$prepared) {
                return;
            }

            static::loadEnvironment($db);
            static::loadModules($db);
            static::loadSensors($db);
            static::loadController($db);
            static::loadGroups($db);
            static::$prepared = true;
        }

        /**
         * @param Database $db
         */
        protected static function loadEnvironment($db) {
            foreach ($db->query('SELECT environmentKey, value FROM environment')->getArray() as $row) {
                static::$environment[$row['environmentKey']] = $row['value'];
            }
        }

        /**
         * @param Database $db
         */
        protected static function loadModules($db) {
            foreach ($db->query('SELECT id, name, type, groupId FROM modules WHERE active = 1')->getArray() as $row) {
                static::$modules[$row['id']] = $row;
            }
        }

        /**
         * @param Database $db
         */
        protected static function loadSensors($db) {
            foreach ($db->query('SELECT id, name, type, groupId FROM sensors WHERE active = 1')->getArray() as $row) {
                static::$sensors[$row['id']] = $row;
            }
        }

        /**
         * @param Database $db
         */
        protected static function loadController($db) {
            foreach ($db->query('SELECT id, type FROM controller WHERE active = 1')->getArray() as $row) {
                static::$controller[$row['id']] = $row;
            }
        }

        /**
         * @param Database $db
         */
        protected static function loadGroups($db) {
            foreach ($db->query('SELECT id, name, description FROM groups')->getArray() as $row) {
                static::$groups[$row['id']] = $row;
            }
        }

        /**
         * @return Array
         */
        public static function getModules() {
            return static::$modules;
        }

        /**
         * @return Array
         */
        public static function getSensors() {
            return static::$sensors;
        }

        /**
         * @return Array
         */
        public static function getController() {
            return static::$controller;
        }

        /**
         * @return Array
         */
        public static function getGroups() {
            return static::$groups;
        }

        /**
         * @param string $key
         * @param mixed $default Default value to return if value is undefined
         * @return mixed
         */
        public static function getEnvironmentKey($key, $default = null) {
            return (isset(static::$environment[$key])) ? static::$environment[$key] : $default;
        }

    }