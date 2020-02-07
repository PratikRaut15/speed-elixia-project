<?php
/**
 * View service required
 */
$customerno = exit_issetor($_SESSION['customerno']);
$userid = exit_issetor($_SESSION['userid']);
$mm_2 = new Mobility($customerno, $userid);
$categories = $mm_2->getcatdata();
?>
<style>
.bootstrap-timepicker td a{margin: -8px !important;padding:0 !important}
.bootstrap-timepicker td, .bootstrap-timepicker th{height: 15px !important;}
</style>
<div class="row">
    <div class="col-xs-12">Service Required</div>
</div>
<div class="row">
    <div class="col-xs-8">
        <!--<input type="text" id="serviceauto" placeholder="Service name" style='width:100%;'/>-->
        <select id="category" style='width:100%;' >
            <option value=''>-Select category-</option>
            <?php
            if($categories){
                foreach($categories as $sc){
                    echo "<option value='{$sc['id']}'>{$sc['value']}</option>";
                }
            }
            ?>
        </select>
        <select id="serviceauto" style='width:100%;'>
            <option value='' >-Select services-</option>
        </select>
    </div>
<!--    <div class="col-xs-8">
        <input type="text" id="serviceauto" placeholder="Service name" style='width:100%;'/>
        <select id="serviceauto" style='width:100%;'>
            <option value=''>-Select services-</option>
        </select>
    </div>-->
    <div class="col-xs-4">
        <input type='hidden' id='EditcallID' />
        <select id='changeStatus'>
            <option style='background-color:#DDC6FF;'>--Status--</option>
            <?php
            foreach($status_colors as $index=>$val){
                echo "<option value='$index' style='background-color:".$val[1].";'>".$val[0]."</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-xs-4">
        <input type="text" placeholder="Discount code" id='discountcode' name='discountcode' style='width:100%;' value='' />
    </div>
    <!--<div class="col-xs-4">
            | <input type="button" data-toggle='modal' data-target='#adServiceModal' onclick ='fillServiceModal();' class="btn btn-primary" value="Add New">
    </div>-->
</div>
<!--<div class='row'>
    <div class="col-xs-12" >
        <table class="table" id='clientService'>
            
        </table>
    </div>
</div>-->
<div class="row">
    <div class="col-xs-12" >
        <table class="table" id='SESTT'>
            <tr>
                <td>
                    <input type='text' name='startdate' id='startdate' placeholder='Time'/>
                    <input type="date" data-date="08:00" class="input-mini" name="STime" id="STime">
                    <input type='text' id='trackieauto' placeholder='Therapist'/>
                    <input type='hidden' id='trackieid' />
                </td>
            </tr>
        </table>
    </div>
</div>