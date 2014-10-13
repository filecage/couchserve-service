<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Command;

    abstract class Sensor extends Module {

        /**
         * @return $this
         */
        public abstract function sense();

        public function getSenses() {
            return $this->senses;
        }

        public function act(Command $command) { }
    }