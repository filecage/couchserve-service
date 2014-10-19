<?php

    namespace couchServe\service\Lib;
    use couchServe\service\Lib\Exceptions\ModuleRegistryException;
    use couchServe\service\Lib\Registries\ModuleRegistry;
    use couchServe\service\Lib\Registries\SensorRegistry;
    use couchServe\service\Lib\Abstracts\Module;
    use couchServe\service\Lib\Abstracts\Sensor;

    class Group {

        /**
         * @var Int
         */
        protected $id;

        /**
         * @var String
         */
        protected $name;

        /**
         * @var String
         */
        protected $description;

        /**
         * @var ModuleRegistry
         */
        protected $moduleRegistry;

        /**
         * @var SensorRegistry
         */
        protected $sensorRegistry;

        /**
         * @param $id
         * @param $name
         * @param $description
         */
        public function __construct($id, $name, $description) {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;

            $this->moduleRegistry = new ModuleRegistry();
            $this->sensorRegistry = new SensorRegistry();
        }

        /**
         * @param Module $module
         * @throws ModuleRegistryException
         */
        public function registerModule(Module $module) {
            $this->moduleRegistry->registerModule($module);
        }

        /**
         * @param Sensor $sensor
         * @throws ModuleRegistryException
         */
        public function registerSensor(Sensor $sensor) {
            $this->sensorRegistry->registerSensor($sensor);
        }

        /**
         * @return String
         */
        public function getDescription() {
            return $this->description;
        }

        /**
         * @return Int
         */
        public function getId() {
            return $this->id;
        }

        /**
         * @return String
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param Int $id
         */
        public function setId($id) {
            $this->id = $id;
        }

        /**
         * @param String $name
         */
        public function setName($name) {
            $this->name = $name;
        }

    }