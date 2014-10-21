<?php

    namespace couchServe\Service\Lib\Abstracts;
    use couchServe\Service\Lib\Exceptions\DatabaseException;
    use couchServe\Service\Lib\Exceptions\GenericException;
    use couchServe\Service\Lib\Group;
    use couchServe\Service\Lib\Sense;
    use couchServe\Service\Lib\Supplier;

    abstract class Sensor {

        /**
         * @var Int
         */
        protected $id;

        /**
         * @var String
         */
        protected $type;

        /**
         * @var String
         */
        protected $name;

        /**
         * @var Array
         */
        protected $configurationRow;

        /**
         * @var Sense
         */
        protected $sense;

        /**
         * @var Sense
         */
        protected $latestSense;

        /**
         * @var Group
         */
        protected $group;

        /**
         * @var bool
         */
        private $cacheSense = true;

        /**
         * @return $this
         * @throws GenericException
         */
        public final function sense() {
            if (isset($this->sense)) {
                $this->latestSense = $this->sense;
            }

            $value = $this->getValue();
            if (!is_scalar($value) && !is_array($value)) {
                throw new GenericException('Sensor may only return scalar or array values');
            }

            $this->sense = new Sense($this, $value);
            $this->storeSenseToCacheIfChanged();
            return $this;
        }

        /**
         * @return bool
         */
        public function valueHasChangedSinceLatestSense() {
            if (!isset($this->latestSense)) {
                return true;
            }

            if ($this->latestSense->getValue() != $this->sense->getValue()) {
                return true;
            }

            return false;
        }

        protected function storeSenseToCacheIfChanged() {
            if (!$this->valueHasChangedSinceLatestSense() || !$this->cacheSense) {
                return;
            }

            Supplier::getReusableDatabase()->insert(
                'sensorValues',
                ['sensorId', 'sensorValue', 'senseTime'],
                [[$this->getId(), serialize($this->sense->getValue()), date('c')]]
            );
        }

        /**
         * @return $this
         */
        public abstract function getValue();

        /**
         * @return Sense
         */
        public function getCurrentSense() {
            return $this->sense;
        }

        /**
         * @return Sense
         */
        public function getLatestSense() {
            return $this->latestSense;
        }

        /**
         * @param array $row
         */
        public function injectConfigurationRow(Array $row) {
            $this->configurationRow = $row;
            $this->type = $row['type'];
            $this->name = $row['name'];
            $this->id = $row['id'];
        }

        /**
         * @return String
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @return String
         */
        public function getType() {
            return $this->type;
        }

        /**
         * @return Group
         */
        public function getGroup() {
            return $this->group;
        }

        /**
         * @return Int
         */
        public function getId() {
            return $this->id;
        }

        /**
         * @param Group $group
         */
        public function setGroup($group) {
            $this->group = $group;
            $this->group->registerSensor($this);
        }


        /**
         * @param String$datetimeStr
         * @return Sense
         * @throws DatabaseException
         */
        public function getHistoricalSense($datetimeStr) {
            $datetime = new \DateTime($datetimeStr);
            $database = Supplier::getReusableDatabase()->query("
                  SELECT
                        sensorValue,
                        senseTime
                    FROM
                        sensorValues
                    WHERE
                            sensorId = %d
                        AND TIMEDIFF('%s', senseTime) >= 0
                    ORDER BY
                        senseTime DESC
                    LIMIT 1
                ", [
                    $this->getId(),
                    $datetime->format('c')
                ]
            );

            if (!$database->affected_rows) {
                return new Sense($this, null);
            }

            $sense = $database->getArray();
            return new Sense($this, unserialize($sense['sensorValue']), $sense['senseTime']);
        }

        /**
         * @return $this
         */
        public function disableCaching() {
            $this->cacheSense = false;
            return $this;
        }

        public function register() { }

        public function unregister() { }

    }