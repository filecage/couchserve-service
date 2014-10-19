<?php

    namespace couchServe\service\Lib;

    class Group {

        /**
         * @var Int
         */
        protected $id;

        /**
         * @var String
         */
        protected $name;

        /**
         * @var String
         */
        protected $description;

        /**
         * @param $id
         * @param $name
         * @param $description
         */
        public function __construct($id, $name, $description) {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
        }

        /**
         * @return String
         */
        public function getDescription() {
            return $this->description;
        }

        /**
         * @return Int
         */
        public function getId() {
            return $this->id;
        }

        /**
         * @return String
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param Int $id
         */
        public function setId($id) {
            $this->id = $id;
        }

        /**
         * @param String $name
         */
        public function setName($name) {
            $this->name = $name;
        }

    }