<?php
include_once("session.php");
include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/bo/CustomerManager.php");
include_once("../../lib/bo/VehicleManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");

//include_once("../../scripts/timepicker/jquery.min.js");
class Logintime {
    
}

//Datagtrid
$db = new DatabaseManager();

//$SQL = sprintf("SELECT c.totalsms, c.customerno, c.customername, c.smsleft, c.customercompany FROM customer c");
$SQL = "SELECT lh.created_by,user.realname,user.username, customer.customerno, customer.customercompany, page_master.is_web,lh.created_on from " . DB_PARENT . ".login_history_details as lh
             INNER JOIN user on user.userid = lh.created_by 
             INNER JOIN page_master on page_master.page_master_id=lh.page_master_id
             INNER JOIN " . DB_PARENT . ".customer on customer.customerno = lh.customerno WHERE user.realname <> 'Elixir' AND DATE(lh.created_on)=CURDATE() order by lh.created_on DESC";
$db->executeQuery($SQL);

$x = 0;
$users = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $x++;
        $user = new Logintime();
        $user->userid = $row['userid'];
        $user->customerno = $row['customerno'];
        $user->customercompany = $row['customercompany'];
        $user->realname = $row['realname'];
        $user->username = $row['username'];
        //$user->webcount = $row['webcount'];
        //$user->mobilecount = $row['mobilecount'];
        $user->status = $row['is_web'];
        $user->datetime = $row['created_on'];
        $user->x = $x;
        if ($user->status == 1) {
            $user->status = "Android";
        } elseif ($user->status == 0) {
            $user->status = "Web";
        } else {
            $user->status = "iOS";
        }
        $users[] = $user;
    }
}
$dg = new objectdatagrid($users);
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
$dg->AddColumn("Serial #", "x");
$dg->AddColumn("Customer #", "customerno");
$dg->AddColumn("Company", "customercompany");
$dg->AddColumn("Realname #", "realname");
$dg->AddColumn("Username", "username");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Date & Time", "datetime");
//$dg->AddColumn("Web", "webcount");
//$dg->AddColumn("Mobile", "mobilecount");
//$dg->AddRightAction("View", "../../images/history.png", "login_hist.php?cid=%d");
$dg->SetNoDataMessage("No Customer");
$dg->AddIdColumn("userid");

$_scripts[] = "../../scripts/trash/prototype.js";
$_scripts[] = "../../scripts/trash/timepicker/jquery.min.js";
include("header.php");
?>

<!-----------------------------------panel for search login history------------------------------------------------------------------>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Search Login  History</div>
    <div class="panelcontents">    
        <form method="post" action="login_time.php"  enctype="multipart/form-data">
            <table>
                <?php
                $installation = date('d-m-Y');
                if (!isset($_POST['fromdate'])) {
                    $_POST['fromdate'] = $installation;
                }if (!isset($_POST['todate'])) {
                    $_POST['todate'] = $installation;
                }
                ?>
                <tr>
                    <td>From Date </td>
                    <td> <input name="fromdate" id="fromdate" type="text" value="<?php echo $_POST['fromdate']; ?>"/><button id="trigger">...</button>
                    </td>

                    <td>To Date </td>
                    <td> <input name="todate" id="todate" type="text" value="<?php echo $_POST['todate']; ?>"/><button id="trigger2">...</button>
                    </td>
                    <td>From</td>
                    <td><input id="STime" name="STime" type="text" class="input-mini" value="<?php
                        if (isset($_POST['STime'])) {
                            echo $_POST['STime'];
                        } else
                            echo "00:00";
                        ?>" />
                    </td>
                    <td> To</td>
                    <td><input id="ETime" name="ETime" type="text" class="input-mini" value= "<?php
                        if (isset($_POST['ETime'])) {
                            echo $_POST['ETime'];
                        } else
                            echo "23:59";
                        ?>" />
                    </td>
                    <td>
                        <input type="submit" name="search" value="Search" />
                    </td>
                </tr>
            </table>

        </form>
    </div>
</div>
<br/>

