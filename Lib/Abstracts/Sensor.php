<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Exceptions\GenericException;
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
         * @var Sense
         */
        protected $sense;

        /**
         * @var Group
         */
        protected $group;

        public final function sense() {
            $value = $this->getValue();
            if (!is_scalar($value) && !is_array($value)) {
                throw new GenericException('Sensor may only return scalar or array values');
            }
            $this->sense = new Sense($this, $value);
            return $this;
        }

        /**
         * @return $this
         */
        public abstract function getValue();

        /**
         * @return Sense
         */
        public function getLastSense() {
            return $this->sense;
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