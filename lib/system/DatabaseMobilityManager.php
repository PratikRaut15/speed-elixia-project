<?php


if(!class_exists('Database_Exception')){
    class Database_Exception extends Exception
    {
        public function __construct($databaseErrorNo, $databaseErrorMessage) {
            $this->message = "A database error occured ($databaseErrorNo): $databaseErrorMessage";
       }
    }
}

class DatabaseMobilityManager
{
    
    private $_server = null;
    private $_connection = null;
    private $_database = null;
    private $_username = null;
    private $_password = null;
    private $_queryResult = null;
    private $_currentRow = null;
    private $_affectedRows = -1;
    private $_insertedId = -1;

    public function getQueryResult()
    {
        return $this->_queryResult;
    }

    public function __construct($customerno,$userid)
    {
	// These values are set in config.inc.php and must be included.
        
        $this->_server = "localhost";
        $this->_database = "speed_mobility";
	$this->_username = "UserSpeed";
	$this->_password = "el!365x!@";
        $this->customerno = $customerno;
        $this->userid = $userid;
		 
	return;
    }
	
    protected function connect()
    {
		// Connection is cached between objects.
        global $connection;
        if(isset($connection))
        {
            $this->_connection = $connection;
            return;
        }
        if ($this->_connection == null){
            $this->_connection = @mysql_connect($this->_server, $this->_username, $this->_password);
            if ($this->_connection == null) {
                throw new Database_Exception(@mysql_errno(), @mysql_error());
            }
            else {
                if(!@mysql_select_db($this->_database, $this->_connection)) {
                    $connection = $this->_connection;
                    throw new Database_Exception(@mysql_errno(), @mysql_error());
                }
            }
        }
        return;
    }
	
    public function executeQuery($query) {
        if ($query == null) throw new InvalidArgument_Exception('query', 'executeQuery');
		
            $this->_affectedRows = -1;
            $this->_currentRow = null;
            $this->_insertedId = -1;
		
            $this->connect();
            if (($this->_queryResult = @mysql_query($query, $this->_connection)) == null) {
                throw new Database_Exception(@mysql_errno(), @mysql_error());
            }
            $pos = strpos($query, "SELECT");
            $pos1 = strpos($query, "select");
            if ($pos !== 0) {
                if ($pos1 !== 0) {
                    $log = new Log();
                    $log->createlog($this->customerno, ($query), $this->userid);
                }
            }                 
        return;
    }
	
    public function get_affectedRows() {
        $affectedRows = 0;
	if ($this->_affectedRows != -1) {
            $affectedRows = $this->_affectedRows;
        }
        else {
            if ($this->_queryResult != null) {
                $affectedRows = $this->_affectedRows = @mysql_affected_rows($this->_connection);
		// TODO: check if the call failed and throw an exception
            }
	}
	return $affectedRows;
    }
	
    public function get_insertedId() {
        $insertedId = 0;
		
	if ($this->_insertedId != -1) {
            $insertedId = $this->_insertedId;
	}
        else {
            $insertedId = $this->_insertedId = @mysql_insert_id($this->_connection);
            // TODO: check if the call failed and throw an exception
	}
	return $insertedId;
    }
    
    public function get_nextRow() {
        $nextRow = null;
	if ($this->_queryResult != null) {
            $this->_currentRow = $nextRow = @mysql_fetch_array($this->_queryResult);
	}
	return $nextRow;
    }
	
    public function get_rowCount() {
        $count = 0;
	if($this->_queryResult != null) {
            $count = @mysql_num_rows($this->_queryResult);
	}
	return $count;
    }
	
    public function get_currentRow() {
        return $this->_currentRow;
    }

    public function get_recordSet($numass=MYSQL_BOTH)
    {
        $got = null;
        if ($this->_queryResult != null) {
            $i=0;
            $data = @mysql_fetch_array($this->_queryResult, $numass);
            if (isset($data) && $data != null)
            {
                $keys=array_keys($data);
                @mysql_data_seek($this->_queryResult, 0);
                while ($row = @mysql_fetch_array($this->_queryResult, $numass))
                {
                    foreach ($keys as $speckey)
                    {
                        $got[$i][$speckey]=$row[$speckey];
                    }
                    $i++;
                }
            }
        }
        return $got;
    }
}

?>
