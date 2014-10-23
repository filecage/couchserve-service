<?php

    namespace couchServe\service\Lib\WebSocket\ProtocolMember;
    use couchServe\service\Lib\WebSocket\Lib\Connection;
    use couchServe\service\Lib\WebSocket\Lib\ProtocolMember;
    use couchServe\service\Lib\WebSocket\Handler;
    use couchServe\service\Lib\ModuleRegistry;
    use couchServe\service\Lib\CommandPool;
    use couchServe\service\Lib\Command;

    class ModuleCommand extends ProtocolMember {

        /**
         * @var Handler
         */
        protected $handler;

        /**
         * @param Array $data
         * @param Connection $connection
         */
        public function act(Array $data, Connection $connection) {
            if ((!isset($data['type']) || !isset($data['name'])) && !isset($data['id'])) {
                return;
            }

            $command = (!isset($data['id'])) ? $this->createCommandByTypeAndName($data['type'], $data['name']) : $this->createCommandById($data['id']);
            if (isset($data['data']) && is_array($data['data'])) {
                $command->setData($data['data']);
            }

            $this->getCommandPool()->addCommand($command);
        }

        protected function createCommand() {
            return new Command;
        }

        /**
         * @param $id
         * @return Command
         * @throws \couchServe\Service\Lib\Exceptions\ModuleRegistryException
         */
        protected function createCommandById($id) {
            $command = $this->createCommand();
            $command->setTargetByModule($this->getModuleRegistry()->findLocalModuleById($id));
            return $command;
        }

        /**
         * @param $type
         * @param $name
         * @return Command
         * @throws \couchServe\Service\Lib\Exceptions\ModuleRegistryException
         */
        protected function createCommandByTypeAndName($type, $name) {
            $command = $this->createCommand();
            $command->setTargetByModule($this->getModuleRegistry()->findModuleByTypeAndName($type, $name));
            return $command;
        }


    }