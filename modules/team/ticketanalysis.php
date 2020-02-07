<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");

class testing{
    
}
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$todaydate =date("Y-m-d");
include("header.php");
?>
<div class="panel">
<div class="paneltitle" align="center">Ticket Analysis</div>
<?php 
$gofun=0;
$message="";
if(isset($_POST['gofun'])){
    $fromdate = $_POST["fromdate"];
    if($fromdate==""){
        $message="Please select date.";
    }
    if($message==""){
        if($_POST["gofun"]=="Report"){
            $gofun="1";
        }
    }
}
   

?>
<div class="panelcontents">
    <?php if($message!=""){ echo"<span style='color:red; font-size:12px;'>".$message."</span>";  }?>
    <form name="dateform" method="POST" action="ticketanalysis.php">  
    <table align="center" cellpadding="5">
        <tr>
            <td> Date :</td>
            <td><input id="fromdate" name="fromdate" placeholder="dd-mm-yyyy" type="text" value="<?php echo $fromdate; ?>"/></td>
            <td><input type="submit" name="gofun" value="Report"/></td>
            <td>&nbsp;</td>
        </tr>
    </table>
    </form>
   <?php
   if($gofun=='1'){
    $fromdate1 = date("Y-m-d",strtotime($fromdate));
    $date1=date_create($fromdate1);
    date_time_set($date1,23,50,50);
    $from_date = date_format($date1,'Y-m-d H:i:s');
   ?>    
       <table border="1" style="width:100%;">
        <tr>
            <th width="20%">Team</th>
            <th width="10%"style="text-align: center;">Created Tickets</th>
           <th width="10%" style="text-align: center;">Open Tickets</th>
           <th width="10%" style="text-align: center;">In Progress Tickets</th>
           <th width="10%" style="text-align: center;">Closed Tickets</th>
       
        </tr>
        <?php
            $db = new DatabaseManager();
            function get_created_count($teamid,$from_date)
            {
                $db = new DatabaseManager();
                $sql = sprintf("select * from ".DB_PARENT.".sp_ticket where create_by=".$teamid." AND create_on_date < '".$from_date."'");
                $db->executeQuery($sql);
                    if ($db->get_rowCount()>0) 
                    {
                        $status =  $db->get_rowCount();
                    }else{
                        $status="";
                    }
                return $status;    
            }
            
            function get_count($teamid,$from_date,$status){
            $db = new DatabaseManager();
            $sql = sprintf("select * from (select * from ".DB_PARENT.".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.create_by = ".$teamid." AND main.create_on_time < '".$from_date."' AND main.status=".$status);
            $db->executeQuery($sql);
            if ($db->get_rowCount()>0) 
            {
                $status =  $db->get_rowCount();
            }else{
                $status="";
            }

            return $status;    
            }
            
            function get_open_count($teamid,$from_date,$status){
            $db = new DatabaseManager();
            $sql = sprintf("select * from (select * from ".DB_PARENT.".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.allot_to = ".$teamid." AND main.create_on_time < '".$from_date."' AND main.status=".$status);
            $db->executeQuery($sql);
            if ($db->get_rowCount()>0) 
            {
                $status =  $db->get_rowCount();
            }else{
                $status="";
            }

            return $status;    
            }
            
            
            function get_inprogress_count($teamid,$from_date,$status){
            $db = new DatabaseManager();
            $sql = sprintf("select * from (select * from ".DB_PARENT.".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.allot_to = ".$teamid." AND main.create_on_time < '".$from_date."' AND main.status=".$status);
            $db->executeQuery($sql);
            if ($db->get_rowCount()>0) 
            {
                $status =  $db->get_rowCount();
            }else{
                $status="";
            }

            return $status;    
            }
            
            
            
    
            $sql = sprintf("select * from ".DB_PARENT.".team where role IN ('Head','Sales','Service','Admin','Data','CRM')");
            $db->executeQuery($sql);
                 while($row = $db->get_nextRow())   
                {
                     $teamid = $row['teamid'];
                     $created_count = get_created_count($teamid,$from_date);
                     $closed_count = get_count($teamid,$from_date,'2');
                     $inprogress_count = get_inprogress_count($teamid,$from_date,'1');
                     $opened_count = get_open_count($teamid,$from_date,'0');
                     
                    ?>    
                    <tr>
                        <td><?php echo $row['name'];?></td>
                        <td style="text-align: right;"> <?php echo $created_count; ?> </td>
                        <td style="text-align: right;"> <?php echo $opened_count;?> </td>
                        <td style="text-align: right;"> <?php echo $inprogress_count;?> </td>
                        <td style="text-align: right;"> <?php echo $closed_count;?> </td>
                        
                    </tr>
                <?php
                }
        ?>
    </table>
   <?php
    }
    else
    {
        $date1=date_create($todaydate);
        date_time_set($date1,00,00,00);
        $from_date = date_format($date1,'Y-m-d H:i:s');

        $date2=date_create($todaydate);
        date_time_set($date2,23,50,50);
        $to_date = date_format($date2,'Y-m-d H:i:s');
    ?>    
       <table border="1" style="width:100%;">
        <tr>
            <th width="20%">Team</th>
            <th width="10%"style="text-align: center;">Created Tickets</th>
           <th width="10%" style="text-align: center;">Open Tickets</th>
           <th width="10%" style="text-align: center;">In Progress Tickets</th>
           <th width="10%" style="text-align: center;">Closed Tickets</th>
       
        </tr>
        <?php
            $db = new DatabaseManager();
            function get_created_count1($teamid,$from_date,$to_date)
            {
                $db = new DatabaseManager();
                $sql = sprintf("select * from ".DB_PARENT.".sp_ticket where create_by=".$teamid." AND create_on_date BETWEEN '".$from_date."' AND '".$to_date."'");
                $db->executeQuery($sql);
                    if ($db->get_rowCount()>0) 
                    {
                        $status =  $db->get_rowCount();
                    }else{
                        $status="";
                    }
                return $status;    
            }
            
            function get_count1($teamid,$from_date,$to_date,$status){
            $db = new DatabaseManager();
            $sql = sprintf("select * from (select * from ".DB_PARENT.".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.create_by = ".$teamid." AND main.create_on_time BETWEEN '".$from_date."'AND '".$to_date."' AND main.status=".$status);
            $db->executeQuery($sql);
            if ($db->get_rowCount()>0) 
            {
                $status =  $db->get_rowCount();
            }else{
                $status="";
            }

            return $status;    
            }
            
             
            function get_inprogress_count1($teamid,$from_date,$to_date,$status){
            $db = new DatabaseManager();
            $sql = sprintf("select * from (select * from ".DB_PARENT.".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.allot_to = ".$teamid." AND  main.create_on_time BETWEEN '".$from_date."'AND '".$to_date."' AND main.status=".$status);
            $db->executeQuery($sql);
            if ($db->get_rowCount()>0) 
            {
                $status =  $db->get_rowCount();
            }else{
                $status="";
            }
            return $status;    
            }
            
             function get_open_count1($teamid,$from_date,$to_date,$status){
            $db = new DatabaseManager();
            $sql = sprintf("select * from (select * from ".DB_PARENT.".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.allot_to = ".$teamid." AND  main.create_on_time BETWEEN '".$from_date."'AND '".$to_date."' AND main.status=".$status);
            $db->executeQuery($sql);
            if ($db->get_rowCount()>0) 
            {
                $status =  $db->get_rowCount();
            }else{
                $status="";
            }
            return $status;    
            }
            
            
            
    
            $sql = sprintf("select * from ".DB_PARENT.".team where role IN ('Head','Sales','Service','Admin','Data','CRM')");
            $db->executeQuery($sql);
                 while($row = $db->get_nextRow())   
                {
                     $teamid = $row['teamid'];
                     $created_count1 = get_created_count1($teamid,$from_date,$to_date);
                     $closed_count1 = get_count1($teamid,$from_date,$to_date,'2');
                     $inprogress_count1 = get_inprogress_count1($teamid,$from_date,$to_date,'1');
                     $opened_count1 = get_open_count1($teamid,$from_date,$to_date,'0');
                     
                    ?>    
                    <tr>
                        <td><?php echo $row['name'];?></td>
                        <td style="text-align: right;"> <?php echo $created_count1; ?> </td>
                        <td style="text-align: right;"> <?php echo $opened_count1;?> </td>
                        <td style="text-align: right;"> <?php echo $inprogress_count1;?> </td>
                        <td style="text-align: right;"> <?php echo $closed_count1;?> </td>
                        
                    </tr>
                <?php
                }
        ?>
    </table>
   <?php
    }
  ?>  
    
</div>
</div>


<?php
include("footer.php");

?>
<script>
 
 $(document).ready(function(){ 
        $('#fromdate').datepicker({
            format: "dd-mm-yyyy",
            language:  'en',
            autoclose: 1
        }); 
        
        
    });
</script>   
