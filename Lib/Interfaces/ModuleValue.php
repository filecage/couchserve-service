<?php

    namespace couchServe\service\Lib\Interfaces;

    interface ModuleValue {

        /**
         * @return string
         */
        function getControlType();

        /**
         * @return mixed
         */
        function getCurrentValue();

        /**
         * @return Array
         */
        function getExportableOptions();

    }