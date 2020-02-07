<?php
include 'ecode_functions.php';
if (isset($_GET['update'])) {
    updateecode($_POST);
} elseif (isset($_GET['ecodeid'])) {
    $delete = deleteecode($_GET['ecodeid']);
    if ($delete) {
        header("Location:ecode.php?id=2");
    }
} elseif (isset($_POST)) {
    generateecode($_POST);
}
?>