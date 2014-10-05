<?php

    namespace couchServe\Service\Lib;

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
         * @return Array
         */
        public function getData() {
            return $this->data;
        }

        /**
         * @param Array $data
         */
        public function setData(Array $data) {
            $this->data = $data;
        }

        /**
         * @return String
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param String $name
         */
        public function setName($name) {
            $this->name = $name;
        }

        /**
         * @return String
         */
        public function getTargetId() {
            return $this->targetId;
        }

        /**
         * @param Int $targetId
         */
        public function setTargetId($targetId) {
            $this->targetId = APP_ID . '_' . $targetId;
        }

        /**
         * @param String $targetId
         */
        public function setTargetIdAnonymous($targetId) {
            $this->targetId = $targetId;
        }

        /**
         * @return String
         */
        public function getType() {
            return $this->type;
        }

        /**
         * @param String $type
         */
        public function setType($type) {
            $this->type = $type;
        }



    }