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
         * @param $table
         * @param array $fields
         * @param array $values
         * @throws DatabaseException
         * @return $this
         */
        public function insert($table, Array $fields, Array $values) {
            $valueRows = [];
            foreach ($values as $valueRow) {
                if (count($valueRow) != count($fields)) {
                    throw new DatabaseException('Value count does not match field count');
                }

                $valueRowValues = [];
                foreach ($valueRow as $value) {
                    $valueRowValues[] = sprintf("'%s'", $this->escape_string($value));
                }
                $valueRows[] = 'VALUES(' . implode(',', $valueRowValues) . ')';
            }

            return $this->query('INSERT INTO %s(%s) ' . implode(',', $valueRows), [
                $table,
                implode(',', $fields),
            ]);
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
         * @return mixed
         */
        public function getScalar() {
            $result = $this->getSingle();
            return array_shift($result);
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