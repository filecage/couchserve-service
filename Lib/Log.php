<?php

    namespace couchService\Service\Lib;

    class Log {

        const CONFIG_KEY_APP_LOG_LEVEL = 'LOG_APP_LOG_LEVEL';
        const CONFIG_KEY_FILE_LOG_LEVEL = 'LOG_FILE_LOG_LEVEL';
        const CONFIG_KEY_FILE_LOG_FILE = 'LOG_FILE';

        const LOG_LEVEL_VERBOSE = 100;
        const LOG_LEVEL_INFO    = 200;
        const LOG_LEVEL_NOTICE  = 300;
        const LOG_LEVEL_WARN    = 400;
        const LOG_LEVEL_ERROR   = 500;
        const LOG_LEVEL_FATAL   = 600;

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

        static public function verbose($message, $args = []) {
            $message = vsprintf($message, $args);
            static::appLog($message, self::LOG_LEVEL_VERBOSE, 'VERBOSE');
            static::fileLog($message, self::LOG_LEVEL_VERBOSE, 'VERBOSE');
        }

        static public function info($message, $args = []) {
            $message = vsprintf($message, $args);
            static::appLog($message, self::LOG_LEVEL_INFO, 'INFO');
            static::fileLog($message, self::LOG_LEVEL_INFO, 'INFO');
        }

        static public function notice($message, $args = []) {
            $message = vsprintf($message, $args);
            static::appLog($message, self::LOG_LEVEL_NOTICE, 'NOTICE');
            static::fileLog($message, self::LOG_LEVEL_NOTICE, 'NOTICE');
        }

        static public function warn($message, $args = []) {
            $message = vsprintf($message, $args);
            static::appLog($message, self::LOG_LEVEL_WARN, 'WARN');
            static::fileLog($message, self::LOG_LEVEL_WARN, 'WARN');
        }

        static public function error($message, $args = []) {
            $message = vsprintf($message, $args);
            static::appLog($message, self::LOG_LEVEL_ERROR, 'ERROR');
            static::fileLog($message, self::LOG_LEVEL_ERROR, 'ERROR');
        }

        static public function fatal($message, $args = []) {
            $message = vsprintf($message, $args);
            static::appLog($message, self::LOG_LEVEL_FATAL, 'FATAL');
            static::fileLog($message, self::LOG_LEVEL_FATAL, 'FATAL');
        }

    }