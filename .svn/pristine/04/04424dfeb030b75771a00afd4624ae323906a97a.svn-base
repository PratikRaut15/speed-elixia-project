<?php
include 'nomenclature_functions.php';

if(isset($_POST['nomensName']) && !isset($_POST['nid']))
{
    $nomensName         = GetSafeValueString($_POST['nomensName'],"string");
    $customerno         = $_SESSION['customerno'];
    $creted_on          = date('Y-m-d h:i:s');
    $created_by         = $_SESSION['userid'];
    $nomensExist        = checkNomensIfExist($nomensName,$customerno);
    
    if($nomensExist['countNomensVar']  == 0){
         $nomenId            = addNomens($nomensName, $customerno, $creted_on, $created_by);
        echo $nomenId;
    }
    else{
          return null;
    }
}
else if(isset($_POST['nomensName']) && isset($_POST['nid']))
{
    $nomensName          = GetSafeValueString($_POST['nomensName'],"string");
    $nid                 = $_POST['nid'];
    $isdeleted           = 0;
    $customerno          = $_SESSION['customerno'];
    $updated_on          = date('Y-m-d h:i:s');
    $updated_by          = $_SESSION['userid'];
    $status             = editNomens($nomensName,$nid, $customerno, $updated_on, $updated_by,$isdeleted);
    return $status;
}
?>