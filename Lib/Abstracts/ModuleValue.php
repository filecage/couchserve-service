<?php

    namespace couchServe\service\Lib\Abstracts;
    use couchServe\Service\Lib\Abstracts\Module;

    abstract class ModuleValue implements \couchServe\service\Lib\Interfaces\ModuleValue {

        const CONTROL_TYPE_TOGGLE = 'CONTROL_TYPE_TOGGLE';

        /**
         * @var Module
         */
        protected $module;

        /**
         * @param Module $module
         */
        public function __construct(Module $module) {
            $this->module = $module;
        }

    }