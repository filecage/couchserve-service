<?php

    namespace couchServe\service\Sensors;
    use couchServe\service\Lib\Abstracts\Sensor;
    use couchServe\service\Lib\Sense;
    use couchServe\service\Lib\Log;

    class Dummy extends Sensor {

        protected $cnt = 0;

        public function __construct() {
            Log::info('Dummy sensor loaded');
        }

        public function sense() {
            $this->cnt++;
            return $this;
        }

        public function getSenses() {
            return [new Sense($this, $this->cnt)];
        }

    }