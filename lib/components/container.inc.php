<?php
require_once ("../../lib/components/component.inc.php");
class container  extends component{
    //put your code here
    public $CSSClass = "";
    public $Type="div";
    public $ElementId = "";

    public function __construct($ElementId, $Type="div", $CSSClass="", $CSSFile="")
    {
        parent::__construct();
        $this->CSSClass=$CSSClass;
        $this->Type = $Type;
        $this->ElementId = $ElementId;
        if($CSSFile!="")
        {
            $this->RegisterStyleSheet($CSSFile);
        }
    }

    

    public function RenderComponents()
    {
        if(isset($this->_components))
        {
            foreach($this->_components as $thiscomponent)
            {
                $thiscomponent->Render();
            }
        }
    }

    public function Render()
    {
        if($this->CSSClass !="" )
        {
            echo(sprintf("<%s id='%s' class='%s'>",$this->Type,$this->ElementId,$this->CSSClass));
        }
        else
        {
            echo(sprintf("<%s id='%s'>",$this->Type,$this->ElementId));
        }
        $this->RenderComponents();
        parent::Render();
        echo(sprintf("</%s>",$this->Type));
    }


}
?>