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

        text-shadow: 0 1px 0 #ffffff;
        vertical-align: middle;
        width: 93%;
    }
    tr td{
        text-align: left;
    }
    select{
        width:95%;
    }
    .center{width: 20px;}
</style>
<?php
if (isset($_GET['isid'])) {
    $ticketdata = getissue($_GET['isid']);
    $gettickettype = getalltickettype();
    $getpriority = getallpriority();

    if (isset($ticketdata)) {
        foreach ($ticketdata as $thisissue) {
            $title1 = $thisissue["title"];
            $desc = $thisissue["description"];
            $ticket_type = $thisissue["ticket_type"];
            $ticketid = $thisissue["ticketid"];
            $priority = $thisissue["priority"];
            $send_mail_to = $thisissue["send_mail_to"];
        }
    }
    ?>
    <?php //include 'panels/editissue.php';  ?>


    <form class="form-horizontal well"  style="width:70%;margin: auto;" action="route.php" method="POST" id="modifysupport" name="modifysupport"  enctype="multipart/form-data">
        <div class="control-group">
            <table style="margin: auto;">
                <tr>
                    <td colspan="2">
                        <span id="tickettitle" style="display:none; color:red; font-size: 12px;">Please enter Title.</span>   
                        <span id="notescomp" style="display:none; color:red; font-size: 12px;">Please enter a Description.</span>   
                        <span id="noteerror" style="display:none; color:red; font-size: 12px;">Please enter a Note.</span>   
                        <span id="issuetype" style="display:none; color:red; font-size: 12px;">Please select issue type.</span>   

                        <span id="oprdate" style="display:none; color:red; font-size: 12px;">Please select date.</span>   
                        <span id="oprdatevalidate" style="display:none; color:red; font-size: 12px;">Please select Different date.</span> 
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <span class="add-on">Title</span>
                    </td>
                    <td class="center"></td>
                    <td>
                        <input type="text" name="issuetitle" id="issuetitle" size="32" value="<?php echo $title1; ?>"/>
                        <input type="hidden" id="supportid" name="supportid" value="<?php echo($ticketid); ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="add-on">Issue</span></td>
                    <td class="center"></td>
                    <td>
                        <select name="tickettype" id="tickettype" onchange="jQuery('#typeName').val(this.options[this.selectedIndex].innerHTML);">
                            <option value="0">Select Type</option>
                            <?php
                            if (isset($gettickettype)) {
                                foreach ($gettickettype as $row) {
                                    ?>
                                    <option value="<?php echo $row['typeid']; ?>" <?php
                                    if ($row['typeid'] == $ticket_type) {
                                        echo "selected";
                                    }
                                    ?>><?php echo $row['tickettype']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                        </select><input type="hidden" id="typeName" name="typeName" /></td>
                </tr>
                <tr>
                    <td>
                        <span class="add-on">Description <span class="mandatory">*</span></span></td>
                    <td class="center"></td>
                    <td><textarea cols="30" rows="3" name="notes_support" id="notes_support" maxlength="200" autofocus><?php echo $desc; ?></textarea></td>

                </tr> 

                <tr>
                    <td> <span class="add-on">Priority<span class="mandatory">*</span></span></td>
                    <td class="center"></td>
                    <td><select name="priority" id="priority">
                            <?php
                            if (isset($getpriority)) {
                                foreach ($getpriority as $row) {
                                    ?>
                                    <option value="<?php echo $row['prid']; ?>" <?php
                                    if ($row['prid'] == $priority) {
                                        echo"selected";
                                    }
                                    ?>><?php echo $row['priority']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                        </select></td>
                </tr>
                <tr>
                    <td>
                        <span class="add-on" style="width: 100px;">Enter Mail Id</span>
                    </td>
                    <td class="center"></td>
                    <td>
                        <input type="text" name="ticketmailid" id="ticketmailid" value="" placeholder="Enter email id" onkeyup="getmailids()" >
                        <input type="button" style="background: #00a6b8;margin: 0px;width: 80px;float: right" class="g-button g-button-submit" onclick="insertMailId()" value="Add Mail Id" name="addMail">
                        

                        <?php
                         echo '<div id="listemailids">';
                        $send_mail_to = array_filter(explode(',', $send_mail_to));
                        if (!empty($send_mail_to) && is_array($send_mail_to)) {
                           
                            $mailids='';
                            foreach ($send_mail_to AS $emailid) {
                                $mail=getmailid($emailid);
                                $mailids.=$mail.',';
                                echo '<br>';
                                echo '<div id="em_vehicle_div_' . $emailid . '" class="recipientbox">';
                                echo '<span>' . $mail . '</span>';
                                $mail = "'".$mail."'";
                                echo '<input class="v_list_element" name="em_vehicles_'.$emailid.'" value="'.$emailid.'" type="hidden"></input>';
                                echo '<img class="clickimage" src="../../images/boxdelete.png" onclick="removeEmailDiv('. $mail . ','.$emailid.');"></img>';
                                echo '</div>';
                            }
                            
                        }
                        echo '</div>';
                        ?>
                        <input type='hidden' name='sentoEmail' id="sentoEmail" value="<?php 
                       echo (isset($mailids) ? $mailids : ' '); 
                            ?>"/>
                    </td>
                </tr>
                <tr><td>
                        <span class="add-on">Attachment </span></td>
                    <td class="center"></td>
                    <td><input type="file" name="file_upload" id="file_upload" /></td>
                </tr>
                <?php
                if (file_exists("../../customer/" . $_SESSION["customerno"] . '/support/' . $ticketid . "_support.pdf") || file_exists("../../customer/" . $_SESSION["customerno"] . '/support/' . $ticketid . "_support.png") || file_exists("../../customer/" . $_SESSION["customerno"] . '/support/' . $ticketid . "_support.jpg") || file_exists("../../customer/" . $_SESSION["customerno"] . '/support/' . $ticketid . "_support.jpeg")) {
                    ?>
                    <tr>
                        <td><span class="add-on">Attached File </span></td>
                        <td class="center"></td>
                        <td><a href="download.php?download_file=<?php echo($ticketid); ?>_support.pdf&customerno=<?php echo $_SESSION['customerno']; ?>">Download</a>
                        </td>
                    </tr>                           
                  <?php }
                if (isset($_GET['id']) && $_GET['id'] == 3) {
                    ?>
                    <tr><td></td><td class="center"></td><td>
                            <input type="submit" class="btn btn-primary" style="background:  #00a6b8;" name="modify" value="Edit Ticket" onclick="return editsupport();"></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </form>
<?php } ?>

<script>

<?php if ($_GET['id'] == '4') { ?>
        var form = document.getElementById("modifysupport");
        var elements = form.elements;
        for (var i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = true;
        }
<?php } ?>
</script>