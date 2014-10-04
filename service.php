<?php

    require_once 'Exceptions/AutoloadException.php';
    require_once 'Lib/Autoload.php';

    spl_autoload_register('\couchService\Service\Lib\Autoload::load');

