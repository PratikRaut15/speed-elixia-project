<?php
require_once("components/component.inc.php");
require_once("system/Sanitise.php");


class parameterisedpage extends component
{
    private $opt_starttime=0;
    private $opt_endtime=0;
    private $_customfields;
    public function BufferStart( $manualflush = false )
    {
        $this->opt_starttime = microtime(true);
/*        if (!isset($manualflush) || $manualflush == false)
        {
          //ob_start();
          if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
          {
              ob_start("ob_gzhandler");

              }
          else
          {
              ob_start();
          }

        }
 * 
 */
    }

    public $CustomFields;

    public function BufferFlush()
    {
        $this->opt_endtime = microtime(true);

        ob_flush();
        // How much time to generate the page?
        return ($this->opt_endtime - $this->opt_starttime);

    }

    public function GetParameter($paramName, $type="string",$default="")
    {
        $val=$default;
        if(isset($_POST[$paramName]))
        {
            $val = $_POST[$paramName];
        }
        elseif(isset($_REQUEST[$paramName]))
        {
            $val = $_REQUEST[$paramName];
        }
        elseif(isset($_GET[$paramName]))
        {
            $val = $_GET[$paramName];
        }

        switch($type)
        {
            case "csvi":
                $theArray = explode(",",$val);
                $resArray = NULL;
                foreach($theArray as $a)
                {
                    $resArray[] =Sanitise::Long($a);
                }
                $val = implode(",",$resArray); // Now we've sanitised the array...
                break;
            case "csv":
                $theArray = explode(",",$val);
                $resArray = NULL;
                foreach($theArray as $a)
                {
                    $resArray[] = Sanitise::String($a);
                }
                $val = implode(",",$resArray); // Now we've sanitised the array...
                break;
            case "long":
                $val = Sanitise::Long($val, $default);
                break;
            case "json":
                $val = str_replace("\\", "",  Sanitise::String($val, $default) ); // Need to find a way to sanitize the JSON without messing it  up.
                break;
            case "string":
                $val=Sanitise::String($val, $default);
                break;
            case "date":
                $val=Sanitise::Date($val, $default);
                break;
            case "datetime":
                $val=Sanitise::DateTime($val, $default);
                break;
            case "binary":
                if($val==1 || $val===true || $val=='on' || $val=="TRUE" || $val=="T" || $val=="true")
                {
                    $val= 1;
                }
                else
                {
                    $val= 0;
                }
                break;
            case "double":
            case "float":
                $val=Sanitise::Float($val, $default);
                break;
            default:
                $val=Sanitise::String($val, $default);
        }

        return $val;
    }

    public function __construct( )
    {
    }
}
?>