<?php include 'panels/addstatus.php';?>
<form method="post" action="statusmaster.php?id=1">
    
    <table style="width:40%;">
        <tr>
            <th colspan="100%" id="formheader">Add Status</th>
        </tr>
        <tr>
          <td>Status</td>  
          <td><input type="text" id="status" name="status" size="35"/></td>  
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
      if($_POST['status'] == '')
      {
          echo "Status can not be empty";
      }
      else
      {
          $result = add_status($_POST['status']);
          if($result == 'ok')
          {
              echo "Status Added Successfully";
          }elseif($result =='notok')
          {
              echo "Status already present, please enter another status";
          }
          else{
              echo "Status not added, please try again";
          }
      }
   
}
?>