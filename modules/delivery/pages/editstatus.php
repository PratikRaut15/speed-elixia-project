<?php
$status  = get_status_byid($_GET['sid']);
?>
<form method="post" action="statusmaster.php?id=3&sid=<?php echo $_GET['sid'];?>">
    
    <table style="width:40%;">
        <tr>
            <th colspan="100%" id="formheader">Add Status</th>
        </tr>
        <tr>
          <td>Status</td>  
          <td>
              <input type="text" id="status" name="status" size="35" value="<?php echo $status->status;?>"/>
              <input type="hidden" id="statusid" name="statusid" size="35" value="<?php echo $status->statusid;?>"/>
          </td>  
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="update" value="Modify" class="btn btn-primary" />
                <input type="reset" name="cancel" value="Cancel" class="btn btn-danger" />
            </td>
            
        </tr>
    </table>
    
</form>

<?php
if(isset($_POST['update']))
{
      if($_POST['status'] == '')
      {
          echo "Status can not be empty";
      }
      else
      {
          $result = edit_status_byid($_POST['status'], $_POST['statusid']);
          if($result == 'ok')
          {
              echo "Status Edited Successfully";
              header('location: statusmaster.php?id=2');
          }elseif($result =='notok')
          {
              echo "Status already present, please enter another status";
          }
          else{
              echo "Status not edited, please try again";
          }
      }
   
}
?>