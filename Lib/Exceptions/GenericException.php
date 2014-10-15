<?php

    namespace couchServe\Service\Lib\Exceptions;
    use couchServe\Service\Lib\Log;
    use \Exception;

    class GenericException extends \Exception {
        public function __construct($message = '', $code = 0, Exception $previous = null) {
            if ($message) {
                Log::error('%s with message "%s"', [
                    get_class($this),
                    $message
                ]);
            }
            parent::__construct($message, $code, $previous);
        }
    }