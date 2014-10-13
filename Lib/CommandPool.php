<?php

    namespace couchServe\Service\Lib;

    class CommandPool {

        /**
         * @var Command[]
         */
        protected $commands = [];

        /**
         * @var Sense[]
         */
        protected $senses = [];

        /**
         * @param Command[] $commands
         */
        public function addCommands(Array $commands) {
            foreach ($commands as $command) {
                $this->commands[] = $command;
            }
        }

        /**
         * @param array $senses
         */
        public function addSenses(Array $senses) {
            foreach ($senses as $sense) {
                $this->senses[] = $sense;
            }
        }

        /**
         * @return Command[]
         */
        public function getCommands() {
            return $this->commands;
        }

        /**
         * @return Sense[]
         */
        public function getSenses() {
            return $this->senses;
        }

    }