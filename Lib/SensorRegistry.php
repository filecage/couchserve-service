<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\Abstracts\Sensor;
    use couchServe\Service\Lib\Exceptions\ModuleRegistryException;

    class SensorRegistry {

        /**
         * @var Sensor[]
         */
        protected $sensors = [];

        /**
         * @param Sensor $sensor
         * @param array $configurationRow
         * @throws ModuleRegistryException
         */
        public function registerSensor(Sensor $sensor, Array $configurationRow) {
            $id = APP_ID . '_' . $configurationRow['id'];
            if (isset($this->sensors[$id])) {
                throw new ModuleRegistryException('Module already registered, multiple registrations are not allowed');
            }

            $this->sensors[$id] = $sensor;
            $sensor->register();

            Log::verbose('Registered new sensor #%d "%s" of type %s', [
                $id,
                $configurationRow['name'],
                $configurationRow['type']
            ]);
        }

        /**
         * @return Abstracts\Sensor[]
         */
        public function getSensors() {
            return $this->sensors;
        }

    }