<?php
require_once $RELATIVE_PATH_DOTS.'lib/exceptions/Generic_Exception.php';
if(!class_exists('Database_Exception')){
class Database_Exception extends Generic_Exception
{
	public function __construct($databaseErrorNo, $databaseErrorMessage) {
		$this->code = 50000001;
		$this->message = "A database error occured ($databaseErrorNo): $databaseErrorMessage";

		$this->set_innerException(new Exception($databaseErrorMessage, $databaseErrorNo));

		return;
	}
}
}
?>