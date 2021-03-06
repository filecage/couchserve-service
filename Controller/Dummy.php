<?php

    namespace couchServe\service\Controller;
    use couchServe\service\Lib\Abstracts\Controller;
    use couchServe\service\Lib\CommandPool;
    use couchServe\service\Lib\Command;
    use couchServe\service\Lib\Log;
    use \DateTime;

    class Dummy extends Controller {

        protected $state = 0;

        public function __construct() {
            Log::info('Dummy controller loaded');
        }

        public function react(CommandPool $commandPool) {
            foreach ($commandPool->getSenses() as $sense) {
                if ($sense->getSensor() instanceof \couchServe\service\Sensors\Dummy) {
                    if($sense->getValue() % 50000 === 0) {
                        $this->state++;
                        $this->addNewDummyCommand($commandPool);
                    }
                }
            }
        }

        protected function addNewDummyCommand(CommandPool $commandPool) {
            $command = new Command;
            $command->setTargetByModule($this->moduleRegistry->findModuleByTypeAndName(\couchServe\service\Modules\Dummy::class, 'dummy'));
            $commandPool->addCommand($command);
        }

    }