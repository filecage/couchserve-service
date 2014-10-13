<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Database;
    use couchServe\Service\Lib\CommandPool;
    use couchServe\Service\Lib\Command;
    use couchServe\Service\Lib\ModuleRegistry;

    abstract class Controller {

        /**
         * @var Database
         */
        protected $database;

        /**
         * @var ModuleRegistry
         */
        protected $moduleRegistry;

        /**
         * @param ModuleRegistry $moduleRegistry
         * @return $this
         */
        public function injectModuleRegistry(ModuleRegistry $moduleRegistry) {
            $this->moduleRegistry = $moduleRegistry;
            return $this;
        }

        /**
         * @param CommandPool $commandPool
         * @return $this
         */
        public abstract function react(CommandPool $commandPool);

    }