<?php
class toolbaricon
{
    public $icon;
    public $linkurl;
    public $caption;
    public $onclick;
    public $isseperator;
    public $id;

    function toolbaricon( $iconurl,$isseperator, $hrefurl, $caption, $onclick, $iconid="" )
    {
        $this->icon = $iconurl;
        $this->linkurl = $hrefurl;
        $this->caption = $caption;
        $this->onclick = $onclick;
        $this->isseperator = $isseperator;
        $this->id = $iconid;
    }

    function generatehtml( $index )
    {
        if($this->isseperator)
        {
            echo("<div class='toolbarseperator'  >");
            echo("<div class='toolbarseperatoricon' ><img  src='" . $this->icon . "' /></div>");
            echo("</div>");
        }
        else
        {
            $URL = $this->linkurl!=""?$this->linkurl:"#";
            $EVENT = $this->onclick;

if($this->id=="")
{
    $id="";
}
else
{
    $id=" id='" . $this->id . "' ";
}

            echo("<div class='toolbarbutton' $id >");
            echo("<div class='toolbarbuttonicon' ><a href='$URL' onclick='$EVENT'><img alt='" . $this->caption . "' title='" . $this->caption . "' src='" . $this->icon . "' /></a></div>");
            echo("<div class='toolbarbuttoncaption' >" . $this->caption . "</div>");
            echo("</div>");
        }
    }
}
?>
