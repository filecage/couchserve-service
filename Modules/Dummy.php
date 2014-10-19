<?php

    namespace couchServe\service\Modules;
    use couchServe\service\Lib\Abstracts\Module;
    use couchServe\service\Lib\ModuleValues\TogglePercent;
    use couchServe\service\Lib\Command;
    use couchServe\service\Lib\Log;

    class Dummy extends Module {

        public function __construct() {
            $this->moduleValue = new TogglePercent($this);
            Log::info('Dummy module loaded');
        }

        public function act(Command $command) {
            $data = $command->getData();
            if (empty($data)) {
                return;
            }

            $value = array_shift($data);
            $this->moduleValue->setValue($value);
            Log::info('Dummy modules value set to %d', [
                $this->moduleValue->getCurrentValue()
            ]);
        }

    }