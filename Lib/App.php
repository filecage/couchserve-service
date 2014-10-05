<?php

    namespace couchServe\Service\Lib;

    class App {

        /**
         * @var ModuleRegistry
         */
        protected $moduleRegistry;

        /**
         * @var SensorRegistry
         */
        protected $sensorRegistry;

        /**
         * @param ModuleRegistry $moduleRegistry
         * @param SensorRegistry $sensorRegistry
         */
        public function __construct(ModuleRegistry $moduleRegistry, SensorRegistry $sensorRegistry) {
            $this->moduleRegistry = $moduleRegistry;
            $this->sensorRegistry = $sensorRegistry;
        }

        public function run() {
            while ($this->stayAlive()) {
                $this->tick(new CommandPool);
            }
        }

        protected function tick(CommandPool $commandPool) {
            $this->collectCommands($commandPool);
            $this->processCommands($commandPool);
            $this->broadcastCommands($commandPool);
        }

        protected function collectCommands(CommandPool $commandPool) {
            foreach ($this->sensorRegistry->getSensors() as $sensor) {
                $commands = $sensor->sense();
                if (!is_array($commands)){
                    $commands = [$commands];
                }
                $commandPool->addCommands($commands);
            }
        }

        protected function processCommands(CommandPool $commandPool) {
            $modules = $this->moduleRegistry->getModules();
            foreach ($commandPool->getCommands() as $command) {
                if (!isset($modules[$command->getTargetId()])) {
                    continue;
                }
                $modules[$command->getTargetId()]->act($command);
            }
        }

        protected function broadcastCommands(CommandPool $commandPool) {
            // todo: implement broadcasting
        }

        protected function stayAlive() {
            return true;
        }
    }