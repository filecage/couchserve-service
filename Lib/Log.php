<?php

    namespace couchServe\Service\Lib;

    class Log {

        const CONFIG_KEY_APP_LOG_LEVEL = 'LOG_APP_LOG_LEVEL';
        const CONFIG_KEY_FILE_LOG_LEVEL = 'LOG_FILE_LOG_LEVEL';
        const CONFIG_KEY_FILE_LOG_FILE = 'LOG_FILE';

        const LOG_LEVEL_DEBUG   = 50;
        const LOG_LEVEL_VERBOSE = 100;
        const LOG_LEVEL_INFO    = 200;
        const LOG_LEVEL_NOTICE  = 300;
        const LOG_LEVEL_WARN    = 400;
        const LOG_LEVEL_ERROR   = 500;
        const LOG_LEVEL_FATAL   = 600;

        /**
         * @var Array
         */
        protected static $logLevelToName = [
            self::LOG_LEVEL_DEBUG   => 'DEBUG',
            self::LOG_LEVEL_VERBOSE => 'VERBOSE',
            self::LOG_LEVEL_INFO    => 'INFO',
            self::LOG_LEVEL_NOTICE  => 'NOTICE',
            self::LOG_LEVEL_WARN    => 'WARN',
            self::LOG_LEVEL_ERROR   => 'ERROR',
            self::LOG_LEVEL_FATAL   => 'FATAL'
        ];

        /**
         * @var Resource
         */
        protected static $logFilePointer;

        /**
         * @return Int
         */
        static protected function getAppLogLevel() {
            return Configuration::getEnvironmentKey(self::CONFIG_KEY_APP_LOG_LEVEL, self::LOG_LEVEL_INFO);
        }

        /**
         * @return Int
         */
        static protected function getFileLogLevel() {
            return Configuration::getEnvironmentKey(self::CONFIG_KEY_FILE_LOG_LEVEL, self::LOG_LEVEL_WARN);
        }

        /**
         * @param String $message
         * @param Int $messageLogLevel
         * @param String $messageLogLevelName
         */
        static protected function appLog($message, $messageLogLevel, $messageLogLevelName) {
            if (static::getAppLogLevel() > $messageLogLevel) {
                return;
            }

            echo vsprintf("%s -%s- %s\n", [
                date('r'),
                $messageLogLevelName,
                $message
            ]);
        }

        /**
         * @param String $message
         * @param Int $messageLogLevel
         * @param String $messageLogLevelName
         */
        static protected function fileLog($message, $messageLogLevel, $messageLogLevelName) {
            if (static::getFileLogLevel() > $messageLogLevel) {
                return;
            }

            fwrite(self::getFilePointer(), vsprintf("%s -%s- %s\r\n", [
                date('r'),
                $messageLogLevelName,
                $message
            ]));
        }

        /**
         * @return Resource
         */
        static protected function getFilePointer() {
            if (!is_resource(self::$logFilePointer)) {
                self::$logFilePointer = fopen(Configuration::getEnvironmentKey(self::CONFIG_KEY_FILE_LOG_FILE, 'Log/app.log'), 'a');
            }
            return self::$logFilePointer;
        }

        static protected function log($message, $args, $logLevel) {
            $message = vsprintf($message, $args);
            static::appLog($message, $logLevel, self::$logLevelToName[$logLevel]);
            static::fileLog($message, $logLevel, self::$logLevelToName[$logLevel]);
        }

        static public function debug($message, $args = []) {
            static::log($message, $args, self::LOG_LEVEL_DEBUG);
        }

        static public function verbose($message, $args = []) {
            static::log($message, $args, self::LOG_LEVEL_VERBOSE);
        }

        static public function info($message, $args = []) {
            static::log($message, $args, self::LOG_LEVEL_INFO);
        }

        static public function notice($message, $args = []) {
            static::log($message, $args, self::LOG_LEVEL_NOTICE);
        }

        static public function warn($message, $args = []) {
            static::log($message, $args, self::LOG_LEVEL_WARN);
        }

        static public function error($message, $args = []) {
            static::log($message, $args, self::LOG_LEVEL_ERROR);
        }

        static public function fatal($message, $args = []) {
            static::log($message, $args, self::LOG_LEVEL_FATAL);
        }

    }