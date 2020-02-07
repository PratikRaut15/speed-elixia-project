<?php
/**
 * Sales view 
 */
?>
<?php 
    $saleviewdata = saleview($_SESSION['customerno'],$_SESSION['userid']);
    
?>

<br/>
<div class='container'>
<center>
 <table class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
    <thead>
        <tr><th colspan="100%">Sales Master</th></tr>
        <tr>
<!--        <th>Sale ID</th>-->
        <th>Sr Code</th>
        <th>Sr Name</th>
        <th>Phone</th>
        <th>Created Date</th>
<!--        <th>Updated By</th>
        <th>Updated Time</th>-->
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
   
    <?php
 if(isset($saleviewdata))
    {
    foreach($saleviewdata as $row)
    {
        $saleid = $row['salesid'];
        $entrytime1 = date("d-m-Y", strtotime($row['entrytime']));
        if($entrytime1=="01-01-1970"){
            $entry_date="-";
        }else{
            $entry_date=$entrytime1;
        }
        
        echo "<tr>";
        //echo "<td>".$row['salesid']."</td>";
        echo "<td>".$row['srcode']."</td>";
        echo "<td>".ucfirst($row['srname'])."</td>";
        echo "<td>".$row['phone']."</td>";
        echo "<td>".$entry_date."</td>";
        //echo "<td>".$row['updated_by_name']."</td>";
        //echo "<td>".$row['updatedtime']."</td>";
        $string= "&saleid=".$saleid;
        echo "<td><a  href = 'sales.php?pg=saleedit$string'> <i class='icon-pencil'></i> </a></td>";
        ?>
        <td style='width:30px;'><a  href = 'javascript:void(0);' title="Remove" onclick="deletesales(<?php echo $saleid;?>)"> <i class='icon-remove'></i> </a></td>
        <?php
        echo "</tr>";
    }
   } 
 else{
     echo 
     "<tr>
         <td colspan='100%'>No Sale Created</td>
    <tr>";
 }  

?>    
 </table>   
   </center> 
    
</div>
