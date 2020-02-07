<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");

$uid = GetSafeValueString(isset($_GET["uid"])? $_GET["uid"]:$_POST["uid"], "long");
// See if we need to save a new one.
$message="";
$db = new DatabaseManager();


if(isset($_POST["save"]))
{
   $commentr = GetSafeValueString($_POST["commentr"], "string");
   $uid = GetSafeValueString($_POST["uid"], "string");
        $SQL = sprintf("UPDATE unit SET comments_repair ='".$commentr."' where uid=$uid");    
         $db->executeQuery($SQL);
        header("Location: repairview.php");
}
$sql = sprintf("Select * from `unit` where uid=%d",$uid);
$db->executeQuery($sql);
if($db->get_rowCount()>0)
{
    while($row = $db->get_nextRow())
    {
      
       $commentr = $row['comments_repair'];

    }
}


include("header.php");
?>
<script>
    function deleteteam()
    {
        if(confirm("Delete this Customer?"))
        {
            $("form1").submit();
        }
    }
</script>
<div class="panel">
<div class="paneltitle" align="center">Add comment</div>
<div class="panelcontents">
    <form method="post" id="form1" name="form1"  action="add_comment_repair.php">
<?php if(!empty($message)){
    echo"<span style='color:red; font-size:10px;'>".$message."</span>";
} ?>
<input type="hidden" name = "uid" value="<?php echo($uid) ?>"/>
<table width="100%">
        <tr>
            <td>Comment</td><td><textarea name="commentr" id="commentr" style="width:150px;"><?php echo $commentr;?></textarea></td>
        </tr>
        
</table>
<input type="submit" name="save" value="Save Comment"/>
</form>
</div>
</div>

<?php 
include("footer.php");
?>
