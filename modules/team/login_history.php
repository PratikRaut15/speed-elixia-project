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

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(-1);


class VOLogin {
    
};

$installation = date('d-m-Y');
$installation_date = date('Y-m-d');

$weballcount = 0;
$androidallcount = 0;
$iosallcount = 0;

//Datagtrid

function check_is_web($page_master_id)
{
    $db = new DatabaseManager();    
    $sql = sprintf("SELECT is_web FROM page_master WHERE page_master_id = %d",$page_master_id);
    $db->executeQuery($sql);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            return $row["is_web"];
        }
    }
    return null;
}

$db = new DatabaseManager();

//$SQL = sprintf("SELECT c.totalsms, c.customerno, c.customername, c.smsleft, c.customercompany FROM customer c");
$SQL = sprintf("select lh.created_by,user.realname,user.username, customer.customerno, customer.customercompany, lh.type,
SUM(CASE WHEN page_master.is_web = 1 THEN 1 ELSE 0 END) as androidcount,    
SUM(CASE WHEN page_master.is_web = 0 THEN 1 ELSE 0 END) as webcount,
SUM(CASE WHEN page_master.is_web = 2 THEN 1 ELSE 0 END) as ioscount 
from " . DB_PARENT . ".login_history_details as lh
inner join user on user.userid = lh.created_by
INNER JOIN page_master on page_master.page_master_id=lh.page_master_id
inner join " . DB_PARENT . ".customer on customer.customerno = lh.customerno
Where user.realname <> 'Elixir' AND DATE(lh.created_on) = '$installation_date' group by lh.created_by order by lh.customerno ASC ");

$db->executeQuery($SQL);

$x = 0;
$users = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $x++;
        $user = new VOLogin();
        $user->userid = $row['created_by'];
        $user->customerno = $row['customerno'];
        $user->customercompany = $row['customercompany'];
        $user->realname = $row['realname'];
        $user->username = $row['username'];
        $user->webcount = $row['webcount'];
        $weballcount+= $row['webcount'];
        $user->androidcount = $row['androidcount'];
        $androidallcount+= $row['androidcount'];
        $user->ioscount = $row['ioscount'];
        $iosallcount+= $row['ioscount'];
        $user->x = $x;
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
$dg->AddColumn("Web", "webcount");
$dg->AddColumn("Android", "androidcount");
$dg->AddColumn("iOS", "ioscount");
$dg->AddRightAction("View", "../../images/history.png", "login_hist.php?cid=%d");
$dg->SetNoDataMessage("No Customer");
$dg->AddIdColumn("userid");

$_scripts[] = "../../scripts/trash/prototype.js";

include("header.php");

?>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Search Login  History</div>
    <div class="panelcontents">    
        <form method="post" action="login_history.php"  enctype="multipart/form-data">
            <table>
                <?php
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
    $fromdate = date('Y-m-d 00:00:00', strtotime($fromdate));
    $todate = GetSafeValueString($_POST["todate"], "string");
    $todate = date('Y-m-d 23:59:59', strtotime($todate));
    $monthfrom = date('M', strtotime($fromdate));
    $monthto = date('M', strtotime($todate));
    $year = date('Y', strtotime($fromdate));
    $monthyear = $monthfrom.$year;
    if ($monthfrom != $monthto) {
        echo "<script type='text/javascript'>alert('Error - Month Will Be Same')</script>";
    } else {
        $SQL = sprintf("select customerno from " . DB_PARENT . ".customer order by customerno ASC");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $customer = new VOLogin();
                $customer->customerno = $row['customerno'];
                $customers[] = $customer;
            }
        }

//        print_r($customers);

        foreach ($customers as $customer) {
            $vm = new CustomerManager();

            $location = "../../customer/$customer->customerno/history/login/$monthyear.sqlite";
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);
                
                $query = "SELECT *, SUM(CASE WHEN page_master_id < 37 THEN 1 ELSE 0 END) as webcount,    
