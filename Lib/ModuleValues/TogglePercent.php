<?php

    namespace couchServe\service\Lib\ModuleValues;
    use couchServe\service\Lib\Abstracts\ModuleValue;
    use couchServe\service\Lib\Command;

    class TogglePercent extends ModuleValue {

        /**
         * @var Int
         */
        protected $value = 0;

        public function getCurrentValue() {
            return $this->value;
        }

        public function setValue($value) {
            $this->value = max(0, min($value, 100));
        }

        public function getOppositeValue($value) {
            return ($value > 0) ? 0 : 100;
        }

        public function getControlType() {
            return ModuleValue::CONTROL_TYPE_TOGGLE;
        }

        /**
         * @param Command $command
         */
        public function setValueFromCommand(Command $command) {
            $data = $command->getData();
            $this->setValue((isset($data[0]) ? $data[0] : 0));
        }

        public function getExportableOptions() {
            return [
                'controlType'   => $this->getControlType(),
                'value'         => $this->getCurrentValue(),
                'nextValue'     => $this->getOppositeValue($this->value),
                'previousValue' => $this->getOppositeValue($this->getOppositeValue($this->value))
            ];
        }

    }