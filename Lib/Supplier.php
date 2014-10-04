<?php

    namespace couchService\Service\Lib;

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
                static::$reusableDatabase = static::getDatabase(false);
            }
            return static::$reusableDatabase;
        }

        /**
         * @param bool $reusable
         * @return Database
         */
        public static function getDatabase($reusable = true) {
            return ($reusable) ? static::getReusableDatabase() : new Database('localhost', 'couchServe');
        }

    }