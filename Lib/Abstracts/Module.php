<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Database;

    abstract class Module {
        /**
         * @var Database
         */
        protected $database;

        /**
         * @var Array
         */
        protected $configurationRow;

        /**
         * @param array $row
         */
        public function injectConfigurationRow(Array $row) {
            $this->configurationRow = $row;
        }

        public abstract function register();
        public abstract function unregister();
    }