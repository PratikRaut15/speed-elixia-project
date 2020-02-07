<?php
include_once("session/sessionhelper.php");
include_once("db.php");
include_once("constants/constants.php");
include_once("lib/components/gui/objectdatagrid.php");
include_once("lib/bo/TrackeeManager.php");
include_once("lib/bo/ItemManager.php");
include_once("getcustomfields.php");


$customerno = GetCustomerno();
$tm = new TrackeeManager($customerno);
$trackees = $tm->gettrackeesforcustomer();

if(isset($_POST["Find"]))
{
    $trackeeid = GetSafeValueString($_POST["trackeeid"], "string");
    $trackee = $tm->get_trackee($trackeeid);
    $fromdate = GetSafeValueString($_POST["fromdate"], "string"); 
    $todate = GetSafeValueString($_POST["todate"], "string");             
    $fromtime = GetSafeValueString($_POST["fromtime"], "string"); 
    $totime = GetSafeValueString($_POST["totime"], "string");                 
}    
$filtcheckpoints = $tm->getcheckpointsCM($trackeeid);

$dg = new objectdatagrid( $filtcheckpoints );
$dg->AddColumn("Name", "cname");
$dg->AddColumn("Address", "cadd1");
$dg->AddColumn("", "cadd2");
$dg->AddColumn("", "cadd3");
$dg->AddColumn("City", "ccity");
$dg->AddColumn("State", "cstate");
$dg->AddIdColumn("checkpointid");
$dg->SetNoDataMessage("No " . $cust_Checkpoint .  "s assigned");

$im = new ItemManager($customerno);
$items = $im->get_items_for_trackee($trackeeid);

$dg1 = new objectdatagrid( $items );
$dg1->AddColumn($cust_Item ." Name", "itemname");
$dg1->AddColumn($cust_Trackee, "tname");
$dg1->AddColumn("Recipient", "recipientname");
$dg1->AddColumn("Status", "isdeliveredstring");
$dg1->AddColumn("Created At", "createdon");
$dg1->AddColumn("Delivered At", "deliverydate");
$dg1->AddColumn("Select", "group","","grp","checkbox");
$dg1->AddRightAction("Print", $rootpath."images/barcode.png", $rootpath.'pdfitemlabels.php?id=%d');
$dg1->AddCustomFooter( "Select All<input type='checkbox' name='checkall' id='checkall' onclick=\"SetAllCheckBoxes('myform');\"><input type=\"submit\" name=\"groupprint\" value=\"Group Print\">");
$dg1->AddIdColumn("itemno");
$dg1->SetNoDataMessage("No " . $cust_Item . "s assigned");
?>

<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<script>
function SetAllCheckBoxes(doc)
{
    var c = document.getElementsByTagName('input');
    var d = $("checkall").checked;
    if(d==0)
    {
        for (var i = 0; i < c.length; i++)
        {
            if (c[i].type == 'checkbox')
                c[i].checked = 0;
        }
    }
    if(d==1)
    {
        for (var i = 0; i < c.length; i++)
        {
            if (c[i].type == 'checkbox')
                c[i].checked = 1;
        }
    }
}
</script>

