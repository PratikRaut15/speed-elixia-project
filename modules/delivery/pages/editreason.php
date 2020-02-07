<?php
$status  = get_reason_byid($_GET['sid']);
?>
<form method="post" action="reasonmaster.php?id=3&sid=<?php echo $_GET['sid'];?>">
    
    <table style="width:40%;">
        <tr>
            <th colspan="100%" id="formheader">Add Status</th>
        </tr>
        <tr>
          <td>Status</td>  
          <td>
              <input type="text" id="reason" name="reason" size="35" value="<?php echo $status->status;?>"/>
              <input type="hidden" id="reasonid" name="reasonid" size="35" value="<?php echo $status->statusid;?>"/>
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
      if($_POST['reason'] == '')
      {
          echo "Reason can not be empty";
      }
      else
      {
          $result = edit_reason_byid($_POST['reason'], $_POST['reasonid']);
          if($result == 'ok')
          {
              echo "Reason Edited Successfully";
              header('location: reasonmaster.php?id=2');
          }elseif($result =='notok')
          {
              echo "Reason already present, please enter another reason";
          }
          else{
              echo "Reason not edited, please try again";
          }
      }
   
}
?>