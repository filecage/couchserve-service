<?php

    define('APP_ID', 1); // has to be a unique identifier when using multiply instances in master/slave mode!

    require_once 'Lib/Exceptions/AutoloadException.php';
    require_once 'Lib/Autoload.php';

    spl_autoload_register('couchServe\Service\Lib\Autoload::load');

    use couchServe\Service\Lib\Log;
    Log::info('Hello! :)');

    $bootstrap = new couchServe\Service\Lib\Bootstrap();
    $app = $bootstrap->loadApp();

    $app->run();