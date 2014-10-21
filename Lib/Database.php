<?php

    namespace couchServe\Service\Lib;
    use couchServe\Service\Lib\Exceptions\DatabaseException;

    /**
     * Extends mysqli by some useful functions
     *
     * @author David Beuchert
     */
    
    class Database extends \mysqli {

        /**
         * The last query result object
         * @var \mysqli_result
         */
        protected $result;

        /**
         * Instances mysqli by using the default access and checks for connection errors
         *
         * @throws DatabaseException
         * @param String $host
         * @param String $db
         * @param String $user
         * @param String $pass
         */
        public function __construct($host, $db, $user = 'root', $pass = '') {
        
            parent::__construct($host, $user, $pass, $db);
            
            if ($this->connect_error) {
                throw new DatabaseException('Could not connect to database - MySQL error: ' . $this->connect_error, $this->connect_errno);
            }
            
        }

        /**
         * Perfoms a query and stores the result for later use
         *
         * @param String $query
         * @param Array $args
         * @throws DatabaseException
         * @return Database
         */
        public function query($query, $args = []) {

            if (count($args) > 0) {
                if (!is_array($args)) {
                    $args = [$args];
                }
                foreach ($args as $key => $arg) {
                    $args[$key] = $this->escape_string($arg);
                }
                $query = vsprintf($query, $args);
            }
        
            $this->result = parent::query($query);
            
            if ($this->result === false) {
                throw new DatabaseException('MySQL Query "' . $query . '" failed with message: ' . $this->error, $this->errno);
            }
            
            return $this;
            
        }

        /**
         * Returns an array of result rows based on the latest result
         *
         * @throws DatabaseException
         * @return Array
         */
        public function getArray() {
        
            if (!($this->result instanceof \mysqli_result)) {
                throw new DatabaseException('Unable to return result array as the result is not an instance of MySQLi_Result');
            }
            
            $return = [];
            while ($row = $this->result->fetch_assoc()) {
                $return[] = $row;
            }
            
            return $return;
            
        }
        
        /**
         * Returns a single result row
         *
         * @return Array
         */
        public function getSingle() {
            
            $result = $this->getArray();
            if (count($result) > 0) {
                return $result[0];
            }
            
            return [];
            
        }
        
        /**
         * Returns the current result object
         *
         * @return \mysqli_result
         */
        public function getResult() {
            return $this->result;
        }
        
    }