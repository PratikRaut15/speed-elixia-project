<?php
include '../panels/header.php';
include 'support_functions.php';
if (isset($_GET['id']) && $_GET['id'] == '3') {
    include 'pages/editissue.php';
} elseif (isset($_GET['id']) && $_GET['id'] == '4') {
    include 'pages/editissue.php';
} else {
    ?>
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
            float: left;
            text-align: center;
            text-shadow: 0 1px 0 #ffffff;
            vertical-align: middle;
            width: 80%;
        }
        .control-group{
            margin-left: auto;
            margin-right: auto;
            width:90%;
        }
        #file_upload{
            cursor: pointer;
        }
        .input-prepend{
            padding: 6px;
        }
    </style>
    <?php
$user = getUser($_SESSION['customerno'], $_SESSION['userid']);
    $gettickettype = getalltickettype();
    $getpriority = getallpriority();
    ?>
    <div id="container-fluid" style="margin-top:20px">
    <form class="form-horizontal well "  style="width:95%;margin-left: auto;margin-right: auto;" name="createsupport" id="createsupport " action="route.php" method="POST" enctype="multipart/form-data">
        <?php //include 'panels/addissue.php';     ?>
        <span id="tickettitle" style="display:none; color:red; font-size: 12px;">Please enter Title.</span>
        <span id="notescomp" style="display:none; color:red; font-size: 12px;">Please enter a Description.</span>
        <span id="issuetype" style="display:none; color:red; font-size: 12px;">Please select ticket type.</span>
        <span id="file_error" style="display:none; color:red; font-size: 12px;">Please select zip file.</span>
        <span id="oprdate" style="display:none; color:red; font-size: 12px;">Please select date.</span>
        <span id="oprdatevalidate" style="display:none; color:red; font-size: 12px;">Please select Different date.</span>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend">
                            <span class="add-on">Title<span class="mandatory">*</span></span>
                            <input type="text" name="issuetitle" id="issuetitle"/>
                            <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>" />
                </div>
                <div class="input-prepend">
                    <span class="add-on">Type<span class="mandatory">*</span></span>
                        <select name="tickettype" id="tickettype" onchange="jQuery('#typeName').val(this.options[this.selectedIndex].innerHTML);">
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
                    <input type="hidden" name="typeName" id="typeName" />
                </div>
                <div class="input-prepend">  <span class="add-on" style="width: auto;margin-top:-2px;">Description <span class="mandatory">*</span></span>
                    <textarea cols="30" rows="1" name="notes_support" id="notes_support" placeholder="Enter Description here" maxlength="200"  style="margin-top:-2px;" autofocus></textarea>
                </div>
                </div>
                <fieldset>
                    <div class="control-group">
                   <div class="input-prepend"> <span class="add-on">Priority<span class="mandatory">*</span></span>
                   <select name="priority" id="priority" onchange="jQuery('#priorityName').val(this.options[this.selectedIndex].innerHTML);">
                                <option value="0">Select Priority</option>
                                <?php
if (isset($getpriority)) {
        foreach ($getpriority as $row) {
            ?>
                                        <option value="<?php echo $row['prid']; ?>" <?php
if ($row['prid'] == '4') {
                echo "selected";
            }
            ?>><?php echo $row['priority']; ?></option>
                                                <?php
}
    }
    ?>
                            </select>
                            <input type="hidden" name="priorityName" id="priorityName" />
                        </div>
                        <div class="input-prepend">
                            <span class="add-on" style="width: 100px;">Enter Mail Id <span class="mandatory">*</span></span>
                        <input type="text" name="ticketmailid" id="ticketmailid" value="" placeholder="Enter mail id here" onkeyup="getmailids()" >
                         <input type="button" style="background: #00a6b8;margin: 0px;width: 80px;" class="btn btn-primary" onclick="insertMailId()" value="Add Mail Id" name="addMail">
                        <input type='hidden' name='sentoEmail' id="sentoEmail"/>
                        </div>
                        <div class="input-prepend">
                           <span class="btn btn-default btn-file">Attachment</span> <input type="file" name="file_upload" id="file_upload">
                       </div>
             <div id="listemailids" ></div>
            </div>
            </fieldset>
            <fieldset>
            <div class="control-group">
                <div class="input-prepend">Show Ticket to <input type="text" name="ticketuserid" id="ticketuserid" value="" placeholder="Enter User Name" onkeyup="getuserlist()" >
                            <div class="input-prepend"> <input type='hidden' name='showtoUser' id="showtoUser"/>
                            </div>
                       <div id="listuserids" ></div>
                    </div>
            </div>
            <div class="input-prepend" style="margin-left:40%">
                <input type="submit" name="send_request" style="background: #00a6b8;" class="btn btn-primary" value="Create Ticket" onclick="return addsupport1();">
            </div>
            </fieldset>
        </fieldset>
    </form>
    </div>
    </div>
    <div class="container-fluid">
        <?php
