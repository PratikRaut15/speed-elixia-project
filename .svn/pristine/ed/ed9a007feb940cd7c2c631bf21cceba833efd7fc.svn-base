<?php
if (isset($_GET['eid']) && $_GET['eid'] != '') {
    $delid = $_GET['eid'];
    $objRole = new Hierarchy();
    $objRole->roleid = $delid;
    $objRole->parentroleid = '';
    $objRole->moduleid = '1';
    $objRole->customerno = $_SESSION['customerno'];
    $recordAffected = deleteRole($objRole);
    if($recordAffected != 0){
        header("location: hierarchy.php?id=2");
    }
}

$objRole = new Hierarchy();
$objRole->roleid = '';
$objRole->parentroleid = '';
$objRole->moduleid = '2';
$objRole->customerno = $_SESSION['customerno'];
$roles = getRolesByCustomer($objRole);
//print_r($roles);
?>


<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
    <thead>

        <tr>
            <th>Sr. No</th>       
            <th>Role</th>
            <th>Parent</th>
            <th>Module</th>                            
            <th colspan="2">Options</th>                    
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($roles) && !empty($roles)) {
            $i = 1;
            foreach ($roles as $role) {
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $role[role] ?></td>
                    <td><?php echo $role[prole] ?></td>
                    <td><?php echo $role[modulename] ?></td>
                    <td><a href='hierarchy.php?id=3&eid=<?php echo $role[id] ?>'><img src='../../images/edit_black.png'/></a></td>
                    <td><a href='hierarchy.php?id=2&eid=<?php echo $role[id] ?>'><img src='../../images/delete1.png'/></a></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td clspan=100%> No Data Available</td></tr>";
        }
        ?>

    </tbody>
</table>