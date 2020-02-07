<style>

    .add-on {
        background-color: #eeeeee;
        border: 1px solid #ccc;
        display: inline-block;
        font-weight: normal;
        height: 18px;
        line-height: 18px;
        min-width: 16px;
        padding: 4px 5px;
        text-align: center;
        text-shadow: 0 1px 0 #ffffff;
        vertical-align: middle;
        width: auto;
    }
</style>

<?php
$user = getUser($_SESSION['customerno'], $_SESSION['userid']);

$gettickettype = getalltickettype();
$getpriority = getallpriority();
?>
<form class="form-horizontal well "  style="width:70%;" name="createsupport" id="createsupport" action="route.php" method="POST" enctype="multipart/form-data">

<?php //include 'panels/addissue.php';  ?>   

    <span id="tickettitle" style="display:none; color:red; font-size: 12px;">Please enter Title.</span>   
    <span id="notescomp" style="display:none; color:red; font-size: 12px;">Please enter a Description.</span>   
    <span id="issuetype" style="display:none; color:red; font-size: 12px;">Please select issue type.</span>   
    <span id="file_error" style="display:none; color:red; font-size: 12px;">Please select zip file.</span>   

    <span id="oprdate" style="display:none; color:red; font-size: 12px;">Please select date.</span>   
    <span id="oprdatevalidate" style="display:none; color:red; font-size: 12px;">Please select Different date.</span>   

    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Title</span>
                <input type="text" name="issuetitle" id="issuetitle"/>
                <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>" />
            </div>                            
            <br/><br/> 

            <div class="input-prepend ">
                <span class="add-on">Issue</span>
                <select name="tickettype" id="tickettype">
                    <option value="0">Select Type</option>
<?php
if (isset($gettickettype)) {
    foreach ($gettickettype as $row) {
        ?>
                            <option value="<?php echo $row['typeid']; ?>"><?php echo $row['tickettype']; ?></option>
                            <?php
                        }
                    }
                    ?>


                </select>
            </div>

            <div style="clear: both;"></div>
            <br>
            <div class="input-prepend input-prepend ">
                <span class="add-on">Description <span class="mandatory">*</span></span>&nbsp;<textarea cols="45" rows="3" name="notes_support" id="notes_support" placeholder="Enter Notes here" maxlength="200" autofocus></textarea>
            </div>
            <br/><br/>

            <div class="input-prepend ">
                <span class="add-on">Priority<span class="mandatory">*</span></span>
                <select name="priority" id="priority">
                    <option value="0">Select Priority</option>
<?php
if (isset($getpriority)) {
    foreach ($getpriority as $row) {
        ?>
                            <option value="<?php echo $row['prid']; ?>" <?php if ($row['prid'] == '1') {
                        echo"selected";
                    } ?>><?php echo $row['priority']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <br/><br/>

            <div class="input-prepend ">
                <span class="add-on">Enter Mail Id</span>
                <input type="text" name="ticketmailid" id="ticketmailid" value="" placeholder="Enter email id" onkeyup="getmailids()" >
                <input type="button" style="float:right;margin-right: 40px;" class="g-button g-button-submit" onclick="insertMailId()" value="Add Mail Id" name="addMail">
                <input type='hidden' name='sentoEmail' id="sentoEmail"/>

            </div>
            <br><br>
            <div class="input-prepend ">
                <div id="listemailids" ></div>
            </div>
            <br><br>
            <div class="input-prepend ">
                <span class="add-on">Attachment </span>&nbsp;<input type="file" name="file_upload" id="file_upload" />
            </div>

        </div>
    </fieldset>

    <fieldset>
        <div class="control-group pull-right">
            <input type="submit" name="send_request" class="btn btn-primary" value="Add Ticket" onclick="return addsupport();">
        </div>      
    </fieldset>
</form>
