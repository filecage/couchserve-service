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
            foreach ($this->sensorRegistry->getSensors() as $sensor) {
                $commandPool->addCommands($sensor->sense());
            }

        }

        protected function stayAlive() {
            return true;
        }
    }