<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/bo/CustomerManager.php");


// Get Userid
class VOLogin{};
$db = new DatabaseManager();
$userid = $_GET['cid']; 
$fromdate = $_GET['from']; 
$todate = $_GET['to']; 
$monthfrom = date('M', strtotime($fromdate));
$year =date('Y',  strtotime($fromdate));
$vm = new CustomerManager();
$chkuser = $vm->GetUserRole($userid);
$REPORT = array();
$fromdate1 = date('Y-m-d',strtotime($fromdate));
$curr = date('Y-m-d');
$x = 0;
if($fromdate1 == $curr)// search for current date and time
    {
       $SQL = "SELECT * from ".DB_PARENT.".login_history WHERE userid=$userid AND timestamp between '$fromdate' AND '$todate' ORDER BY timestamp DESC";  
               
               $db->executeQuery($SQL);
               //print_r($res);
               if ($db->get_rowCount() > 0)
               {
                     while ($row = $db->get_nextRow())
                     {
                           $Datacap = new VOLogin();
                           //echo $row['userid'];
                           $x++;
                          $vm = new CustomerManager();
                           $chkuser = $vm->GetUserRole($row['userid']);
                           if($chkuser->role != 'elixir' && $chkuser->role !=''){
                           $Datacap->customerno = $row['customerno'];
                           $Datacap->userid= $row['userid'];
                           $Datacap->status = $row['type'];
                           $Datacap->timestamp = $row['timestamp'];
                           $Datacap->realname = $chkuser->realname;
                           $Datacap->customercompany = $chkuser->customercompany;
                           $Datacap->username= $chkuser->username;
                           $Datacap->x= $x;
                           if ($Datacap->status == 1){
                           $Datacap->status = "Mobile";
                                                   }
                           else{
                           $Datacap->status = "Web";
                                }
                           $REPORT[] = $Datacap;
                           }
                     }
                }
    
                //print_r($REPORT);
                           }
        else{                   
 $location ="../../customer/$chkuser->customerno/history/$monthfrom$year.sqlite";
            if(file_exists($location))
            {
               $path = "sqlite:$location";
               $db = new PDO($path);
               
               $query = "SELECT userid,customerno,timestamp, 
                    CASE 
                    WHEN loginhistory.type = 1 THEN
                      'Mobile'
                    ELSE
                     'Web'
                    END 
                    as status
                    from ".DB_PARENT.".loginhistory where userid=$userid AND timestamp between '$fromdate' AND '$todate' order by timestamp DESC ";  
               $result = $db->query($query);
               if(isset($result) && $result!="")
               {
                     foreach ($result as $row)
                     {
                           $Datacap = new VOLogin();
                           $x++;
                           if($chkuser->role != 'elixir' && $chkuser->role !=''){
                           $Datacap->customerno = $row['customerno'];
                           $Datacap->userid= $row['userid'];
                           $Datacap->status = $row['status'];
                           $Datacap->realname = $chkuser->realname;
                           $Datacap->customercompany = $chkuser->customercompany;
                           $Datacap->username= $chkuser->username;
                           $Datacap->x= $x;                           
                           $Datacap->timestamp = $row['timestamp'];
                           $REPORT[] = $Datacap;
                           }
                     }
              }
            }
        }    
//Datagtrid
$db = new DatabaseManager();


$dg = new objectdatagrid( $REPORT );
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
$dg->AddColumn("Serial #", "x");
$dg->AddColumn("Customer #", "customerno");
$dg->AddColumn("Customer Company", "customercompany");
$dg->AddColumn("Realname", "realname");
$dg->AddColumn("Username", "username");
$dg->AddColumn("Login Type", "status");

$dg->AddColumn("Date Time", "timestamp");
//$dg->AddRightAction("View", "../../images/history.png", "login_hist.php?cid=%d");
$dg->SetNoDataMessage("No Customer");
$dg->AddIdColumn("userid");

$_scripts[] = "../../scripts/trash/prototype.js";
 
include("header.php");

// Get Username From Userid
$SQL = sprintf("select realname,username from user where userid=%d", $userid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $username = $row["username"];
        $realname = $row["realname"];
    }    
}

?>

<br/>

<div class="panel">
<div class="paneltitle" align="center">Login History For  - <?php echo $realname; echo " (". $username .")";?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>

<?php
include("footer.php");
?>

