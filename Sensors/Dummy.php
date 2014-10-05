<?php

    namespace couchServe\service\Sensors;
    use couchServe\service\Lib\Abstracts\Sensor;
    use couchServe\service\Lib\Command;
    use couchServe\service\Lib\Log;

    class Dummy extends Sensor {

        protected $cnt = 0;

        public function __construct() {
            Log::info('Dummy sensor loaded');
        }

        public function sense() {
            $this->cnt++;

            if ($this->cnt < 50000) {
                return [];
            }

            $this->cnt = 0;
            $command = new Command;
            return $command->setTargetId(1)
                    ->setOrigin($this)
                    ->setData(['foo' => 'bar']);
        }

    }