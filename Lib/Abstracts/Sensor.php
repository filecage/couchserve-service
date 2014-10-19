<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Group;
    use couchServe\Service\Lib\Sense;

    abstract class Sensor {

        /**
         * @var Int
         */
        protected $id;

        /**
         * @var String
         */
        protected $type;

        /**
         * @var String
         */
        protected $name;

        /**
         * @var Array
         */
        protected $configurationRow;

        /**
         * @var Sense[]
         */
        protected $senses = [];

        /**
         * @var Group
         */
        protected $group;

        /**
         * @return $this
         */
        public abstract function sense();

        public function getSenses() {
            return $this->senses;
        }

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
         * @return Group
         */
        public function getGroup() {
            return $this->group;
        }

        /**
         * @return Int
         */
        public function getId() {
            return $this->id;
        }

        /**
         * @param Group $group
         */
        public function setGroup($group) {
            $this->group = $group;
            $this->group->registerSensor($this);
        }

        public function register() { }

        public function unregister() { }

    }