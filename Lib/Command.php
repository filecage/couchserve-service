<?php

    namespace couchService\Service\Lib;

    class Command {

        /**
         * @var string
         */
        protected $targetId;

        /**
         * @var string
         */
        protected $type;

        /**
         * @var string
         */
        protected $name;

        /**
         * @var Array
         */
        protected $data = [];

        /**
         * @return mixed
         */
        public function getData() {
            return $this->data;
        }

        /**
         * @param mixed $data
         */
        public function setData(Array $data) {
            $this->data = $data;
        }

        /**
         * @return string
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param string $name
         */
        public function setName($name) {
            $this->name = $name;
        }

        /**
         * @return string
         */
        public function getTargetId() {
            return $this->targetId;
        }

        /**
         * @param string $targetId
         */
        public function setTargetId($targetId) {
            $this->targetId = $targetId;
        }

        /**
         * @return string
         */
        public function getType() {
            return $this->type;
        }

        /**
         * @param string $type
         */
        public function setType($type) {
            $this->type = $type;
        }



    }