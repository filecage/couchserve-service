<?php

    namespace couchServe\Service\Lib\Interfaces;
    use couchServe\Service\Lib\Command;

    interface Module {
        function act(Command $command);
    }