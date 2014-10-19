<?php

    namespace couchServe\service\Lib\Abstracts;
    use couchServe\Service\Lib\Interfaces\Module;

    abstract class ModuleValue implements \couchServe\service\Lib\Interfaces\ModuleValue {

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