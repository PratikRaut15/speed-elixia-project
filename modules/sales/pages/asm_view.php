<?php
/**
 * ASM view 
 */
?>
<?php 
    $asmviewdata = asmview($_SESSION['customerno'],$_SESSION['userid']);
    
?>

<br/>
<div class='container'>
<center>
 <table class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
    <thead>
        <tr><th colspan="100%">ASM Master</th></tr>
        <tr>
<!--        <th>ASM ID</th>-->
<!--        <th>State</th>-->
        <th>ASM Name</th>
        <th>State Name</th>
        <th>Created Date</th>
<!--        <th>Updated By</th>
        <th>Updated Time</th>-->
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
   
    <?php
 if(isset($asmviewdata))
    {
    foreach($asmviewdata as $row)
    {
        $asmid = $row['asmid'];
        $entrytime1 = date("d-m-Y", strtotime($row['entrytime']));
        if($entrytime1=="01-01-1970"){
            $entry_date="-";
        }else{
            $entry_date=$entrytime1;
        }
        
        echo "<tr>";
//        echo "<td>".$row['asmid']."</td>";
        echo "<td>".ucfirst($row['asmname'])."</td>";
        echo "<td>".ucfirst($row['statename'])."</td>";
        //echo "<td>".$row['addedby_name']."</td>";
        echo "<td>".$entry_date."</td>";
        //echo "<td>".$row['updated_by_name']."</td>";
        //echo "<td>".$row['updatedtime']."</td>";
        $string= "&asmid=".$asmid;
        echo "<td><a style='width:30px;' href = 'sales.php?pg=asmedit$string'> <i class='icon-pencil'></i> </a></td>";
        ?>
        <td style='width:30px;'><a  href = 'javascript:void(0);' title="Remove" onclick="deleteasm(<?php echo $asmid;?>)"> <i class='icon-remove'></i> </a></td>
        <?php
        echo "</tr>";
    }
   } 
 else{
     echo 
     "<tr>
         <td colspan='100%'>No ASM Created</td>
    <tr>";
 }  

?>    
 </table>   
   </center> 
    
</div>
