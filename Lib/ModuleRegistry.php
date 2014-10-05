<?php

    namespace couchService\Service\Lib;
    use couchService\Service\Lib\Abstracts\Module;
    use couchService\Service\Lib\Exceptions\ModuleRegistryException;

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
            if (isset($this->modules[$configurationRow['id']])) {
                throw new ModuleRegistryException('Module already registered, multiple registrations are not allowed');
            }
            $this->modules[$configurationRow['id']] = $module;
            $module->register();

            Log::verbose('Registered new module #%d "%s" of type %s', [
                $configurationRow['id'],
                $configurationRow['name'],
                $configurationRow['type']
            ]);
        }

    }