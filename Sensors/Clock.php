<?php

    namespace couchServe\service\Sensors;
    use couchServe\service\Lib\Abstracts\Sensor;
    use couchServe\service\Lib\Sense;
    use couchServe\service\Lib\Log;

    class Clock extends Sensor {

        /**
         * @var \Datetime
         */
        protected $datetime;

        public function __construct() {
            $this->disableCaching();
        }

        public function getValue() {
            return date('c');
        }

    }