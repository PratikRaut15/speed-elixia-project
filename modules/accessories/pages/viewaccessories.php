
<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
    <thead>
   
    <tr>
       
        <th>Sr. No.</th>
        <th>Accessory</th>
        <th>Max. Permissible Amount</th>        
        <th colspan="2">Options</th>
    </tr>
    </thead>
    <tbody>
<?php
    $accs = getaccessories();    
    if(isset($accs))
    {	
        $x = 1;
        foreach($accs as $acc)
        {
            echo "<tr>";
            echo "<td>$x</td>";
            echo "<td>$acc->name</td>";
            echo "<td>$acc->max_amount</td>";            
            if($acc->customerno != 0){
            echo "<td><a href='accessories.php?id=4&tid=$acc->id' ><i class='icon-pencil'></i></a></td>";
            ?>
                <td><a href = 'accessories.php?id=4&did=<?php echo($acc->id); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>                
            <?php
            }
            else{
            echo "<td colspan='2'>---</td>";
            }
            echo "</tr>";
            $x++;
        }
    }
    else
        echo 
        "<tr>
            <td colspan=100%>No Accessories Created</td>
        </tr>";
?>
    </tbody>
</table>
<form style="width: 48%;" class="simple_form adminform form-horizontal" id="add_accessory" name="add_accessory" action="route.php" method="POST">
    <span id="problem" style="display: none; color: #FF0000">Please enter Accessory Name</span>
    <span id="problem_2" style="display: none; color: #FF0000">Please enter Maximum Permissible Amount</span>    
    <div class="control-group string required">
        <label class="string required control-label" for="service_accessory_name">
            Accessory Name</label><span>
        <div class="controls">
            <input class="string required" id="accessoryname" name="accessoryname" type="text">
         </div>
        <br/>
        <label class="string required control-label" for="service_accessory_amount">
        Max. Permissible Amount</label>
        <div class="controls">
            <input class="string required" id="amountname" name="amountname" type="text">
    </div>
    <div class="form-actions">
        <input type="button" name="adduserdetails" class="btn  btn-primary" value="Add Accessory" onclick="submitaccessory();">
    </div>
</form>
<style>
.form-horizontal .control-label {
    float: left;
    width: 160px;
    padding-top: 5px;
    text-align: right;
}
.adminform {
padding: 7px 14px;
margin: 10px 0 10px;
list-style: none;
background-color: #fbfbfb;
background-image: -moz-linear-gradient(top, #fff, #f5f5f5);
background-image: -ms-linear-gradient(top, #fff, #f5f5f5);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fff), to(#f5f5f5));
background-image: -webkit-linear-gradient(top, #fff, #f5f5f5);
background-image: -o-linear-gradient(top, #fff, #f5f5f5);
background-image: linear-gradient(top, #fff, #f5f5f5);
background-repeat: repeat-x;
border: 1px solid #ddd;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffff', endColorstr='#f5f5f5', GradientType=0);
-webkit-box-shadow: inset 0 1px 0 #ffffff;
-moz-box-shadow: inset 0 1px 0 #ffffff;
box-shadow: inset 0 1px 0 #ffffff;
}
</style>
<script>
function submitaccessory()
{
    if(jQuery("#accessoryname").val() == "")
    {
        jQuery("#problem").show();
        jQuery("#problem").fadeOut(6000);                 
    }
    else if(jQuery("#amountname").val() == "")
    {
        jQuery("#problem_2").show();
        jQuery("#problem_2").fadeOut(6000);                 
    }    
    else
    {
        jQuery("#add_accessory").submit();
    }
}
</script>