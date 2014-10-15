<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\WebSocket\Lib\Server;

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
         * @var Server
         */
        protected $webSocket;

        /**
         * @param ModuleRegistry $moduleRegistry
         * @param SensorRegistry $sensorRegistry
         * @param ControllerRegistry $controllerRegistry
         * @param Server $webSocket
         */
        public function __construct(
            ModuleRegistry $moduleRegistry,
            SensorRegistry $sensorRegistry,
            ControllerRegistry $controllerRegistry,
            Server $webSocket
        ) {
            $this->moduleRegistry = $moduleRegistry;
            $this->sensorRegistry = $sensorRegistry;
            $this->controllerRegistry = $controllerRegistry;
            $this->webSocket = $webSocket;
        }

        public function run() {
            while ($this->stayAlive()) {
                $this->tick(new CommandPool);
            }
        }

        protected function tick(CommandPool $commandPool) {
            $this->collectSenses($commandPool);
            $this->collectStreamCommands($commandPool);
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

        protected function collectStreamCommands($commandPool) {

        }

        protected function processSenses(CommandPool $commandPool) {
            foreach ($this->controllerRegistry->getControllers() as $controller) {
                $controller->react($commandPool);
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

        }

        protected function stayAlive() {
            return true;
        }
    }