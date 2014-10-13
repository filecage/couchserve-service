<?php

    namespace couchServe\service\Lib;
    use couchServe\service\Lib\Abstracts\Sensor;

    class Sense {

        /**
         * @var Sensor
         */
        protected $sensor;

        /**
         * @var mixed
         */
        protected $value;

        /**
         * @param $value
         * @param Sensor $sensor
         */
        public function __construct($value, Sensor $sensor = null) {
            $this->value = $value;
            if ($sensor) {
                $this->sensor = $sensor;
            }
        }

        /**
         * @return Sensor
         */
        public function getSensor() {
            return $this->sensor;
        }

        /**
         * @return mixed
         */
        public function getValue() {
            return $this->value;
        }

    }