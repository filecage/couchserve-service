<?php

    namespace couchService\Service\Lib;
    use couchService\Service\Lib\Abstracts\Module;

    class Bootstrap {

        /**
         * @var ModuleRegistry
         */
        protected $moduleRegistry;

        public function __construct() {
            $this->moduleRegistry = new ModuleRegistry();
        }

        public function loadApp() {
            $this->loadConfiguration();
            $this->loadModules();
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

    }