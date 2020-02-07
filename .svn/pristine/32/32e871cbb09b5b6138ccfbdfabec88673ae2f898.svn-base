<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
	$RELATIVE_PATH_DOTS = "../../";
}
if (!defined('RELATIVE_PATH_DOTS')) {
	define("RELATIVE_PATH_DOTS", $RELATIVE_PATH_DOTS);
}
require_once $RELATIVE_PATH_DOTS . 'lib/exceptions/Database_Exception.php';
require_once $RELATIVE_PATH_DOTS . 'lib/exceptions/InvalidArgument_Exception.php';
require_once $RELATIVE_PATH_DOTS . 'config.inc.php';

class DatabaseManager {

	private $_server = null;
	private $_connection = null;
	private $_database = null;
	private $_username = null;
	private $_password = null;
	private $_queryResult = null;
	private $_currentRow = null;
	private $_affectedRows = -1;
	private $_insertedId = -1;
	private $_dsn = null;
	private $_tech_dsn = null;
	private $_tech_database = null;

	public function getQueryResult() {
		return $this->_queryResult;
	}

	public function __construct() {
		// These values are set in config.inc.php and must be included.

		$this->_server = DB_HOST;
		$this->_database = SPEEDDB;
		$this->_username = DB_LOGIN;
		$this->_password = DB_PWD;
		$this->_tech_database = DB_ELIXIATECH;
		$this->_dsn = 'mysql:dbname=' . $this->_database . ';host=' . $this->_server . '';
		$this->_tech_dsn = 'mysql:dbname=' . $this->_tech_database . ';host=' . $this->_server . '';
		return;
	}

	protected function connect() {
		// Connection is cached between objects.
		global $connection;
		if (isset($connection)) {
			$this->_connection = $connection;
			return;
		}
		if ($this->_connection == null) {

			$this->_connection = @mysqli_connect($this->_server, $this->_username, $this->_password, $this->_database);

			@mysqli_set_charset($this->_connection, "utf8");
			if ($this->_connection == null) {
				throw new Database_Exception(@mysqli_errno(), @mysqli_error());
			} else {
				if (!@mysqli_select_db($this->_connection, $this->_database)) {
					$connection = $this->_connection;
					throw new Database_Exception(@mysqli_errno(), @mysqli_error());
				}
			}
		}
		return;
	}

	public function executeQuery($query) {
		if ($query == null) {
			throw new InvalidArgument_Exception('query', 'executeQuery');
		}

		$this->_affectedRows = -1;
		$this->_currentRow = null;
		$this->_insertedId = -1;

		$this->connect();
		if (($this->_queryResult = @mysqli_query($this->_connection, $query)) == null) {

			throw new Database_Exception(@mysqli_errno(), @mysqli_error());
		}
		$pos = strpos($query, "SELECT");
		$pos1 = strpos($query, "select");
		if ($pos !== 0) {
			if ($pos1 !== 0) {
				require_once RELATIVE_PATH_DOTS . 'lib/system/Log.php';
				$log = new Log();
				$custno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : '';
				$usrid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
				$log->createlog($custno, ($query), $usrid);
			}
		}

		return;
	}

	public function get_affectedRows() {
		$affectedRows = 0;
		if ($this->_affectedRows != -1) {
			$affectedRows = $this->_affectedRows;
		} else {
			if ($this->_queryResult != null) {
				$affectedRows = $this->_affectedRows = @mysqli_affected_rows($this->_connection);
				// TODO: check if the call failed and throw an exception
			}
		}

		return $affectedRows;
	}

	public function get_insertedId() {
		$insertedId = 0;

		if ($this->_insertedId != -1) {
			$insertedId = $this->_insertedId;
		} else {
			$insertedId = $this->_insertedId = @mysqli_insert_id($this->_connection);
			// TODO: check if the call failed and throw an exception
		}

		return $insertedId;
	}

	public function get_nextRow() {
		$nextRow = null;
		if ($this->_queryResult != null) {
			$this->_currentRow = $nextRow = @mysqli_fetch_array($this->_queryResult);
		}
		return $nextRow;
	}

// <editor-fold defaultstate="collapsed" desc="get_resultRow">
	public function get_resultRow() {
		$nextRow = null;
		if ($this->_queryResult != null) {
			$this->_currentRow = $nextRow = @mysqli_fetch_assoc($this->_queryResult);
		}
		return $nextRow;
	}

	// </editor-fold>

	public function get_rowCount() {
		$count = 0;
		if ($this->_queryResult != null) {
			$count = @mysqli_num_rows($this->_queryResult);
		}
		return $count;
	}

	public function get_currentRow() {
		return $this->_currentRow;
	}

	public function get_recordSet($numass = MYSQLI_ASSOC) {
		$got = null;
		if ($this->_queryResult != null) {
			$i = 0;
			$data = @mysqli_fetch_array($this->_queryResult, $numass);
			if (isset($data) && $data != null) {
				$keys = array_keys($data);
				@mysqli_data_seek($this->_queryResult, 0);
				while ($row = @mysqli_fetch_array($this->_queryResult, $numass)) {
					foreach ($keys as $speckey) {
						$got[$i][$speckey] = $row[$speckey];
					}
					$i++;
				}
			}
		}
		return $got;
	}

	// <editor-fold defaultstate="collapsed" desc="PDOConn">
	public function CreatePDOConn() {
		$pdo = new PDO($this->_dsn, $this->_username, $this->_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		return $pdo;
	}

	public function ClosePDOConn($pdo) {
		$pdo = NULL;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Prepare SP">
	public function PrepareSP($sp_name, $sp_params) {
		return "call " . $sp_name . "(" . $sp_params . ");";
	}

	// </editor-fold>

	public function query($sql, $errorFile, $errorLine) {
		$result = mysqli_query($this->_connection, $sql) or die($this->error($sql . "<br />" . mysqli_error($this->_connection), $errorFile, $errorLine));
		return $result;
	}

	public function next_result() {
		if (mysqli_more_results($this->_connection)) {
			return $this->_connection->next_result();
		} else {
			return null;
		}
	}

	public function num_rows($result) {
		return mysqli_num_rows($result);
	}

	public function fetch_array($result) {
		$row = mysqli_fetch_array($result);
		if (count($row) - 1 > 0) {
			foreach ($row as $key => $value) {
				$row[$key] = stripslashes($value);
			}

		}
		return $row;
	}

	/* POOD Connection For ELIXIATECH Parent DB*/
	public function createPDOConnForTech() {
		$pdo = new PDO($this->_tech_dsn, $this->_username, $this->_password);
		return $pdo;
	}

}

?>
