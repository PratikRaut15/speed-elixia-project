<?php

error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once ("../../lib/system/utilities.php");
include_once ("../../lib/system/Sanitise.php");

class ContactAjax {
    
}

$db = new DatabaseManager();

if (isset($_POST['cno'])) {
    $custno = GetSafeValueString($_POST['cno'], "string");
    $vodatas = Array();
    $SQL = sprintf("SELECT *,cm.person_typeid,cm.person_type FROM  ".DB_PARENT.".contactperson_details cd
            INNER JOIN ".DB_PARENT.".contactperson_type_master as cm ON cd.typeid = cm.person_typeid
            WHERE customerno = %d AND isdeleted = 0
            ", $custno);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $x = 1;
        while ($row = $db->get_nextRow()) {
            $vodata = new ContactAjax();
            $vodata->x = $x++;
            $vodata->cpid = $row['cpdetailid'];
            $vodata->type = $row['person_type'];
            $vodata->person_name = $row['person_name'];
            $vodata->email1 = $row['cp_email1'];
            $vodata->email2 = $row['cp_email2'];
            $vodata->phone1 = $row['cp_phone1'];
            $vodata->phone2 = $row['cp_phone2'];
            $vodata->cust = $row['customerno'];
            $vodatas[] = $vodata;
        }
    }
    echo json_encode($vodatas);
}

if (isset($_POST['add_details'])) {
    $today = date('Y-m-d H:i:s');
    $type = GetSafeValueString($_POST['type'], "string");
    $person = GetSafeValueString($_POST['person'], "string");
    $email1 = GetSafeValueString($_POST['email1'], "string");
    $email2 = GetSafeValueString($_POST['email2'], "string");
    $phone1 = GetSafeValueString($_POST['phone1'], "string");
    $phone2 = GetSafeValueString($_POST['phone2'], "string");
    $customerno = GetSafeValueString($_POST['cid'], "string");

    $SQL = sprintf("INSERT INTO ".DB_PARENT.".contactperson_details (typeid,person_name,cp_email1,cp_email2,cp_phone1,cp_phone2,customerno,insertedby,insertedon)
            VALUES(%d, '%s', '%s' , '%s' , '%s' , '%s' , %d , %d , '%s')"
            , Sanitise::Long($type), Sanitise::String($person), Sanitise::String($email1), Sanitise::String($email2), Sanitise::String($phone1), Sanitise::String($phone2), Sanitise::Long($customerno), GetLoggedInUserId(), Sanitise::DateTime($today));
    $db->executeQuery($SQL);

    //header('Location:contactdetails.php?cno=' . $customerno);
    header('Location:modifycustomer.php?cid=' . $customerno);
}

if (isset($_GET['delcpid']) && isset($_GET['cust'])) {
    $today = date('Y-m-d H:i:s');
    $cpid = GetSafeValueString($_GET['delcpid'], "string");
    $customerno = GetSafeValueString($_GET['cust'], "string");
    $SQL = sprintf("UPDATE ".DB_PARENT.".contactperson_details SET isdeleted = 1 , updatedby = %d ,updatedon = '%s' WHERE cpdetailid = %d AND customerno = %d", GetLoggedInUserId(), Sanitise::DateTime($today), $cpid, $customerno);
    $db->executeQuery($SQL);

    //header('Location:contactdetails.php?cno=' . $customerno);
    header('Location:modifycustomer.php?cid=' . $customerno);
}



