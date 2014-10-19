<?php

    namespace couchServe\Service\Lib\Registries;
    use couchServe\Service\Lib\Abstracts\Controller;
    use couchServe\Service\Lib\Exceptions\ModuleRegistryException;
    use couchServe\Service\Lib\Log;

    class ControllerRegistry {

        /**
         * @var Controller[]
         */
        protected $controllers = [];

        /**
         * @param Controller $controller
         */
        public function registerController(Controller $controller) {
            $this->controllers[] = $controller;

            Log::verbose('Registered new controller of type %s', [
                get_class($controller)
            ]);
        }

        /**
         * @return Controller[]
         */
        public function getControllers() {
            return $this->controllers;
        }

    }