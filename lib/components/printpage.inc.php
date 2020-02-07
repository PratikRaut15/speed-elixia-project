<?php

require_once("../../lib/components/parameterisedpage.inc.php");

if(!defined('FPDF_FONTPATH'))
{
    define('FPDF_FONTPATH',INCLUDE_ROOT . "/pdf/font/");
}
require(INCLUDE_ROOT . "/pdf/code39barcode.php");

require_once("../../lib/system/pdfgenerator.php");

class printpage extends parameterisedpage
{
    private $_companyid;
    private $_clientid;
    private $_template;
    private $_data;
    private $_datacallback;
    private $_groupby;
    private $_reporttitle;
    private $_name;
    private $_value;

    public function __construct( &$user, $template, $reporttitle = "" )
    {
        parent::__construct( $user );
        // load the template
        $this->_clientid = GetClientId();
        $this->_companyid = $this->_user->companyid;
        $this->_reporttitle = $reporttitle;
if($this->_clientid>0)
{
    $this->_template = INCLUDE_ROOT .  "/customer/" . $this->_companyid . "/client/" . $this->_clientid . "/templates/" . $template;
}
else
{
    $this->_template = INCLUDE_ROOT .  "/customer/" . $this->_companyid . "/templates/" . $template;
}
        
        if (!file_exists($this->_template))
        {
            $this->_template =  INCLUDE_ROOT .  "/customer/" . $this->_companyid . "/templates/" . $template;
            if (!file_exists($this->_template))
            {
                $this->_template =  INCLUDE_ROOT .  "/templates/" . $template;
            }
        }
        
    }

    public function SetData( $data, $datacallback= "", $groupby="")
    {
        $this->_data = $data;
        $this->_datacallback = $datacallback;
        $this->_groupby = $groupby;
    }

    function SetTitle( $NewTitle )
    {
        $this->_reporttitle = $NewTitle;
    }

    function AddVariable( $name, $value )
    {
        $this->_name = $name;
        $this->_value = $value;
    }

    function Render()
    {
        //Print the page       
        $generator = new pdfgenerator( $this->_template,  $this->_data, $this->_datacallback, $this->_groupby);
        $generator->SetReportTitle($this->_reporttitle);
        $generator->AddVariable($this->_name, $this->_value);
        $generator->render();
    }
}
?>
