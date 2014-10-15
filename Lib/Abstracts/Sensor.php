<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Command;
    use couchServe\Service\Lib\Sense;

    abstract class Sensor extends Module {

        /**
         * @var Sense[]
         */
        protected $senses = [];

        /**
         * @return $this
         */
        public abstract function sense();

        public function getSenses() {
            return $this->senses;
        }

        public function act(Command $command) { }
    }