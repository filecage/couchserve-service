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
            $id = APP_ID . '_' . $configurationRow['id'];
            if (isset($id)) {
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

    }