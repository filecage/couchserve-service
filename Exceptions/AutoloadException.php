<?php

    namespace couchService\Service\Exceptions;
    class AutoloadException extends \Exception {
        public function __construct($class, $file) {
            parent::__construct('Autoloader did not find requested class "' . $class . '", resolved file path "' . $file . '"');
        }
    }