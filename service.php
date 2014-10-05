<?php

    define('APP_ID', 1); // has to be a unique identifier when using multiply instances in master/slave mode!

    require_once 'Lib/Exceptions/AutoloadException.php';
    require_once 'Lib/Autoload.php';

    spl_autoload_register('couchService\Service\Lib\Autoload::load');

    use couchService\Service\Lib\Log;
    Log::info('Hello! :)');

    $bootstrap = new couchService\Service\Lib\Bootstrap();
    $bootstrap->loadApp();