$ticketdata = getissues();
    include 'pages/panels/viewissues.php';
    if (isset($ticketdata)) {
        foreach ($ticketdata as $thisissue) {
            if ($thisissue['status'] == '0') {
                $status = "Open";
            } elseif ($thisissue['status'] == '1') {
                $status = "Inprogress";
            } elseif ($thisissue['status'] == '2') {
                $status = "Closed";
            } elseif ($thisissue['status'] == '3') {
                $status = "Pipeline";
            } elseif ($thisissue['status'] == '4') {
                $status = "On Hold";
            } elseif ($thisissue['status'] == '5') {
                $status = "Waiting for Client";
            } elseif ($thisissue['status'] == '6') {
                $status = "Resolved";
            } elseif ($thisissue['status'] == '7') {
                $status = "Open";
            }
            $close_date1 = date("d-m-Y", strtotime($thisissue['getclosedate']));
            if ($close_date1 == "01-01-1970") {
                $close_date = "-";
            } else {
                $close_date = $close_date1;
            }
            echo "<tr>";
            echo "<td>SUP00" . $thisissue['ticketid'] . "</td>";
            echo "<td>" . $thisissue['title'] . "</td>";
            echo "<td>" . $thisissue['ticket_type'] . "</td>";
            echo "<td>" . $thisissue['getusername'] . "</td>";
            echo "<td>" . $thisissue['allot_to'] . "</td>";
            echo "<td>" . $thisissue["timestamp"] . "</td>";
            echo "<td>$status</td>";
            echo "<td style='text-align:center'>" . $close_date . "</td>";
            if ($thisissue['status'] != '2') {
                echo "<td style='text-align:center'><a  href = 'support.php?id=3&isid=" . $thisissue['ticketid'] . "'> <i class='icon-pencil'></i></a></td>";
            } else {
                echo "<td style='text-align:center'><a  href = 'support.php?id=4&isid=" . $thisissue['ticketid'] . "'> <i class='icon-pencil'></i></a></td>";
            }
            echo "<td style='text-align:center'><img src='../../images/view.png' style='width: 16px; height:16px;cursor:pointer;' onclick='view_note_list(" . $thisissue['ticketid'] . ");' title='Note History'></img></td>";
            if ($thisissue['create_platform'] == 1) {
                echo "<td style='text-align:center'><img src='../../images/web.png' style='width: 16px; height:16px;' title='Web'></img></td>";
            } elseif ($thisissue['create_platform'] == 2) {
                echo "<td style='text-align:center'><img src='../../images/android.png' style='width: 16px; height:16px;' title='Web'></img></td>";
            } elseif ($thisissue['create_platform'] == 3) {
                echo "<td style='text-align:center'><img src='../../images/iOS.png' style='width: 16px; height:16px;' title='Web'></img></td>";
            } else {
                echo "<td style='text-align:center'></td>";
            }
            echo "</tr>";
        }
    } else {
        echo
            "<tr>
         <td colspan='6'>No Case Created</td>
    <tr>";
    }
    ?>
    </div>
   <div class="container-fluid">
        <?php
$otherTicketdata = getOtherIssue();
    ?>
        <table class="table  table-bordered table-striped dTableR dataTable"  style=" width:100%">
        <center><h4> Additional Tickets for your Information</h4></center>
    <thead>
        <tr>
            <th>Ticket No.</th>
            <th>Title</th>
            <th>Ticket Type</th>
            <th>Created By</th>
            <th>Allot To</th>
            <th>Date Created</th>
            <th>Status</th>
            <th>Closed Date</th>
            <th>Note</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
