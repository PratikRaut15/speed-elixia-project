<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
   $RELATIVE_PATH_DOTS = "../../";
}
include_once 'transaction_functions.php';
$work="";
$action="";
extract($_REQUEST);

if($action=='edittyre'){
    edittyrepopup($_POST);   
}elseif($action=='editbattery'){
  
    editbatterypop($_POST);
    
}elseif($action=='deleteparts'){
    
    deletepartspopup($partid);
    header('Location: transaction.php?id='.$id.'&accid='.$tid.'&vehicleid='.$vehicleid);    
    
}elseif($action=='deletetasks'){
   deletetaskspopup($taskid);
    header('Location: approvals.php?id='.$id.'&tid='.$tid);    
}elseif($action=='edittask'){
   $data = array();
    $data = array(
        'partid'=> $_POST['partid'],
        'partqty'=> $_POST['partqty'],
        'partamount'=> $_POST['partamount'],
        'partdisc'=> $_POST['partdisc'],
        'parttot'=> $_POST['parttot'],
        'pid' => $_POST['pid'],
        'getid' => $_POST['getid'],
        'tid' => $_POST['tid'],
        'action' => $_POST['action']
    );
   edittaskpopup($data);
  echo '1';
}elseif($action=='editparts'){
    $data = array();
    $data = array(
        'partid'=> $_POST['partid'],
        'partqty'=> $_POST['partqty'],
        'partamount'=> $_POST['partamount'],
        'partdisc'=> $_POST['partdisc'],
        'parttot'=> $_POST['parttot'],
        'pid' => $_POST['pid'],
        'getid' => $_POST['getid'],
        'tid' => $_POST['tid'],
        'action' => $_POST['action']
    );
    editpartpopup($data);
    //header("Location: approvals.php?id=".$data['getid']."&tid=".$data['tid']); 
    echo '1';
}elseif($action=="addPartspop"){
    $parts_select_array1 = $_POST['parts_select_array1'];
    $tid = $_POST['tid'];
    addpartspopup($parts_select_array1,$tid);
}elseif($action=="addTaskpop"){
    $tasks_select_array1 = $_POST['tasks_select_array1'];
    $tid = $_POST['tid'];
    addtaskspopup($tasks_select_array1,$tid);
}elseif($action=="vehicletransedit"){
    edittransdetailsvehicle($_REQUEST);
}elseif($action=="edittransdetails"){
    updateTransdetails($_REQUEST);
}elseif($action=="editqtn"){
    updateqtndetails($_REQUEST,$_FILES);
}elseif($action=="taxedit"){
    updatetax($_REQUEST);
}elseif($action=="invoiceamtedit"){
    updateinvoice($_REQUEST);
}
?>
