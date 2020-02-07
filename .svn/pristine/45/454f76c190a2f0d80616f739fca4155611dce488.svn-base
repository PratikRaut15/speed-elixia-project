<?php
$sales = getsalesforcustomer($_SESSION['customerno'],$_SESSION['userid']);
if(isset($sales))
{
    echo("<ul class='devicelist'>");
    foreach( $sales as $thissale )
    {
        $selecteddevice = getdevicefromsales($_SESSION['customerno'],$_SESSION['userid'],$thissale->salesid);
        echo("<li id='t_" . $thissale->salesid . "' class='device " . (isset($selecteddevice) && $selecteddevice->salesid>0?"assigned":"") . "' onclick='st(" . $thissale->salesid . ")'>" );
        echo( $thissale->srcode." ( ".$thissale->phone." )");
        echo('<input type="hidden" id="tl_' . $thissale->salesid . '" value="' . $thissale->salesid . '" /></li>');
    }
    echo("</ul>");
}
?>