<?php
if (isset($otherTicketdata)) {
        foreach ($otherTicketdata as $thisissue) {
            if ($thisissue['status'] == '0') {
                $status = "Open";
            } elseif ($thisissue['status'] == '1') {
                $status = "Inprogress";
            } elseif ($thisissue['status'] == '2') {
                $status = "Closed";
            } elseif ($thisissue['status'] == '3') {
                $status = "Pipeline";
            } elseif ($thisissue['status'] == '4') {
                $status = "On Hold";
            } elseif ($thisissue['status'] == '5') {
                $status = "Waiting for Client";
            } elseif ($thisissue['status'] == '6') {
                $status = "Resolved";
            } elseif ($thisissue['status'] == '7') {
                $status = "Open";
            }
            $close_date1 = date("d-m-Y", strtotime($thisissue['getclosedate']));
            if ($close_date1 == "01-01-1970") {
                $close_date = "-";
            } else {
                $close_date = $close_date1;
            }
            echo "<tr>";
            echo "<td>SUP00" . $thisissue['ticketid'] . "</td>";
            echo "<td>" . $thisissue['title'] . "</td>";
            echo "<td>" . $thisissue['ticket_type'] . "</td>";
            echo "<td>" . $thisissue['getusername'] . "</td>";
            echo "<td>" . $thisissue['allot_to'] . "</td>";
            echo "<td>" . $thisissue["timestamp"] . "</td>";
            echo "<td>$status</td>";
            echo "<td style='text-align:center'>" . $close_date . "</td>";
            echo "<td style='text-align:center'><img src='../../images/view.png' style='width: 16px; height:16px;cursor:pointer;' onclick='view_note_list(" . $thisissue['ticketid'] . ");' title='Note History'></img></td>";
            if ($thisissue['create_platform'] == 1) {
                echo "<td style='text-align:center'><img src='../../images/web.png' style='width: 16px; height:16px;' title='Web'></img></td>";
            } elseif ($thisissue['create_platform'] == 2) {
                echo "<td style='text-align:center'><img src='../../images/android.png' style='width: 16px; height:16px;' title='Web'></img></td>";
            } elseif ($thisissue['create_platform'] == 3) {
                echo "<td style='text-align:center'><img src='../../images/iOS.png' style='width: 16px; height:16px;' title='Web'></img></td>";
            } else {
                echo "<td style='text-align:center'></td>";
            }
            echo "</tr>";
        }
    } else {
        echo
            "<tr>
         <td colspan='6'>No Case Created</td>
    <tr>";
    }
    ?>
    </tbody>
    </table>
    </div>
    <div id="noteTab" class="modal hide in" style="min-width: 600px;width:auto; height:300px; display: none;margin-top: -10%">
        <center>
            <form class="form-horizontal">
                <fieldset>
                    <div class="modal-header" >
                        <button class="close" data-dismiss="modal" onclick="clearModal();">×</button>
                        <h4 id="header-6" style="color:#0679c0">Add Note</h4>
                    </div>
                    <div class="modal-body">
                        <span id="noteSuccess" style="display:none; color:green; font-size: 12px;">Note added successfully.</span>
                        <span id="noteFail" style="display:none; color:red; font-size: 12px;">Note creation failed</span>
                        <table>
                            <tr>
                                <td><span class="add-on">Note<span class="mandatory">*</span></span></td>
                                <td>
                                    <textarea name="noteModal" id="noteModal" placeholder="Enter note here.."></textarea>
                                    <input type="hidden" name="ticketModal" id="ticketModal" />
                                </td>
                                <td><input type="button" onclick="submitNote()" value="Add Note"/></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer" style="text-align: center; height: 5px;">
                        <h4 id="header-6" style="color:#0679c0">Note History</h4>
                        <div id="historyTable">
                        </div>
                    </div>
                </fieldset>
            </form>
        </center>
    </div>
    <?php
}
include '../panels/footer.php';
?>