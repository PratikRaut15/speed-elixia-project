<?php// include 'panels/addstatus.php';?>
<form method="post" action="reasonmaster.php?id=1">
    
    <table style="width:40%;">
        <tr>
            <th colspan="100%" id="formheader">Add Reason</th>
        </tr>
        <tr>
          <td>Status</td>  
          <td><input type="text" id="reason" name="reason" size="35"/></td>  
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="save" value="Save" class="btn btn-primary" />
                <input type="reset" name="cancel" value="Cancel" class="btn btn-danger" />
            </td>
            
        </tr>
    </table>
    
</form>
<?php
if(isset($_POST['save']))
{
      if($_POST['reason'] == '')
      {
          echo "Reason can not be empty";
      }
      else
      {
          $result = add_reason($_POST['reason']);
          if($result == 'ok')
          {
              echo "Reason Added Successfully";
          }elseif($result =='notok')
          {
              echo "Reason already present, please enter another reason";
          }
          else{
              echo "Reason not added, please try again";
          }
      }
   
}
?>