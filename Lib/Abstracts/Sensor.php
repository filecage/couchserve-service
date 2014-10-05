<?php

    namespace couchServe\Service\Lib\Abstracts;

    abstract class Sensor extends Module {
        public abstract function sense();
    }