<div class="panelnotop">    
    <div class="paneltitle">Filter <?php echo($cust_Trackee); ?></div>
    <div class="panelcontents">
          <form action="reports.php?rid=2" method="post" name="form1" id="form1" enctype="multipart/form-data">
                <table>
                  <tr>
                    <td><?php echo($cust_Trackee); ?> Name:*</td>
                    <td><select id="trackeeid" name="trackeeid">
                    <?php
                    if(isset($trackee)) 
                    {
                    ?>
                        <option value="<?php echo($trackee->id); ?>"><?php echo($trackee->tname); ?></option>                            
                    <?php
                    }
                    else
                    {
                    ?>
                        <option value="-1"><?php echo("Select ".$cust_Trackee); ?></option>                            

                    <?php
                    }
                    if(isset($trackees))
                    {
                        foreach($trackees as $thistrackee)
                        {
                            if($thistrackee->trackeeid != $trackee->id)
                            {
                            ?>
                                <option value="<?php echo($thistrackee->trackeeid); ?>"><?php echo($thistrackee->tname); ?></option>
                            <?php
                            }
                        }
                    }
                    ?>
                    </select>
                        <span class="mandatory">*</span>
                        <span id="mantrackee" class="mandatory" style="display:none;">Please select a Trackee.</span>                        
                      </td>                        
                  </tr>
                <tr>
                    <td>Date</td>
                    <?php
                        $today = date("Y-m-d H:i:s");        
                    ?> 
                    <td>From
                        <input name="fromdate" type="text" id="fromdate" value="<?php if(isset($fromdate)) echo($fromdate); else echo date("d-m-Y",$today); ?>" size="10" maxlength="10" /><button id="trigger">...</button>
                        <select id="fromtime" name="fromtime">
                            <?php
                            if(isset($fromtime))
                            {
                            ?>
                                <option value="<?php echo($fromtime); ?>"><?php echo($fromtime); ?></option>                                                            
                        <?php
                                for($i = 0; $i < 24; $i++)
                                {
                                    if($i<10) $i="0".$i;
                                    if($i != $fromtime)
                                    {
                                        ?>
                                        <option value="<?php echo($i); ?>"><?php echo($i); ?></option>
                                        <?php
                                    }
                                }                                
                            }
                            else
                            {
                        ?>
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>                        
                        <?php
                        }
                        ?>
                        </select> hours
                        <br/>
                        To &nbsp;&nbsp;&nbsp;&nbsp; <input name="todate" type="text" id="todate" value="<?php if(isset($todate)) echo($todate); else echo date("d-m-Y",$today); ?>" size="10" maxlength="10" /><button id="triggerto">...</button>
                        <select id="totime" name="totime">
                            <?php
                            if(isset($totime))
                            {
                            ?>
                                <option value="<?php echo($totime); ?>"><?php echo($totime); ?></option>                                                            
                        <?php
                                for($i = 0; $i < 24; $i++)
                                {
                                    if($i<10) $i="0".$i;
                                    if($i != $totime)
                                    {
                                        ?>
                                        <option value="<?php echo($i); ?>"><?php echo($i); ?></option>
                                        <?php
                                    }
                                }                                
                            }
                            else
                            {
                        ?>
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>                        
                        <?php
                        }
                        ?>
                        </select> hours                   
                    </td>                    
                </tr>
<script>
Calendar.setup(
{
    inputField : "fromdate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger" // ID of the button
});                

Calendar.setup(
{
    inputField : "todate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "triggerto" // ID of the button
});                
</script>             
                  <tr>
                      <td></td>
                      <td><input type="submit" name="Find" id="Find" value="Get Report"></td>
                  </tr>

                </table>
        </form>      
    </div>
</div>

<script>
var eviction_list = [];
var gmapsinited=false;

function initialize() {
var latlng = new google.maps.LatLng(19.07, 72.89);
var myOptions = {
  zoom: 11,
  center: latlng,
 draggableCursor: 'crosshair',
  mapTypeId: google.maps.MapTypeId.ROADMAP
};
map = new google.maps.Map(document.getElementById("mapcontainer"),
    myOptions);
}

function initmap( lat,lng )
{
    if(gmapsinited)return;
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: 15,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
  };
    map = new google.maps.Map($("mapcontainer"),
        myOptions);
    gmapsinited=true;
}

function pullinfo()
{
    var trackeeid = $("trackeeid").value;
    if(trackeeid != -1)
    {
        var fromdate = $("fromdate").value;
        var todate = $("todate").value;
        var fromtime = $("fromtime").value;
        var totime = $("totime").value;            
        var params = "fromdate=" + encodeURIComponent( fromdate ) +
                    "&todate=" + encodeURIComponent( todate ) +
                    "&fromtime=" + encodeURIComponent( fromtime ) +
                    "&totime=" + encodeURIComponent( totime ) +        
                    "&trackeeid=" + encodeURIComponent(trackeeid);            
        new Ajax.Request('getTrackeeinfoAjax.php',
        {
            parameters: params,                            
            onSuccess: function(transport)
            {
                var cdata = transport.responseText.evalJSON();
                plotCurrentTrackee(cdata);  
            },
            onComplete: function()
            {
            }
        });        
    }
    else
    {
        $("mantrackee").show();
        jQuery("#mantrackee").fadeOut(3000);                
    }
}

