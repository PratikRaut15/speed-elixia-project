
<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
    <thead>
   
    <tr>
       
        <th>Sr. No.</th>
        <th>Part Name</th>
        <th>Part Unit Amount</th>
        <th>Part Unit Discount</th>
        <th colspan="2">Options</th>
    </tr>
    </thead>
    <tbody>
<?php
    $parts = getpart(); 
    if(isset($parts))
    {	
        $x = 1;
        foreach($parts as $part)
        {
            echo "<tr>";
            echo "<td>$x</td>";
            echo "<td>".$part->part_name."</td>";
            echo "<td>".$part->unitamount."</td>";
            echo "<td>".$part->unitdiscount."</td>";
            if($part->customerno != 0){
            echo "<td><a href='parts.php?id=4&pid=$part->id' ><i class='icon-pencil'></i></a></td>";
            ?>
<!--        <td><a href = 'parts.php?id=4&did=<?php echo($part->id); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>        -->
            <td><a href ='javascript: void(0);' onclick='deletepart(<?php echo $part->id;?>);'><i class='icon-trash'></i></a></td>                
            <?php
            }
            else{
            echo "<td colspan='2'>---</td>";
            }
            echo "</tr>";
            $x++;
        }
    }
    else{
        echo 
        "<tr>
            <td colspan=100%>No Part Created</td>
        </tr>";
        }
?>
    </tbody>
</table>

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
   function deletepart(id){
     var result="";
    result = confirm("Are you sure you want to delete this part");
    if (result == true) {   
    var dataresult = "action=deletepart&id="+id;
    jQuery.ajax({
            url: "route.php",
            type: 'POST',
            cache: false,
            data: dataresult,
            success: function (statuscheck) {
                if (statuscheck == "notok") {
                    return false;
                    location.reload();   
                }
                else if (statuscheck == 'ok') {
                    location.reload();   
                }
            }
        });
    return true;
    }else{ 
        return false;
    }
   } 
</script>