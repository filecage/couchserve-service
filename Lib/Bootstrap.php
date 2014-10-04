<?php

    namespace couchService\Service\Lib;
    use couchService\Service\Lib\Configuration;
    use couchService\Service\Lib\Database;
    use couchService\Service\Lib\Supplier;

    class Bootstrap {

        public function __construct() {
            $this->loadConfiguration();
        }

        protected function loadConfiguration() {

        }

    }