<?php
/**
 * View Template interface
 */
echo "<script src='" . $_SESSION['subdir'] . "/scripts/tinymce/tinymce.min.js' type='text/javascript'></script>";
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno, $userid);
$reminderlist = $sales->getreminderdataselect();
$stagelist = $sales->getstagedataselect();
?>
<script type="text/javascript">
    tinymce.init({
        mode: "textareas",
        menubar: false,
        editor_selector: "mceEditor",
        editor_deselector: "mceNoEditor",
        elements: "emailtemplate",
        statusbar: false
    });



</script>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #viewtemplate_filter{display: none}
    .dataTables_length{display: none}
    .bubbleclosetemp{
        text-align: right;
        cursor:pointer;
    }
</style>
<br/>
<div class='container' >
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addtemplate();">Add New Template <img src="../../images/show.png"></button>
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewTemplate'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewtemplate">
            <thead>
                <tr>

                    
                    <td><input type="text" class='search_init' name='reminderid' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='StageId' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='recipienttype' autocomplete="off"/></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class='dtblTh'>
                    <th>Reminders </th>
                    <th>Stages</th>
                    <th>Recipient Type</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
        </table>
    </center>
</div>


<!--Add templates pop starts----->
<div id='addTemplateformBuble'  class="bubble row" style='position: absolute;' >
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <h4 style='text-align:center;'>Add Template</h4>
                <div id='ajaxBstatus'></div>
                <table  class="table showtable">
                    <tbody>
                        <tr>
                            <td class='frmlblTd'>Template Type</td>
                            <td>
                                <input type="radio" id="templatetype_reminder" name="templatetype" value='1'> Reminder
                                <input type="radio" id="templatetype_stage" name="templatetype" value='2'> Stages
                            </td>
                        </tr>

                        <tr id="reminder">
                            <td class='frmlblTd'>Reminder </td>
                            <td>
                                <select name="remid" id="remid">
                                    <option value="">Select</option>
                                    <?php
                                    if (isset($reminderlist)) {
                                        foreach ($reminderlist as $row) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['value'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr id="stage">
                            <td class='frmlblTd'>Stage </td>
                            <td>
                                <select name="stageid" id="stageid">
                                    <option value="">Select</option>
                                    <?php
                                    if (isset($stagelist)) {
                                        foreach ($stagelist as $row) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['value'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <td class="frmlblTd">Email Subject</td>
                            <td>
                                <textarea name="emailsubject"  id="emailsubject" style="resize: none;" cols="37"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="frmlblTd">Email Template</td>
                            <td>
                                <textarea name="emailtemplate" id="emailtemplate" class="mceEditor" cols="30" rows="4"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="frmlblTd">SMS Template</td>
                            <td>
                                <textarea name="smstemplate" cols="37" class="mceNoEditor" val="" id="smstemplate" style="resize: none;"></textarea>
                                <br>
                                <span id="remaining">160 characters remaining</span>
                                <span id="messages">1 message(s)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="frmlblTd">Recipient Type</td>
                            <td><input type="radio" name="rtype" value="1" checked> Client <input type="radio" name="rtype" value="2"> Self</td> 
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class='row'>
            <div class='col-xs-12' style='text-align:right;'>
                <input type="button" class="btn-info" name="preview" id="preview" style='float:left;' value="Preview Email Template"/>
                <input type="submit" class="btn btn-primary" value="Submit" id="addreminderdata" onclick="addtemplatedatapop();"/> 
                <input type="submit" class="btn  bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change template pop ends-->


<!--Add template pop starts----->
<div id='addtemplateBuble' class="bubble row" oncontextmenu="return false;">

    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclosetemp" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <div id="emailsubjectpreview" style="font-style:normal; font-family:sans-serif; font-size:11px; border: 1px solid #EFEFEF; line-height: 20px; margin: 6px; word-wrap:normal; "></div>
                <div style="clear: both;"></div>
                <div id="emailcontent" style="border: 1px solid #EFEFEF; padding:5px; margin:5px; word-wrap:normal;">
                </div>

            </div>
        </div>
        <div class='row'>
            <div class='col-xs-12' style='text-align:right;'>
                <input type="submit" class="btn  bubbleclosetemp" value="Close" /></div>
        </div><br/>

    </div>
</div>
<!--change status pop ends-->