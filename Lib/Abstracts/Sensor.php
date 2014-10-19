<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Group;
    use couchServe\Service\Lib\Command;
    use couchServe\Service\Lib\Sense;

    abstract class Sensor extends Module {

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
            $this->group->registerSensor($this);
        }

        public function act(Command $command) { }
    }