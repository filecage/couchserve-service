<?php

    namespace couchServe\Service\Lib\Registries;
    use couchServe\Service\Lib\Group;

    class GroupRegistry {

        /**
         * @var Group[]
         */
        protected $groups = [];

        /**
         * @param Group $group
         */
        public function registerGroup(Group $group) {
            $this->groups[$group->getId()] = $group;
        }

        /**
         * @return Group[]
         */
        public function getGroups() {
            return $this->groups;
        }

        /**
         * @param Int $id
         * @return Group
         */
        public function getGroupById($id) {
            return $this->groups[$id];
        }

    }