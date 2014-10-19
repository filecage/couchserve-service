<?php

    namespace couchServe\service\Lib\ModuleValues;
    use couchServe\service\Lib\Abstracts\ModuleValue;

    class TogglePercent extends ModuleValue {

        /**
         * @var Int
         */
        protected $value = 0;

        public function getCurrentValue() {
            return $this->value;
        }

        public function getControlType() {
            return ModuleValue::CONTROL_TYPE_TOGGLE;
        }

        public function getExportableOptions() {
            return [
                'controlType' => $this->getControlType(),
                'value' => $this->getCurrentValue()
            ];
        }

    }