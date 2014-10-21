<?php

    namespace couchServe\service\Lib;
    use couchServe\service\Lib\Abstracts\Sensor;
    use \DateTime;

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
         * @var DateTime
         */
        protected $datetime;

        /**
         * @param $value
         * @param Sensor $sensor
         */
        public function __construct(Sensor $sensor, $value) {
            $this->value = $value;
            $this->sensor = $sensor;
            $this->datetime = new DateTime;
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

        /**
         * @return DateTime
         */
        public function getDateTime() {
            return $this->datetime;
        }

    }