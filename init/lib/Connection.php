<?php
/**
 * Gets a basic database connection to use when installing
 */
class Connection {
    
    static $_instance;
    private $conn;
    private $database_array;
    
    /**
     * Returns an instance
     * @return Connection
     */
    static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Connection();
        }
        return self::$_instance;
    }
    
    /**
     * Loads the databases
     * @param $filename
     */
    static function loadDatabasesFromFile($filename) {
        if (!file_exists($filename)) {
            throw new Exception('Cannot load databases from file ' . $filename);
        }
        $ini_file = parse_ini_file($filename, true);
        foreach ($ini_file['databases'] as $key => $value) {
            if (isset($ini_file[$value])) {
                if (isset($ini_file[$value]['param.user'])) { $db_array[$key]['user'] = $ini_file[$value]['param.user']; }
                if (isset($ini_file[$value]['param.password'])) { $db_array[$key]['password'] = $ini_file[$value]['param.password']; }
                if (isset($ini_file[$value]['param.database'])) { $db_array[$key]['database'] = $ini_file[$value]['param.database']; }
                if (isset($ini_file[$value]['param.host'])) { $db_array[$key]['host'] = $ini_file[$value]['param.host']; }
                if (isset($ini_file[$value]['param.port'])) { $db_array[$key]['port'] = $ini_file[$value]['param.port']; }
                if (isset($ini_file[$value]['param.dsn'])) { $db_array[$key]['dsn'] = $ini_file[$value]['param.dsn']; }
                self::getInstance()->setDatabaseArray($db_array);
            }
        }
    }
    
    /**
     * Returns a database connection
     * @return resource
     */
    function getDbConnection($name) {
        $db_array = $this->getDbConnectionArray($name);
        if (($res = mysql_connect($db_array['host'], $db_array['user'], $db_array['password'])) !== null) {
            if (mysql_select_db($db_array['database'], $res) !== false) {
                return $res;
            } else {
                throw new Exception('Failed to select database ' . $db_array['database'] . ' from ' . $db_array['user'] . '@' . $db_array['host']);
            }
        } else {
            throw new Exception('Failed to connect to database host: ' . $db_array['user'] . '@' . $db_array['host'] . ': ' . mysql_error());
        }
    }
    
    /**
     * Returns the database_array
     * @return array
     */
    function getDatabaseArray() {
        if (is_null($this->database_array)) {
            $this->database_array = array();
        }
        return $this->database_array;
    }
    /**
     * Sets the database_array
     * @param array
     */
    function setDatabaseArray($arg0) {
        $this->database_array = $arg0;
        return $this;
    }
    /**
     * Returns the db connection array
     * @return array
     */
    function getDbConnectionArray($name) {
        $db_array = $this->getDatabaseArray();
        if (isset($db_array[$name])) {
            return $db_array[$name];
        } else if (isset($db_array['default'])) {
            return $db_array['default'];
        }
        throw new Exception('No database defined as ' . $name . ' and no default database defined');
    }
    
    
    
}
