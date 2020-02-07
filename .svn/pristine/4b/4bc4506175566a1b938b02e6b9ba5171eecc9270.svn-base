<?php
/**
 * Timeline interface
 */

$today = date('d-m-Y');
$total_columns = ($max_hour-$start_hour)+1;
$cell_width = 100/$total_columns;
?>
<br/>
<div class='container-fluid' >
    
    <div class='row'>
        <div class='col-xs-12' style='text-align:center;'>
            <a href='javascript:void(0);' onclick='date_change("decre");'>&Lt;</a>
             <input type='date' name='datetimeline' value='<?php echo $today;?>'/> 
            <a href='javascript:void(0);' onclick='date_change("incre");'>&Gt;</a>
        </div>
    </div>
    
    <div class='row' style='margin-bottom:5px;'>
        <div class='col-xs-12' >
            <?php
            foreach($status_colors as $stat=>$color_d){
                echo "<span class='Statlabel' style='background-color: {$color_d[1]};'>{$color_d[0]}</span>&nbsp;";
            }
            ?>
        </div>
    </div>
    
    <div class="table-responsive" style="min-height:450px;">
        <table class='table timelineTble table-hover' >
            <thead>
                <tr>
                    <th class='col-xs-1'>
                        <input type='text' id='tmlTrackInp' placeholder='Therapist' style='width:100px;padding:1px;margin-bottom:0px'/>
                    </th>
                    <?php
                    $sh = $start_hour;
                    echo "<th colspan='$total_columns'>";
                    while(true){
                        echo "<div style='width:$cell_width%;float:left;text-align:left;'>$sh</div>";
                        if($sh==$max_hour){break;}
                        $sh++;
                    }
                    echo "</th>";
                    ?>
                </tr>
            </thead>
            <tbody id='timelineBody' oncontextmenu="return false;">
                <?php
                require_once 'pages/timeline/timeline_body.php';
                ?>
            </tbody>
        </table>
    </div>
    
    <div class="row timeline_footer" style='width:100%;text-align:center;'>
    <div class="col-xs-12">
        <div class='row' id='newCallForm'  style='display:none;'>
        <div class="col-xs-12">
            <div class="row" >
                <div class="col-xs-4"></div>
                <div class="col-xs-4"><a href='javascript:void(0);' class="chlinkcolor"onclick="hideNewCall();" >Hide</a></div>
                <div class="col-xs-4" style='text-align: right;'>
                    <a href='javascript:void(0);' class="chlinkcolor" onclick="editCall();" >Edit</a> | 
                    <a href='javascript:void(0);' class="chlinkcolor" onclick="addNewCall();" >Add</a> | 
                    <a href='javascript:void(0);' class="chlinkcolor" onclick="resetCall();" >Clear</a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12" style='text-align:center;' id='newCallStatus'></div>
            </div>
            <div class='row' style='min-height:200px;background:#D3D079; border-top: 1px solid; font-family: Verdana; font-weight:lighter;' id='mainCallDv'>
                <div class="col-xs-2" ><?php require_once 'pages/timeline/client_details.php';?></div>
                <div class="col-xs-2" ><?php require_once 'pages/timeline/service_history.php';?></div>
                <div class="col-xs-4" ><?php require_once 'pages/timeline/service_required.php';?></div>
                <div class="col-xs-4" ><?php require_once 'pages/timeline/service_details.php';?></div>
            </div>
        </div>
        </div>
        <div class='row'>
        <div class="col-xs-12 timeline_footer_contents" id='newCallIcon' style='height: 20px;background:#FF9900;' >
            <a href="javascript:void(0);" class="chlinkcolor" onclick='showNewCall();'>New-call</a>
        </div>
        </div>
    </div>
    </div>
    
</div>

<!-- New service modal starts--
<div style='min-height: 200px; max-height: 400px; width:600px;' aria-hidden="true" aria-labelledby="exampleModalLabel" role="dialog" tabindex="-1" id="adServiceModal" class="modal fade in">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only"></span></button>
        <br/>
    </div>
      
    <div class="modal-body" >
        <form  id="svlform" method="POST"  action="mobility.php?pg=view-service" onsubmit="addService();return false;">
        <table class='table table-condensed' style='width:100%;'>
            <thead><tr><th colspan="100%" >Add Service</th></tr></thead>
            <tbody>
                <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                <tr><td class='frmlblTd'>Service Name <span class="mandatory">*</span></td><td><input type="text" name="servicename" required></td></tr>
                <tr><td class='frmlblTd'>Cost <span class="mandatory">*</span></td><td><input type="number" name="cost" step="0.01" required></td></tr>
                <tr><td class='frmlblTd'>Expected time <span class="mandatory">*</span></td><td><input type="number" name="expTime" required><br/>(Numerical value In minutes)</td></tr>
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Add" class='btn btn-primary'></td></tr>
            </tbody>
        </table>
        </form>
      </div>
    <div class="modal-footer">
        <button id="popClose" data-dismiss="modal" class="btn btn-default" type="button">Close</button>
    </div>
</div>
<!-- New service modal ends -->


<!--change status pop starts----->
<div id='statusChngeBuble' class="bubble row" oncontextmenu="return false;">
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <h3 style='text-align:center;'>Appointment</h3>
                <div id='ajaxBstatus'></div>
                <table class="table">
                    <tbody>
                        <!--<input type='hidden' id='clientID' />-->
                        <tr><td>Client:</td><td id='clientVal'></td></tr>
                        <tr><td>Date:</td><td id='whenVal'></td></tr>
                        <tr><td>Mobile:</td><td id='mobile'></td></tr>
                        <tr><td>Address:</td><td id='whereVal'></td></tr>
                        <tr><td>Details:</td><td id='whatVal'></td></tr>
<!--                        <tr><td>Status:</td><td id='statusVal'></td></tr>-->
<!--                        <tr>
                            <td>Change:</td>
                            <td>
                                <select id='changeStatus'>
                                    <option style='background-color:#DDC6FF;'>--Status--</option>
                                    <?php
                                    foreach($status_colors as $index=>$val){
                                        echo "<option value='$index' style='background-color:".$val[1].";'>".$val[0]."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>-->
                    </tbody>
                </table>
            </div>
        </div>
        <div class='row'>
            <input type="hidden" id="clid">
            <div class='col-xs-6'><input type="submit" class="btn" value="Delete" onclick='deleteSC();'/></div>
            <div class='col-xs-6' style='text-align:right;'><input type="submit" class="btn btn-primary bubbleclose" value="Close" /></div>
            
        </div><br/>
    </div>
</div>
<!--change status pop ends-->

<script>
var allstats = <?php echo json_encode($status_colors); ?>
</script>