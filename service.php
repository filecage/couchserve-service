<?php

    require_once 'Lib/Exceptions/AutoloadException.php';
    require_once 'Lib/Autoload.php';

    spl_autoload_register('couchService\Service\Lib\Autoload::load');

    $bootstrap = new couchService\Service\Lib\Bootstrap();
    $bootstrap->loadApp();