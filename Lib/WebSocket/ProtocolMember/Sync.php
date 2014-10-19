<?php

    namespace couchServe\service\Lib\WebSocket\ProtocolMember;
    use couchServe\service\Lib\WebSocket\Lib\ProtocolMember;
    use couchServe\service\Lib\WebSocket\Lib\Connection;
    use couchServe\service\Lib\Group;

    class Sync extends ProtocolMember {

        public function act(Array $data, Connection $connection) {
            $this->send($connection, [
                'type' => 'SYNC',
                'groups' => $this->getGroups()
            ]);
        }

        protected function getGroups() {
            $groups = [];
            foreach ($this->handler->getGroupRegistry()->getGroups() as $group) {
                $groups[] = [
                    'id' => $group->getId(),
                    'name' => $group->getName(),
                    'description' => $group->getDescription(),
                    'modules' => $this->collectExportableGroupModuleData($group),
                    'sensors' => $this->collectExportableGroupSensorData($group)
                ];
            }
            return $groups;
        }

        /**
         * @param Group $group
         * @return Array
         */
        protected function collectExportableGroupModuleData(Group $group) {
            $moduleData = [];
            foreach ($group->getModuleRegistry()->getModules() as $module) {
                $moduleData[] = [
                    'id' => $module->getId(),
                    'type' => $module->getType(),
                    'name' => $module->getName()
                ];
            }
            return $moduleData;
        }

        /**
         * @param Group $group
         * @return Array
         */
        protected function collectExportableGroupSensorData(Group $group) {
            $sensorData = [];
            foreach ($group->getSensorRegistry()->getSensors() as $sensor) {
                $sensorData[] = [
                    'id' => $sensor->getId(),
                    'type' => $sensor->getType(),
                    'name' => $sensor->getName()
                ];
            }
            return $sensorData;
        }
    }