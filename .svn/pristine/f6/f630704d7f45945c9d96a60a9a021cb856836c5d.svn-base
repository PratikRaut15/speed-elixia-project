<?php 
$checkpoint_name='';
if($_SESSION['customerno']=='118'){
   $checkpoint_name = 'Circular Zone'; 
}else{
    $checkpoint_name = 'Checkpoint';
}
?>

<ul id="tabnav">
    <?php
    if (isset($_GET['id'])){
        if($_GET['id'] == 1){
            echo "<li><a class='selected' href='czone.php?id=1'>Create ".$checkpoint_name."</a></li>";
        }else{
            echo "<li><a href='czone.php?id=1'>Create ".$checkpoint_name."</a></li>";
        }
        if($_GET['id'] == 2){
            echo "<li><a class='selected' href='czone.php?id=2'>View ".$checkpoint_name."</a></li>";
        }else{
            echo "<li><a href='czone.php?id=2'>View ".$checkpoint_name."</a></li>";
        }
//        if ($_GET['id'] == 4) {
//            echo "<li><a class='selected' href='czone.php?id=4'>Upload ".$checkpoint_name."</a></li>";
//        }
//        else {
//            echo "<li><a href='czone.php?id=4'>Upload ".$checkpoint_name."</a></li>";
//        }
//        if ($_GET['id'] == 5 && $_SESSION["customerno"] == 328) {
//            echo "<li><a class='selected' href='czone.php?id=5'>Import ".$checkpoint_name." Data</a></li>";
//        }
//        else {
//            echo "<li><a href='czone.php?id=5'>Import ".$checkpoint_name." Data</a></li>";
//        }
        if($_GET['id'] == 3){
            echo "<li><a class='selected' href='czone.php?id=3&chkid=$_GET[chkid]'>Edit ".$checkpoint_name."</a></li>";
        }
    }
    else {
        echo "<li><a class='selected' href='czone.php?id=1'>Create ".$checkpoint_name."</a></li>";
        echo "<li><a href='czone.php?id=2'>View ".$checkpoint_name."</a></li>";
        echo "<li><a href='czone.php?id=4'>Upload ".$checkpoint_name."</a></li>";
        if ($_SESSION["customerno"] == 328) {
            echo "<li><a href='czone.php?id=5'>Import ".$checkpoint_name." Data</a></li>";
        }
    }
    ?>
</ul>
<?php
include 'checkpoint_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1){
    include 'pages/createchk.php';
}
elseif ($_GET['id'] == 2) {
    include 'pages/viewchk.php';
}
elseif ($_GET['id'] == 3) {
    include 'pages/editchk.php';
}
elseif ($_GET['id'] == 4) {
    include 'pages/uploadchk.php';
}
elseif ($_GET['id'] == 5) {
    include 'pages/importChkData.php';
}
?>
