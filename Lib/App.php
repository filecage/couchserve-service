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
         * @var ControllerRegistry
         */
        protected $controllerRegistry;

        /**
         * @param ModuleRegistry $moduleRegistry
         * @param SensorRegistry $sensorRegistry
         * @param ControllerRegistry $controllerRegistry
         */
        public function __construct(ModuleRegistry $moduleRegistry, SensorRegistry $sensorRegistry, ControllerRegistry $controllerRegistry) {
            $this->moduleRegistry = $moduleRegistry;
            $this->sensorRegistry = $sensorRegistry;
            $this->controllerRegistry = $controllerRegistry;
        }

        public function run() {
            while ($this->stayAlive()) {
                $this->tick(new CommandPool);
            }
        }

        protected function tick(CommandPool $commandPool) {
            $this->collectSenses($commandPool);
            $this->processSenses($commandPool);
            $this->processCommands($commandPool);
            $this->broadcastCommands($commandPool);
        }

        protected function collectSenses(CommandPool $commandPool) {
            foreach ($this->sensorRegistry->getSensors() as $sensor) {
                $senses = $sensor->sense()->getSenses();
                if (!is_array($senses)) {
                    $senses = [$senses];
                }
                $commandPool->addSenses($senses);
            }
        }

        protected function processSenses(CommandPool $commandPool) {
            foreach ($this->controllerRegistry->getControllers() as $controller) {
                foreach ($commandPool->getSenses() as $sense) {
                    $controller->react($sense);
                }
                $commands = $controller->getCommands();
                if (!is_array($commands)) {
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