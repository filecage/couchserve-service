<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\Abstracts\Module;
    use couchServe\Service\Lib\Abstracts\Sensor;

    class Bootstrap {

        /**
         * @var ModuleRegistry
         */
        protected $moduleRegistry;

        /**
         * @var SensorRegistry
         */
        protected $sensorRegistry;

        public function __construct() {
            $this->moduleRegistry = new ModuleRegistry();
            $this->sensorRegistry = new SensorRegistry();
        }

        public function loadApp() {
            $this->loadConfiguration();
            $this->loadModules();
            $this->loadSensors();
        }

        protected function loadConfiguration() {
            Configuration::prepare(Supplier::getReusableDatabase());
        }

        protected function loadModules() {
            foreach (Configuration::getModules() as $configurationRow) {
                /** @var Module $module */
                $module = new $configurationRow['type'];
                $module->injectConfigurationRow($configurationRow);
                $this->moduleRegistry->registerModule($module, $configurationRow);
            }
        }

        protected function loadSensors() {
            foreach (Configuration::getSensors() as $configurationRow) {
                /** @var Sensor $module */
                $sensor = new $configurationRow['type'];
                $sensor->injectConfigurationRow($configurationRow);
                $this->sensorRegistry->registerSensor($sensor, $configurationRow);
            }
        }

    }