SUM(CASE WHEN page_master_id < 47 AND page_master_id > 36 THEN 1 ELSE 0 END) as androidcount,
SUM(CASE WHEN page_master_id > 46 THEN 1 ELSE 0 END) as ioscount 
 FROM loginhistory WHERE created_on BETWEEN '$fromdate' AND '$todate' GROUP BY created_by;";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new VOLogin();
                        $Datacap->androidcount = 0 ;                                                        
                        $Datacap->ioscount = 0 ;                                                        
                        $Datacap->webcount = 0 ;                                                                                
                        $chkuser = $vm->GetUserRole($row['created_by']);
                        if ($chkuser->role != 'elixir' && $chkuser->role != '') {
                            $Datacap->id = $row['id'];                            
                            $Datacap->customerno = $row['customerno'];
                            $Datacap->userid = $row['created_by'];
//                            $is_web = check_is_web($row['page_master_id']);
//                            if($is_web == 1)
 //                           {
                                $Datacap->androidcount+=$row['androidcount'];                                
                                $androidallcount+= $row['androidcount'];                                
  //                          }
   //                         if($is_web == 2)
    //                        {
                                $Datacap->ioscount+= $row['ioscount'];                                
                                $iosallcount+= $row['ioscount'];                                
     //                       }
      //                      if($is_web == 0)
       //                     {
                                $Datacap->webcount+= $row['webcount'];
                                $weballcount+= $row['webcount'];                                
        //                    }
                            $Datacap->realname = $chkuser->realname;
                            $Datacap->customername = $chkuser->customercompany;
                            $Datacap->username = $chkuser->username;

                            $REPORT[] = $Datacap;
                        }
                    }
                }
            }
        }

        $df = new objectdatagrid($REPORT);
        $df->AddColumn("Customer No#", "customerno");
        $df->AddColumn("Company Name", "customername");
        $df->AddColumn("Realname #", "realname");
        $df->AddColumn("Username", "username");
        $df->AddColumn("Web", "webcount");
        $df->AddColumn("Android", "androidcount");
        $df->AddColumn("iOS", "ioscount");
        $df->AddRightAction("View", "../../images/history.png", "login_histfilter.php?cid=%d&from=$fromdate&to=$todate");
        $df->SetNoDataMessage("No Customer");
        $df->AddIdColumn("id");
        ?>
        <div class="panel">
            <div class="panelcontents" align="right">

                <b>Web:&nbsp;<?php
                    if (isset($weballcount)) {
                        echo $weballcount;
                    }
                    ?></b>
                <b>Android:&nbsp;<?php
                    if (isset($androidallcount)) {
                        echo $androidallcount;
                    }
                    ?></b>
                <b>iOS:&nbsp;<?php
            if (isset($iosallcount)) {
                echo $iosallcount;
            }
            ?></b>
            </div> 
            <div class="paneltitle" align="center">Login History</div>
            <div class="panelcontents">
        <?php $df->Render(); ?>
            </div>

        </div>
        <?php
        //print_r($REPORT);
    }
} else {
    ?>

    <br/>   
    <div class="panel">
        <div class="panelcontents" align="right">
            <?php
/*            $sql = sprintf("select sum(totallogins.androidcount) as androidallcount,sum(totallogins.ioscount) as iosallcount, sum(totallogins.webcount) as weballcount FROM (select SUM(CASE WHEN page_master.is_web = 1 THEN 1 ELSE 0 END) as androidcount,SUM(CASE WHEN page_master.is_web = 2 THEN 1 ELSE 0 END) as ioscount, SUM(CASE WHEN page_master.is_web = 0 THEN 1 ELSE 0 END) as webcount from " . DB_PARENT . ".login_history_details as lh INNER JOIN page_master on page_master.page_master_id=lh.page_master_id inner join user on user.userid = lh.created_by inner join " . DB_PARENT . ".customer on customer.customerno = lh.customerno Where user.role <> 'elixir' group by lh.created_by order by lh.customerno ASC ) as totallogins");
            $db->executeQuery($sql);
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $weballcount = $row["weballcount"];
                    $androidallcount = $row["androidallcount"];
                    $iosallcount = $row["iosallcount"];
                }
            }
 * 
 */
            ?>
            <b>Web:&nbsp;</b><?php echo $weballcount; ?>
            <b>Android:&nbsp;</b><?php echo $androidallcount; ?>
            <b>iOS:&nbsp;</b><?php echo $iosallcount; ?>
        </div>    
        <div class="paneltitle" align="center">Login History</div>
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

    Calendar.setup(
            {
                inputField: "fromdate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger" // ID of the button
            });

    Calendar.setup(
            {
                inputField: "todate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger2" // ID of the button
            });

</script>

