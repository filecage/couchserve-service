<?php

    namespace couchServe\Service\Lib\Exceptions;
    class AutoloadException extends \Exception {
        public function __construct($class, $file) {
            parent::__construct('Did not find requested class "' . $class . '", resolved file path "' . $file . '"');
        }
    }