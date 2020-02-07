<?php
require_once("../../lib/components/parameterisedpage.inc.php");

class ajaxpageresult
{
    private $success = false;
    private $errorcode=0;
    private $errormessage="";
    private $result;
    
    public function __construct( $success, $resultobj, $errorcode=0, $errormessage="")
    {
        
        $this->success=$success;
        $this->errorcode=$errorcode;
        $this->errormessage = $errormessage;
        $this->result = $resultobj;

    }

    public function encodeJSON()
    {
        $json = new stdClass;
        foreach ($this as $key => $value)
        {
            $json->$key = $value;
        }
        return json_encode($json);
    }

}

class ajaxpage extends parameterisedpage
{

    private $_ajaxpageresult = null;
    private $_accessdenied = false;

    public function __constructor()
    {        
        $this->BufferStart();
    }

    public function Start()
    {
        $this->SetRoot($this);
    }

    function RegisterComponent( $component )
    {
        parent::RegisterComponent($component);
    }

    function SetAccessDenied()
    {
        // This is serious.. No access for you!..
        $this->_accessdenied=true;

    }

    function SetError( $errorCode, $errorMessage)
    {
        $this->_ajaxpageresult = new ajaxpageresult( false, null, $errorCode, $errorMessage);
    }

    function SetResult( $result )
    {
        $this->_ajaxpageresult = new ajaxpageresult( true, $result );
    }

    function Render()
    {
        if($this->_accessdenied)
        {
            // Send an Error message. This causes the onFailure Ajax code to fire.
            header("HTTP/1.0 401 Unauthorized",null,401);
            $this->BufferFlush(); 
        }
        else
        {
            if(isset($this->_ajaxpageresult))
            {
                $json = $this->_ajaxpageresult->encodeJSON() ;
                echo( $json );
            }
            $this->BufferFlush();
        }
    }
}
?>