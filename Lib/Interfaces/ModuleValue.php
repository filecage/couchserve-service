<?php

    namespace couchServe\service\Lib\Interfaces;

    interface ModuleValue {

        /**
         * @return string
         */
        function getValueType();

        /**
         * @return mixed
         */
        function getCurrentValue();

    }