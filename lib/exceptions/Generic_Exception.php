<?php
abstract class Generic_Exception extends Exception
{
	private $_innerExcpetion = null;
	
	public final function set_innerException($exception) {
		$this->_innerExcpetion = $exception;
		return;
	}
	public final function get_innerException() {
		return $this->_innerExcpetion;
	}
}
?>