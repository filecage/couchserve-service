<?php

    namespace couchServe\Service\Lib;

    class Supplier {

        /**
         * @var Database
         */
        static protected $reusableDatabase;

        /**
         * @return Database
         */
        public static function getReusableDatabase() {
            if (!isset(static::$reusableDatabase)) {
                static::$reusableDatabase = static::getDatabase();
            }
            return static::$reusableDatabase;
        }

        /**
         * @return Database
         */
        public static function getDatabase() {
            return new Database('localhost', 'couchServe');
        }

    }