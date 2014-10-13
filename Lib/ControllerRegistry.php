<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\Abstracts\Controller;
    use couchServe\Service\Lib\Exceptions\ModuleRegistryException;

    class ModuleRegistry {

        /**
         * @var Controller[]
         */
        protected $controllers = [];

        /**
         * @param Controller $controller
         * @throws ModuleRegistryException
         */
        public function registerController(Controller $controller) {
            $this->controllers[] = $controller;

            Log::verbose('Registered new controller of type %s', [
                get_class($controller)
            ]);
        }

        /**
         * @return Abstracts\Module[]
         */
        public function getControllers() {
            return $this->controllers;
        }

    }