function plotCurrentTrackee( cdata )
{
    var results = cdata.result;
    results.each( function(device)
    {
        try
        {
            initmap(device.tgeolat, device.tgeolong);
            var iconfile = "thumb48t_" + device.ticonimage;

            if(device.latnext == 0 && device.longnext == 0)
            {
                var image = new google.maps.MarkerImage( 'images/m_lastknown.png' ,
                            new google.maps.Size(30,30),
                            new google.maps.Point(0,0),
                            new google.maps.Point(30,30),
                            new google.maps.Size(30,30) );                
            }
            else
            {
                if(device.latnext > device.tgeolat && device.longnext == device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_north.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }

                if(device.latnext < device.tgeolat && device.longnext == device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_south.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }            

                if(device.latnext == device.tgeolat && device.longnext < device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_west.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }            

                if(device.latnext == device.tgeolat && device.longnext > device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_east.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }                        

                if(device.latnext > device.tgeolat && device.longnext > device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_northeast.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }                        

                if(device.latnext < device.tgeolat && device.longnext > device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_southeast.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }                                    

                if(device.latnext < device.tgeolat && device.longnext < device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_southwest.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }                                    

                if(device.latnext > device.tgeolat && device.longnext < device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_northwest.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }                                                            

                if(device.latnext == device.tgeolat && device.longnext == device.tgeolong)
                {
                    var image = new google.maps.MarkerImage( 'images/m_stay.png' ,
                                new google.maps.Size(16,16),
                                new google.maps.Point(0,0),
                                new google.maps.Point(16,26),
                                new google.maps.Size(16,16) );
                }
            }

            var myLatLng = new google.maps.LatLng(device.tgeolat, device.tgeolong);
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: image
                });                
         map.setCenter(marker.getPosition());
          eviction_list.push(marker);               
        var contentString = '<div id="content">'+
            '<h6 id="firstHeading" class="firstHeading">Name: '+ device.tname +'</h6>'+
            '<div id="bodyContent">'+
            '<p>Last Updated: '+ device.lastupdated +'</p>'
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        google.maps.event.addListener(marker, 'mouseover', function() {
          infowindow.open(map,marker);
        });         

        google.maps.event.addListener(marker, 'mouseout', function() {
          infowindow.close();
        });                                             
    }
    catch( ex)
    {
        alert(ex);
    }
    });
}

function evictMarkers() {
// clear all markers
eviction_list.forEach(function(item) 
{ 
    item.setMap(null) 
});

// reset the eviction array 
eviction_list = [];
}

function loaded()
{
    evictMarkers();    
    initialize();
    if($("fromdate").value != "" || $("todate").value != "")
    {
        pullinfo();
    }    
}
        
Event.observe(window,'load', function() {loaded();});    
</script>
<div class="panel">
    <div class="panelcontents">
        <div id="mapholder" align="center">
            <br/>
            <div id="mapcontainer" style="width:940px; height:520px"></div>
            </div>
    </div>
</div>

<br/>
<div class="panel">
    <div class="paneltitle"><?php echo($cust_Item); ?>s Assigned</div>
    <div class="panelcontents">
    <form id="printform" name="form" method="post" action="pdfitemlabels.php" target="_blank">        
        <?php
        $dg1->Render();
        ?>
    </form>
    </div>
</div>

<br/>
<div class="panel">
    <div class="paneltitle"><?php echo($cust_Checkpoint); ?>s Assigned</div>
    <div class="panelcontents">
        <?php
        $dg->Render();
        ?>        
    </div>
</div>