<?php
if (isset($_POST["search"])) {
    //echo "ok";
    $REPORT = array();
    $ResultSet = array();
    $customers = array();
    $fromdate = GetSafeValueString($_POST["fromdate"], "string");
    $fromdate1 = GetSafeValueString($_POST["fromdate"], "string");
    $stime = GetSafeValueString($_POST['STime'], "string");
    $fromdate = $fromdate . " " . $stime . ":00";
    $fromdate = date('Y-m-d H:i:s', strtotime($fromdate));
    $todate = GetSafeValueString($_POST["todate"], "string");
    $etime = GetSafeValueString($_POST['ETime'], "string");
    $todate = $todate . " " . $etime . ":00";
    $todate = date('Y-m-d H:i:s', strtotime($todate));
    $monthfrom = date('M', strtotime($fromdate));
    $monthto = date('M', strtotime($todate));
    $year = date('Y', strtotime($fromdate));
    $curr = date('d-m-Y');
    if ($fromdate1 == $curr) {// search for current date and time
        $SQL = "SELECT lh.created_by,user.realname,page_master.is_web,user.username,customer.customerno, customer.customercompany,lh.created_on from " . DB_PARENT . ".login_history_details as lh 
               INNER JOIN user on user.userid = lh.created_by 
               INNER JOIN page_master on page_master.page_master_id=lh.page_master_id
               INNER JOIN " . DB_PARENT . ".customer on customer.customerno = lh.customerno 
               WHERE DATE(lh.created_on) between '$fromdate' AND '$todate' 
               ORDER BY lh.created_on DESC";

        $db->executeQuery($SQL);
        //print_r($res);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $Datacap = new Logintime();
                //echo $row['userid'];
                $vm = new CustomerManager();
                $chkuser = $vm->GetUserRole($row['created_by']);
                if ($chkuser->realname != 'Elixir' && $chkuser->realname != '') {
                    $Datacap->customerno = $row['customerno'];
                    $Datacap->userid = $row['created_by'];
                    $Datacap->status = $row['is_web'];
                    $Datacap->datetime = $row['created_on'];
                    $Datacap->realname = $chkuser->realname;
                    $Datacap->customername = $chkuser->customercompany;
                    $Datacap->username = $chkuser->username;

                    if ($Datacap->status == 1) {
                        $Datacap->status = "Android";
                    } elseif ($Datacap->status == 2) {
                        $Datacap->status = "iOS";
                    } else {
                        $Datacap->status = "Web";
                    }
                    $REPORT[] = $Datacap;
                }
            }

            $SQLCount = "SELECT SUM(CASE WHEN page_master.is_web = 1 THEN 1 ELSE 0 END) as androidcoumt,
                                SUM(CASE WHEN page_master.is_web = 2 THEN 1 ELSE 0 END) as ioscount,
                                SUM(CASE WHEN page_master.is_web = 0 THEN 1 ELSE 0 END) as webcount    
                                FROM " . DB_PARENT . ".login_history_details as lh
                                INNER JOIN user on user.userid = lh.created_by
                                INNER JOIN page_master on page_master.page_master_id=lh.page_master_id
                                WHERE user.realname <> 'Elixir' AND DATE(lh.created_on) between '$fromdate' AND '$todate'";

            $db->executeQuery($SQLCount);


            while ($row = $db->get_nextRow()) {
                $webcount = $row['webcount'];
                $androidcount = $row['androidcoumt'];
                $ioscount = $row['ioscount'];
            }
        }
        //print_r($REPORT);
    } elseif ($monthfrom != $monthto) {
        echo "<script type='text/javascript'>alert('Error - Month Will Be Same')</script>";
    } else {//no current date check from sqlite
        $SQL = sprintf("select customerno from " . DB_PARENT . ".customer order by customerno ASC");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $customer = new Logintime();
                $customer->customerno = $row['customerno'];
                $customers[] = $customer;
            }
        }

        //print_r($customers)

        foreach ($customers as $customer) {
            $vm = new CustomerManager();

            $location = "../../customer/$customer->customerno/history/$monthfrom$year.sqlite";
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $query = "SELECT lh.created_by,user.realname,page_master.is_web,user.username,customer.customerno, customer.customercompany,lh.created_on from " . DB_PARENT . ".login_history_details as lh 
               INNER JOIN user on user.userid = lh.created_by 
               INNER JOIN page_master on page_master.page_master_id=lh.page_master_id
               INNER JOIN " . DB_PARENT . ".customer on customer.customerno = lh.customerno WHERE DATE(lh.created_on) between '$fromdate' AND '$todate' ORDER BY lh.created_on DESC";

                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {

                        $Datacap = new Logintime();
                        $chkuser = $vm->GetUserRole($row['userid']);
                        if ($chkuser->role != 'Elixir' && $chkuser->role != '') {
                            $Datacap->customerno = $row['customerno'];
                            $Datacap->userid = $row['userid'];
                            $Datacap->status = $row['is_web'];
                            $Datacap->datetime = $row['timestamp'];
                            $Datacap->realname = $chkuser->realname;
                            $Datacap->customername = $chkuser->customercompany;
                            $Datacap->username = $chkuser->username;
                            if ($Datacap->status == 1) {
                                $Datacap->status = "Android";
                            } elseif ($Datacap->status == 2) {
                                $Datacap->status = "iOS";
                            } else {
                                $Datacap->status = "Web";
                            }
                            $REPORT[] = $Datacap;
                        }
                    }
                }

                $queryCount = "SELECT created_by,SUM(CASE WHEN page_master.is_web = 1 THEN 1 ELSE 0 END) as androidcoumt,
                                SUM(CASE WHEN page_master.is_web = 2 THEN 1 ELSE 0 END) as ioscount,
                                SUM(CASE WHEN page_master.is_web = 0 THEN 1 ELSE 0 END) as webcount    
                                FROM " . DB_PARENT . ".login_history_details as lh
                                INNER JOIN user on user.userid = lh.created_by
                                INNER JOIN page_master on page_master.page_master_id=lh.page_master_id WHERE DATE(lh.created_on) between '$fromdate' AND '$todate'";

                $res = $db->query($queryCount);
                if (isset($res) && $res != "") {
                    foreach ($res as $row) {
                        $chkuser = $vm->GetUserRole($row['userid']);
                        if ($chkuser->role != 'Elixir' && $chkuser->role != '') {
                            $webcount += $row['webcount'];
                            $androidcount += $row['androidcoumt'];
                            $ioscount += $row['ioscount'];
                        }
                    }
                }
            }
        }



        //print_r($REPORT);
    }
    /*     * $weballcount=array();
      $mobilecount =array();
      foreach($REPORT as $count){
      $weballcount[]= $count->webcount;
      $mobilecount[] = $count->mobilecount;
      }
      $weball = array_sum($weballcount); //sum all webcount
      $moball = array_sum($mobilecount); //sum all mobilecount
     * */

    $df = new objectdatagrid($REPORT);
    $df->AddColumn("Customer No#", "customerno");
    $df->AddColumn("Company Name", "customername");
    $df->AddColumn("Realname #", "realname");
    $df->AddColumn("Username", "username");
    $df->AddColumn("Status", "status");
    $df->AddColumn("Date & Time", "datetime");
    //$df->AddColumn("Web", "webcount");
    //$df->AddColumn("Mobile", "mobilecount");
    //$df->AddRightAction("View", "../../images/history.png", "login_histfilter.php?cid=%d&from=$fromdate&to=$todate");
    $df->SetNoDataMessage("No Customer");
    $df->AddIdColumn("userid");
    ?>

    <!-----------------------------------panel for login history--------------------------------------->
    <div class="panel">
        <div class="panelcontents" align="right">

            <b>Web:&nbsp;<?php echo $webcount; ?></b>
            <b>Android:&nbsp;<?php echo $androidcount; ?></b>
            <b>iOS:&nbsp;<?php echo $ioscount; ?></b>
        </div> 
        <div class="paneltitle" align="center">Login History</div>
        <div class="panelcontents">
            <?php $df->Render(); ?>
        </div>

    </div>
    <?php
} else {// to show count
    ?>

    <br/>   
    <div class="panel">
        <div class="panelcontents" align="right">
            <?php
            $sql = sprintf("select sum(totallogins.androidcount) as androidallcount,sum(totallogins.ioscount) as iosallcount, sum(totallogins.webcount) as weballcount FROM (select SUM(CASE WHEN page_master.is_web = 1 THEN 1 ELSE 0 END) as androidcount,SUM(CASE WHEN page_master.is_web = 2 THEN 1 ELSE 0 END) as ioscount, SUM(CASE WHEN page_master.is_web = 0 THEN 1 ELSE 0 END) as webcount from " . DB_PARENT . ".login_history_details as lh 
                        inner join user on user.userid = lh.created_by
                        INNER JOIN page_master on page_master.page_master_id=lh.page_master_id
                        inner join " . DB_PARENT . ".customer on customer.customerno = lh.customerno
                        Where user.role <> 'elixir' group by lh.created_by order by lh.customerno ASC ) as totallogins");
            
            $db->executeQuery($sql);
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $weballcount = $row["weballcount"];
                    $androidallcount = $row["androidallcount"];
                    $iosallcount = $row["iosallcount"];
                }
            }
            ?>
            <b>Web:&nbsp;</b><?php echo $weballcount; ?>
            <b>Android:&nbsp;</b><?php echo $androidallcount; ?>
            <b>iOS:&nbsp;</b><?php echo $iosallcount; ?>
        </div>
        <!-------------------------------for 1st datagrid render------------------------------------------->    
        <div class="paneltitle" align="center">Login History By Time(latest 10 logins)</div>
        <div class="panelcontents">
            <?php $dg->Render(); ?>
        </div>

    </div>
    <br/>

    <?php
}

include("footer.php");
?>

<script>
    $(document).ready(function () {

        Calendar.setup(
                {
                    inputField: "fromdate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format

                    button: "trigger" // ID of the button
                });

        Calendar.setup(
                {
                    inputField: "todate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format;
                    button: "trigger2" // ID of the button
                });

        $('#STime').timepicker({'timeFormat': 'H:i'});
        $('#ETime').timepicker({'timeFormat': 'H:i'});

    });

</script>