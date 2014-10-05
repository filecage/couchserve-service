<?php

    namespace couchServe\service\Modules;
    use couchServe\service\Lib\Abstracts\Module;
    use couchServe\service\Lib\Command;
    use couchServe\service\Lib\Log;

    class Dummy extends Module {

        public function __construct(){
            Log::info('Dummy module loaded');
        }

        public function act(Command $command) {
            Log::info('Dummy module is supposed to do anything...');
        }

    }