<?php

    namespace couchService\Service\Lib;

    class Log {

        const CONFIG_KEY_APP_LOG_LEVEL = 'LOG_APP_LOG_LEVEL';
        const CONFIG_KEY_FILE_LOG_LEVEL = 'LOG_FILE_LOG_LEVEL';

        const LOG_LEVEL_VERBOSE = 100;
        const LOG_LEVEL_INFO    = 200;
        const LOG_LEVEL_NOTICE  = 300;
        const LOG_LEVEL_WARN    = 400;
        const LOG_LEVEL_ERROR   = 500;
        const LOG_LEVEL_FATAL   = 600;

        static protected function getAppLogLevel() {
            $logLevel = Configuration::getEnvironmentKey(self::CONFIG_KEY_APP_LOG_LEVEL);
            return ($logLevel) ?: self::LOG_LEVEL_INFO;
        }

        static protected function getFileLogLevel() {
            $logLevel = Configuration::getEnvironmentKey(self::CONFIG_KEY_FILE_LOG_LEVEL);
            return ($logLevel) ?: self::LOG_LEVEL_WARN;
        }

        static public function verbose($message) {

        }

        static public function info($message) {

        }

        static public function notice($message) {

        }

        static public function warn($message) {

        }

        static public function error($message) {

        }

        static public function fatal($message) {

        }

    }