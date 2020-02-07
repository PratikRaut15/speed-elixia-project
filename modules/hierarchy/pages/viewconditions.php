<?php
if (isset($_GET['eid']) && $_GET['eid'] != '') {
    $delid = $_GET['eid'];
    $objRole = new Hierarchy();
    $objRole->ruleid = $delid;
    $objRole->customerno = $_SESSION['customerno'];
    $recordAffected = deleteRule($objRole);
    if($recordAffected != 0){
        header("location: conditions.php?id=2");
    }
}

$objRole = new Hierarchy();
$objRole->customerno = $_SESSION['customerno'];
$objRole->ruleid = '';
$objRole->conditionid = '';
$rules = getTransactionRules($objRole);
//print_r($rules);
?>


<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
    <thead>

        <tr>
            <th>Sr. No</th>       
            <th>Transaction Type</th>
            <th>Condition</th>
            <th>Value</th>                            
            <th>Approver Role</th>                            
            <th>Priority</th>                            
            <th colspan="2">Options</th>                    
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($rules) && !empty($rules)) {
            $i = 1;
            foreach ($rules as $rule) {
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $rule[categoryname] ?></td>
                    <td><?php echo $rule[conditionname] ?></td>
                    <td><?php echo $rule[minval]." - ". $rule[maxval]?></td>
                    <td><?php echo $rule[role] ?></td>
                    <td><?php echo $rule[sequenceno] ?></td>
                    <td><a href='conditions.php?id=3&eid=<?php echo $rule[ruleid] ?>'><img src='../../images/edit_black.png'/></a></td>
                    <td><a href='conditions.php?id=2&eid=<?php echo $rule[ruleid] ?>'><img src='../../images/delete1.png'/></a></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td clspan=100%> No Data Available</td></tr>";
        }
        ?>

    </tbody>
</table>