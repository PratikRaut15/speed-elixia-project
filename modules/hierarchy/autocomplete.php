<?php include 'hierarchy_functions.php'; ?>
<?php

class autolist {
    
}

;
if ($_POST['dummydata'] == 'condition') {
    if ($_POST['q'] != '') {
        $q = '%' . $_POST['q'] . '%';
        $cnt = $_POST['cnt'];
       $typeid = $_POST['typeid'];
    }
    $objRoute = new Hierarchy();
    $objRoute->transactiontypeid = $typeid;
    $objRoute->customerno = $_SESSION["customerno"];
    $objRoute->conditionname = $q;
    $routes = getTrasactionConditions($objRoute);
    if ($routes) {
        $data = array();
        foreach ($routes as $row) {
            $route = new autolist();
            $route->conditionid = $row['conditionid'];
            $route->conditionname = $row['conditionname'];
            $data[] = $route;
            ?>
            <li onclick="fillcondition(<?php echo $route->conditionid; ?>, '<?php echo $route->conditionname ?>', <?php echo $cnt; ?>)" value="<?php echo $route->conditionid; ?>"><?php echo $route->conditionname; ?></li>
            <?php
        }
        //echo json_encode($data);
    }
} else if ($_POST['dummydata'] == 'approve') {
    if ($_POST['q'] != '') {
        $q = '%' . $_POST['q'] . '%';
        $cnt = $_POST['listid'];
        $typeid = $_POST['typeid'];
    }
    $objRoute = new Hierarchy();
    $objRoute->roleid = '';
    $objRoute->parentroleid = '';
    $objRoute->moduleid = '1';
    $objRoute->customerno = $_SESSION['customerno'];
    $routes = getRolesByCustomer($objRoute);
    if ($routes) {
        $data = array();
        foreach ($routes as $row) {
            $route = new autolist();
            $route->id = $row['id'];
            $route->role = $row['role'];
            $data[] = $route;
            ?>
            <li onclick="fillapprover(<?php echo $route->id; ?>, '<?php echo $route->role ?>', <?php echo $cnt; ?>)" value="<?php echo $route->id; ?>"><?php echo $route->role; ?></li>
            <?php
        }
        //echo json_encode($data);
    }
}else if ($_POST['dummydata'] == 'approve_list') {
    if ($_POST['q'] != '') {
        $q = '%' . $_POST['q'] . '%';
        
    }
    $objRoute = new Hierarchy();
    $objRoute->roleid = '';
    $objRoute->parentroleid = '';
    $objRoute->moduleid = '1';
    $objRoute->customerno = $_SESSION['customerno'];
    $routes = getRolesByCustomer($objRoute);
    if ($routes) {
        $data = array();
        foreach ($routes as $row) {
            $route = new autolist();
            $route->id = $row['id'];
            $route->role = $row['role'];
            $data[] = $route;
            ?>
            <li onclick="fill_approver(<?php echo $route->id; ?>, '<?php echo $route->role ?>')" value="<?php echo $route->id; ?>"><?php echo $route->role; ?></li>
            <?php
        }
        //echo json_encode($data);
    }
}
?>