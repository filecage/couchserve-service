<?php

    namespace couchServe\Service\Lib\Registries;
    use couchServe\Service\Lib\Abstracts\Sensor;
    use couchServe\Service\Lib\Exceptions\ModuleRegistryException;
    use couchServe\Service\Lib\Log;

    class SensorRegistry {

        /**
         * @var Sensor[]
         */
        protected $sensors = [];

        /**
         * @param Sensor $sensor
         * @throws ModuleRegistryException
         */
        public function registerSensor(Sensor $sensor) {
            $id = APP_ID . '_' . $sensor->getId();
            if (isset($this->sensors[$id])) {
                throw new ModuleRegistryException('Module already registered, multiple registrations are not allowed');
            }

            $this->sensors[$id] = $sensor;
            $sensor->register();
        }

        /**
         * @return Sensor[]
         */
        public function getSensors() {
            return $this->sensors;
        }

    }