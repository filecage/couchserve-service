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
         * @param Command $command
         */
        public function addCommand(Command $command) {
            $this->commands[] = $command;
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
         * @param string $type
         * @return Sense[]
         */
        public function findSensesBySensorType($type) {
            $senses = [];
            foreach ($this->senses as $sense) {
                if ($sense->getSensor()->getType() == $type) {
                    $senses[] = $sense;
                }
            }
            return $senses;
        }

        /**
         * @param string $type
         * @param string $name
         * @return Sense[]
         */
        public function findSensesBySensorTypeAndName($type, $name) {
            $senses = [];
            foreach ($this->findSensesBySensorType($type) as $sense) {
                if ($sense->getSensor()->getName() == $name) {
                    $senses[] = $sense;
                }
            }
            return $senses;
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