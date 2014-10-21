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
        protected $senseTime;

        /**
         * @param $value
         * @param Sensor $sensor
         */
        public function __construct(Sensor $sensor, $value, $senseTime = 'now') {
            $this->value = $value;
            $this->sensor = $sensor;
            $this->senseTime = new DateTime($senseTime);
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
        public function getSenseTime() {
            return $this->senseTime;
        }

    }