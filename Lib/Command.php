<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\Abstracts\Controller;
    use couchServe\Service\Lib\Abstracts\Module;

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
         * @var Controller
         */
        protected $origin;

        /**
         * @return Array
         */
        public function getData() {
            return $this->data;
        }

        /**
         * @param Array $data
         * @return $this
         */
        public function setData(Array $data) {
            $this->data = $data;
            return $this;
        }

        public function setTargetByModule(Module $module) {
            $this->setName($module->getName())
                 ->setTargetId($module->getId())
                 ->setType($module->getType());

            return $this;
        }

        /**
         * @return String
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param String $name
         * @return $this
         */
        public function setName($name) {
            $this->name = $name;
            return $this;
        }

        /**
         * @return String
         */
        public function getTargetId() {
            return $this->targetId;
        }

        /**
         * @param Int $targetId
         * @return $this
         */
        public function setTargetId($targetId) {
            $this->targetId = APP_ID . '_' . $targetId;
            return $this;
        }

        /**
         * @param String $targetId
         * @return $this
         */
        public function setTargetIdAnonymous($targetId) {
            $this->targetId = $targetId;
            return $this;
        }

        /**
         * @return String
         */
        public function getType() {
            return $this->type;
        }

        /**
         * @param String $type
         * @return $this
         */
        public function setType($type) {
            $this->type = $type;
            return $this;
        }

        /**
         * @return Controller
         */
        public function getOrigin() {
            return $this->origin;
        }

        /**
         * @param Controller $origin
         * @return $this
         */
        public function setOrigin(Controller $origin) {
            $this->origin = $origin;
            return $this;
        }

    }