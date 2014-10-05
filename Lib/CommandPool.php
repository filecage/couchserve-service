<?php

    namespace couchServe\Service\Lib;

    class CommandPool {

        /**
         * @var Command[]
         */
        protected $commands = [];

        /**
         * @param Command[] $commands
         */
        public function addCommands(Array $commands) {
            foreach ($commands as $command) {
                $this->commands[] = $command;
            }
        }

        /**
         * @return Command[]
         */
        public function getCommands() {
            return $this->commands;
        }

    }