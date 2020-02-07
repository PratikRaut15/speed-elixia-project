<?php

//require_once("class/config.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>master screen</title>
<link rel="stylesheet" href="css/tabs.css" />

<script  type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script  type="text/javascript" src="js/jquery-ui2.js"></script>

<link rel="stylesheet" type="text/css" href="css/masterscreen.css"/>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqInDK10QshlbDp_KOJbG0EGh-Q17AMCQ&sensor=true"></script>


<script type="text/javascript" src="js/masterscreen.js"></script> 

<script    type="text/javascript" type="text/javascript"  src="js/prototype.js"></script>
<script   type="text/javascript"type="text/javascript"  src="js/markerwithlabel.js"></script>

<script type="text/javascript" src="js/infobox.js"></script>

</head>


<body  style="margin:0 auto;">

<div id="block-1" style="display:none; ">
<div  id="trackee" >
<div id="trackee_header"></div>
<div id="remicon"></div>
<div id="tabs">
<ul>
<li><a href="#tabs-1">Trackee Log</a></li>
<li><a href="#tabs-2">Client History</a></li>
<li><a href="#tabs-3">Info</a></li>

</ul>
<div id="tabs-1">
</div>
<div id="tabs-2">
<p>coming soon</p>
</div>
<div id="tabs-3">
<p>coming soon</p>
</div>
</div>




<div id="actions">
<a href="" target="_blank" class="blue">edit</a><a target="_blank" class="grey" href="">view</a>
<div style=" clear:both;"></div>
</div>
</div>


<div id="flower" style="-moz-box-shadow: 10px 10px 5px #888;
-webkit-box-shadow: 10px 10px 5px #888;
box-shadow: 10px 10px 5px #888;">
<div id="gc-topnav">
<div id="headermenu">
<a href="" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; margin-left:-5px; float:left;">master screen</a>
&nbsp;&nbsp;&nbsp;
<!--<ul>
<li><a href="">total calls</a></li>
<li><a href="" >incomplete</a></li>
<li><a href="">updated</a></li>
<li><a href="">More</a></li>
</ul>-->
<div  id="online" style="float:right"><a href="" >online</a></div>



</div>
</div>

</div>
    <div style="background: none repeat scroll 0 0 #E8E8E8;
    height: 40px;
    width: 100%; border-bottom: 1px solid #ccc;"></div>
<div id="sidebar"  style=" width:16.6%;height:auto; float:left; ">

<div id="search">
<input type="text" class="search-query" id="searchbox"  placeholder="Search..." />

</div>
<div id="data"></div>
<div class="list-view" style="height:500px;">

</div>

<!-- <div class="notifications" style="height:500px; position:absolute; width:17.59%; top:400px; left:0px; overflow:scroll; text-transform:capitalize; color:#666666;">
&nbsp; &nbsp; &nbsp;Notification
<ul id="notifications2" >

</ul>
</div>-->
</div>



<div id="maptoggler" onclick="onclicktog();">
<img id="next" style="display:none;" src="images/br_next.png">
<img id="pre" style="display: block;" src="images/br_prev.png">
</div>
<div id="mapcontainer" style="background-color:#CCCCCC;  float:left; width:1100px; height:900px;">

</div>

</div>
<!--block 2 starts here 
-->

<div id="block-2">

<center>
<font  color="#0383C0" size="+4">

loading
</font>

<div style="background:url(./images/15.gif); width:200px; background-repeat:repeat-x; height:10px; border:1px solid #3FBEFC; ">


</div>
</center>
</div>
<div id="dialog" title="Warning">
    <div style="padding: 5px;">
    <ul id="panic_ul"></ul>
    <input type="button" value="close" id="closewarning" class="ui-button"  style="display: block;">
    </div>
    
</div>
</body>
</html>