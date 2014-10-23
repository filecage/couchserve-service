<?php

    namespace couchServe\Service\Lib\Registries;
    use couchServe\Service\Lib\Abstracts\Module;
    use couchServe\Service\Lib\Exceptions\GenericException;
    use couchServe\Service\Lib\Exceptions\ModuleRegistryException;
    use couchServe\Service\Lib\Log;

    class ModuleRegistry {

        /**
         * @var Module[]
         */
        protected $modules = [];

        /**
         * @param Module $module
         * @throws ModuleRegistryException
         */
        public function registerModule(Module $module) {
            $id = APP_ID . '_' . $module->getId();
            if (isset($this->modules[$id])) {
                throw new ModuleRegistryException('Module already registered in registry instance, multiple registrations are not allowed');
            }

            $this->modules[$id] = $module;
            $module->register();
        }

        /**
         * @param String $type
         * @param String $name
         * @throws ModuleRegistryException
         * @return Module
         */
        public function findModuleByTypeAndName($type, $name) {
            foreach ($this->modules as $module) {
                if ($module->getType() == $type && $module->getName() == $name) {
                    return $module;
                }
            }
            throw new ModuleRegistryException(vsprintf('Could not find module by type "%s" and name "%s"', [
                $type,
                $name
            ]));
        }

        /**
         * @param String $type
         * @return Module[]
         */
        public function findModulesByType($type) {
            $modules = [];
            foreach ($this->modules as $module) {
                if ($module->getType() == $type) {
                    $modules[] = $module;
                }
            }
            return $modules;
        }

        /**
         * @param $id
         * @return Module
         * @throws ModuleRegistryException
         */
        public function findLocalModuleById($id) {
            $id = APP_ID . '_' . $id;
            if (!isset($this->modules[$id])) {
                throw new ModuleRegistryException(vsprintf('Could not find module by id %d', [$id]));
            }
            return $this->modules[$id];
        }

        /**
         * @return Module[]
         */
        public function getModules() {
            return $this->modules;
        }

    }