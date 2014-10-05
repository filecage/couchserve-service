<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Database;

    abstract class Module implements \couchServe\Service\Lib\Interfaces\Module {

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

        public function register() { }

        public function unregister() { }
    }