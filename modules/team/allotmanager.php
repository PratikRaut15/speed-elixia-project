<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");

$_scripts[] = "../../scripts/trash/prototype.js";

class allot {
    
}

$display = "<!DOCTYPE html>
<html>
    <head>
        <title>Create Customer</title>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style type='text/css'>
            body{
                font-family:Arial;
                font-size: 11pt;
            }
            table{
                text-align: center;
                border-right:1px solid black;
                border-bottom:1px solid black;

                border-collapse:collapse;
                font-family:Arial;
                font-size: 10pt;
                width: 60%;
            }

            td, th{
                border-left:1px solid black;
                border-top:1px solid black;
            }

            .colHeading{
                background-color: #D6D8EC;
            }

            span{
                font-weight:bold;
            }
        </style>
    </head>
    <body>
        <div style='color: #000000'>
            <h4>Dear {{CUSTOMERNAME}},</h4>
            <p>Following are the details of allotted Customer Relationship Manager ,</p>";

$db = new DatabaseManager();

$cid = $_GET['cid'];
$SQL = sprintf("select * from " . DB_PARENT . ".customer where customerno=$cid");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $team = new allot();
        $team->customerno = $row["customerno"];
        $team->customername = $row["customername"];
        $team->customercompanyname = $row["customercompany"];
        $team->rel_manager = $row['rel_manager'];
    }
}

$SQL = sprintf("select email from " . DB_PARENT . ".user where customerno=$cid AND role='Administrator'");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $user = new allot();
        $user->email = $row["email"];
    }
}
$SQLManager = ("select * from " . DB_PARENT . ".relationship_manager where isdeleted = 0");
$db->executeQuery($SQLManager);
$managers = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $manger = new allot();
        $manger->rid = $row["rid"];
        $manger->name = $row['manager_name'];
        $managers[] = $manger;
    }
}
$subject = "CRM allottment ";

$toArr = array();
array_push($toArr, $user->email);

$CCEmail = 'support@elixiatech.com';
$BCCEmail = 'sanketsheth1@gmail.com';
$attachmentFilePath = '';
$attachmentFileName = '';

//print_r($managers);
if (isset($_POST["submitmanager"])) {
    //echo "ok";
    $customerno = GetSafeValueString($_POST["customerno"], "string");
    $manager = GetSafeValueString($_POST["manager"], "string");
    if ($manager == '00') {
        echo '<script type="text/javascript">alert("Please Select The Relationship Manager");</script>';
    } else {
        $SQL = sprintf("Update " . DB_PARENT . ".customer SET rel_manager=$manager WHERE customerno=$customerno");
        $db->executeQuery($SQL);

        $SQLManager = ("select * from " . DB_PARENT . ".relationship_manager where rid=$manager AND isdeleted = 0");
        $db->executeQuery($SQLManager);
        $manger = new allot();        
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $manger->manager_name = $row['manager_name'];
                $manger->manager_mobile = $row['manager_mobile'];
                $manger->manager_email = $row['manager_email'];                
            }
        }
        $display.="<table><tr><th>Sr No</th><th>CRM Name</th><th>CRM Contact Number</th><th>CRM Email Address</th></tr>";
        $display.="<tr><td>1</td><td>" . $manger->manager_name . "</td><td>" . $manger->manager_mobile . "</td><td>" . $manger->manager_email . "</td></tr>";
        $display .= "</table><br>
                <br/>Warm Regards, <br/>
                Support Team,<br/>
                Elixia Speed<br/>
                Elixia Tech Solutions Ltd.
                <br/><br/>
                <img style='width: 240px;' src = 'http://elixiaspeed.com/images/elixia_logo_75.png' alt = 'Elixia Speed logo' />
            </div>
        </body>
        </html>";
        $display = str_replace("{{CUSTOMERNAME}}", $team->customername, $display);
       
        $isSMSSent = sendMailUtil($toArr, $CCEmail, $BCCEmail, $subject, $display, $attachmentFilePath, $attachmentFileName, 1);
        
        header("Location: allotrm.php");
    }
}

include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">
        Allot Relationship Manager</div>
    <div class="panelcontents">
        <form method="post" name="myform" id="myform" action="allotmanager.php?cid=<?php echo $cid ?>" enctype="multipart/form-data">

            <br/>        
            <p id="error1" style="display: none;">test</p>
            <table width="50%" align="center">
                <tr style="height: 30px;">
                    <td>Customer No.</td>
                    <td><?php echo $team->customerno ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td>Customer Name</td>
                    <td><?php echo $team->customername ?></td>             
                </tr>
                <tr style="height: 30px;">
                    <td>Customer Company</td>
                    <td><?php echo $team->customercompanyname ?></td>             
                </tr>
                <tr style="height: 30px;">
                    <td>Relationship Manager</td>
                    <td>
                        <select name="manager" id="manager">
                            <option value="00">Select Relationship Manager</option>
                            <?php
                            if (!empty($managers)) {
                                foreach ($managers as $mangerlist) {
                                    ?>
                                    <option value="<?php echo $mangerlist->rid ?>" <?php
                                    if ($team->rel_manager == $mangerlist->rid) {
                                        echo 'selected';
                                    }
                                    ?> >
                                        <?php echo $mangerlist->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>             
                </tr>
            </table>


            <hr/> 
            <div align="center">
                <input type="hidden" id="submit" name="customerno" value="<?php echo $team->customerno; ?>"/>
                <input type="submit" id="submit" name="submitmanager" value="Allot Manager"/>
            </div>
        </form>
    </div>
</div>


<br/>



<?php
include("footer.php");
?>

<script type="text/javascript">

    function show_heirarchy()
    {

        if ($("#cmaintenance").is(':checked'))
            $("#heir_tr").show()
        else
            $("#heir_tr").hide()


    }

</script>    