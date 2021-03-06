<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Database;
    use couchServe\Service\Lib\Group;

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
         * @var String
         */
        protected $type;

        /**
         * @var String
         */
        protected $name;

        /**
         * @var Int
         */
        protected $id;

        /**
         * @var Group
         */
        protected $group;

        /**
         * @var ModuleValue
         */
        protected $moduleValue;

        /**
         * @param array $row
         */
        public function injectConfigurationRow(Array $row) {
            $this->configurationRow = $row;
            $this->type = $row['type'];
            $this->name = $row['name'];
            $this->id = $row['id'];
        }

        /**
         * @return Group
         */
        public function getGroup() {
            return $this->group;
        }

        /**
         * @param Group $group
         */
        public function setGroup($group) {
            $this->group = $group;
            $this->group->registerModule($this);
        }

        /**
         * @return String
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @return String
         */
        public function getType() {
            return $this->type;
        }

        /**
         * @return ModuleValue
         */
        public function getModuleValue() {
            return $this->moduleValue;
        }

        /**
         * @return Int
         */
        public function getId() {
            return $this->id;
        }

        public function register() { }

        public function unregister() { }
    }