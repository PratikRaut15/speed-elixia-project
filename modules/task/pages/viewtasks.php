<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
        <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Task Name</th>
            <th>Unit Amount</th>
            <th>Unit Discount</th>
            <th colspan="2">Options</th>
        </tr>
        </thead>
    <tbody>
    <?php
    $tasks = gettask();    
    if(isset($tasks))
    {	
        $x = 1;
        foreach($tasks as $task)
        {
            echo "<tr>";
            echo "<td>$x</td>";
            echo "<td>$task->task_name</td>";
            echo "<td>$task->unitamount</td>";
            echo "<td>$task->unitdiscount</td>";
            if($task->customerno != 0){
            echo "<td><a href='task.php?id=4&tid=$task->id' ><i class='icon-pencil'></i></a></td>";
            ?>
                <!--<td><a href ='task.php?id=4&did=<?php echo($task->id); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>-->
                <td><a href ='javascript: void(0);' onclick='deletetasks(<?php echo $task->id;?>);'><i class='icon-trash'></i></a></td>                
            <?php
            }
            else{
            echo "<td colspan='2'>---</td>";
            }
            echo "</tr>";
            $x++;
        }
    }else{
            echo "<tr> <td colspan=100%>No Task Created</td></tr>";
    }    
?>
    </tbody>
</table>
<script>
   function deletetasks(id){
     var result="";
    result = confirm("Are you sure you want to delete this Task");
    if (result == true) {   
    var dataresult = "action=deletetask&id="+id;
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
