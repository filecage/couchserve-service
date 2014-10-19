<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\Registries\ModuleRegistry;
    use couchServe\Service\Lib\Registries\GroupRegistry;
    use couchServe\Service\Lib\Registries\SensorRegistry;
    use couchServe\Service\Lib\Registries\ControllerRegistry;
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
         * @var GroupRegistry
         */
        protected $groupRegistry;

        /**
         * @var Container
         */
        protected $webSocketContainer;

        public function __construct() {
            $this->moduleRegistry = new ModuleRegistry();
            $this->sensorRegistry = new SensorRegistry();
            $this->controllerRegistry = new ControllerRegistry();
            $this->groupRegistry = new GroupRegistry();
        }

        public function loadApp() {
            $this->loadConfiguration();
            $this->loadGroups();
            $this->loadModules();
            $this->loadSensors();
            $this->loadController();
            $this->setupWebSocket();

            return new App(
                $this->moduleRegistry,
                $this->sensorRegistry,
                $this->controllerRegistry,
                $this->webSocketContainer
            );
        }

        protected function loadConfiguration() {
            Configuration::prepare(Supplier::getReusableDatabase());
        }

        protected function loadModules() {
            foreach (Configuration::getModules() as $configurationRow) {
                /** @var Module $module */
                $module = new $configurationRow['type'];
                $module->injectConfigurationRow($configurationRow);
                if ($configurationRow['groupId']) {
                    $module->setGroup($this->groupRegistry->getGroupById($configurationRow['groupId']));
                }
                $this->moduleRegistry->registerModule($module, $configurationRow);
                Log::verbose('Registered new module #%d "%s" of type %s', [
                    $configurationRow['id'],
                    $configurationRow['name'],
                    $configurationRow['type']
                ]);
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

        protected function loadGroups() {
            foreach (Configuration::getGroups() as $configurationRow) {
                $group = new Group($configurationRow['id'], $configurationRow['name'], $configurationRow['description']);
                $this->groupRegistry->registerGroup($group);
                Log::verbose('Registered new group #%d "%s"', [
                    $configurationRow['id'],
                    $configurationRow['name']
                ]);
            }
        }

        protected function loadSensors() {
            foreach (Configuration::getSensors() as $configurationRow) {
                /** @var Sensor $sensor */
                $sensor = new $configurationRow['type'];
                $sensor->injectConfigurationRow($configurationRow);
                if ($configurationRow['groupId']) {
                    $sensor->setGroup($this->groupRegistry->getGroupById($configurationRow['groupId']));
                }
                $this->sensorRegistry->registerSensor($sensor, $configurationRow);
                Log::verbose('Registered new sensor #%d "%s" of type %s', [
                    $configurationRow['id'],
                    $configurationRow['name'],
                    $configurationRow['type']
                ]);
            }
        }

        protected function setupWebSocket() {
            $webSocketHandler = new Handler;
            $webSocketHandler->setModuleRegistry($this->moduleRegistry);
            $webSocketHandler->setSensorRegistry($this->sensorRegistry);
            $webSocketHandler->setGroupRegistry($this->groupRegistry);

            $webSocketServer = new Server(
                Configuration::getEnvironmentKey(Server::CONFIG_KEY_BIND_IP, '0.0.0.0'),
                Configuration::getEnvironmentKey(Server::CONFIG_KEY_BIND_PORT, 8000),
                false
            );
            $webSocketServer->setCheckOrigin(false);

            $this->webSocketContainer = new Container($webSocketServer, $webSocketHandler);
        }

    }