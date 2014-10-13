<?php

    namespace couchServe\service\Controller;
    use couchServe\service\Lib\Abstracts\Controller;
    use couchServe\service\Lib\Sense;
    use couchServe\service\Lib\Log;

    class Dummy extends Controller {

        protected $state = 0;

        public function __construct() {
            Log::info('Dummy controller loaded');
        }

        public function react(Sense $sense) {
            if ($sense->getSensor() instanceof \couchServe\service\Sensors\Dummy) {
                if ($sense->getValue() % 50000 === 0) {
                    $this->state++;
                }
            }
        }

    }