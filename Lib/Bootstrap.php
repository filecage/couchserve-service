<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\Abstracts\Module;
    use couchServe\Service\Lib\Abstracts\Sensor;
    use couchServe\Service\Lib\Abstracts\Controller;
    use couchServe\Service\Lib\WebSocket\Lib\Server;
    use couchServe\Service\Lib\WebSocket\Container;
    use couchServe\Service\Lib\WebSocket\Handler;

    class Bootstrap {

        /**
         * @var ModuleRegistry
         */
        protected $moduleRegistry;

        /**
         * @var SensorRegistry
         */
        protected $sensorRegistry;

        /**
         * @var ControllerRegistry
         */
        protected $controllerRegistry;

        /**
         * @var Container
         */
        protected $webSocketContainer;

        public function __construct() {
            $this->moduleRegistry = new ModuleRegistry();
            $this->sensorRegistry = new SensorRegistry();
            $this->controllerRegistry = new ControllerRegistry();
        }

        public function loadApp() {
            $this->loadConfiguration();
            $this->loadModules();
            $this->loadSensors();
            $this->loadController();
            $this->setupWebSocket();
            return new App($this->moduleRegistry, $this->sensorRegistry, $this->controllerRegistry, $this->webSocketContainer);
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

        protected function loadController() {
            foreach (Configuration::getController() as $configurationRow) {
                /** @var Controller $controller */
                $controller = new $configurationRow['type'];
                $controller->injectModuleRegistry($this->moduleRegistry);
                $this->controllerRegistry->registerController($controller);
            }
        }

        protected function loadSensors() {
            foreach (Configuration::getSensors() as $configurationRow) {
                /** @var Sensor $sensor */
                $sensor = new $configurationRow['type'];
                $sensor->injectConfigurationRow($configurationRow);
                $this->sensorRegistry->registerSensor($sensor, $configurationRow);
            }
        }

        protected function setupWebSocket() {
            $webSocketHandler = new Handler;
            $webSocketHandler->setModuleRegistry($this->moduleRegistry);
            $webSocketHandler->setSensorRegistry($this->sensorRegistry);

            $webSocketServer = new Server(
                Configuration::getEnvironmentKey(Server::CONFIG_KEY_BIND_IP, '0.0.0.0'),
                Configuration::getEnvironmentKey(Server::CONFIG_KEY_BIND_PORT, 8000),
                false
            );
            $webSocketServer->setCheckOrigin(false);

            $this->webSocketContainer = new Container($webSocketServer, $webSocketHandler);
        }

    }