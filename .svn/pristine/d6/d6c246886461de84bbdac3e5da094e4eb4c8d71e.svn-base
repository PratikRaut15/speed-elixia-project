<?php
include_once 'Generic_Exception.php';

class InvalidArgument_Exception extends Generic_Exception 
{
    public function __construct($argument, $callName) {
        $this->code = 50000002;
        $this->message = "The argument $argument was invalid when calling $callName";
        return;
    }
}
?>