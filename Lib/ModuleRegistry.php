<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\Abstracts\Module;
    use couchServe\Service\Lib\Exceptions\ModuleRegistryException;

    class ModuleRegistry {

        /**
         * @var Module[]
         */
        protected $modules = [];

        /**
         * @param Module $module
         * @param array $configurationRow
         * @throws ModuleRegistryException
         */
        public function registerModule(Module $module, Array $configurationRow) {
            $id = APP_ID . '_' . $configurationRow['id'];
            if (isset($this->modules[$id])) {
                throw new ModuleRegistryException('Module already registered, multiple registrations are not allowed');
            }

            $this->modules[$id] = $module;
            $module->register();

            Log::verbose('Registered new module #%d "%s" of type %s', [
                $id,
                $configurationRow['name'],
                $configurationRow['type']
            ]);
        }

        /**
         * @return Abstracts\Module[]
         */
        public function getModules() {
            return $this->modules;
        }

    }