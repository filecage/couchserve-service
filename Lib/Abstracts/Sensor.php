<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Command;

    abstract class Sensor extends Module {
        public abstract function sense();

        public function act(Command $command) { }
    }