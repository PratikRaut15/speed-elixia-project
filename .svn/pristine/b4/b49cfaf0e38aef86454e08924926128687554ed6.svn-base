<?php
include_once "session.php";
include "loginorelse.php";
include_once "db.php";
include_once "../../constants/constants.php";
include_once "../../lib/system/utilities.php";
include_once "../../lib/autoload.php";
include_once "../../lib/system/DatabaseManager.php";
include_once "../../lib/components/gui/datagrid.php";
include_once "../../lib/components/gui/objectdatagrid.php";

//<editor-fold defaultstate="collapsed" desc="Page Actions">
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "addCategory") {
    $objCategory = new stdClass();
    $objCategory->category = $_POST['category'];
    $objCategory->teamid = GetLoggedInUserId();
    //print_r($objCategory);
    $categoryId = insertCategory($objCategory);
    if($categoryId > 0) {
        header("Location: category.php");
    }
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "editCategory") {
    $objCategory = new stdClass();
    $objCategory->categoryid = $_POST['categoryid'];
    $objCategory->category = $_POST['category'];
    $objCategory->teamid = GetLoggedInUserId();
    //print_r($objCategory);
    $noOfAffectedRows = updateCategory($objCategory);
    header("Location: category.php");


}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "deleteCategory") {
    $objCategory = new stdClass();
    $objCategory->categoryid = $_GET['cid'];
    $objCategory->teamid = GetLoggedInUserId();
    //print_r($objCategory);
    $noOfAffectedRows = deleteCategory($objCategory);

    header("Location: category.php");

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "addBankStatement") {
    $objStatement = new stdClass();
    $objStatement->transaction_datetime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($_POST['transaction_date']." ".$_POST['transaction_time'].":00"));
    $objStatement->details = $_POST['details'];
    $objStatement->remarks = $_POST['remarks'];
    $objStatement->transaction_type = $_POST['transaction_type'];
    $objStatement->categoryid = $_POST['category'];
    $objStatement->amount = $_POST['amount'];
    $objStatement->teamid = GetLoggedInUserId();

    $statementId = insertBankStatement($objStatement);
    if($statementId > 0) {
        header("Location: bankstatement.php");
    }
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "editBankStatement") {
    $objStatement = new stdClass();
    $objStatement->statementid = $_POST['statementid'];
    $objStatement->transaction_datetime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($_POST['transaction_date']." ".$_POST['transaction_time'].":00"));
    $objStatement->details = $_POST['details'];
    $objStatement->remarks = $_POST['remarks'];
    $objStatement->transaction_type = $_POST['transaction_type'];
    $objStatement->categoryid = $_POST['category'];
    $objStatement->amount = $_POST['amount'];
    $objStatement->teamid = GetLoggedInUserId();

    $noOfAffectedRows = updateBankStatement($objStatement);
    header("Location: bankstatement.php");
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "deleteBankStatement") {
     $objStatement = new stdClass();
    $objStatement->statementid = $_GET['statementid'];
    $objStatement->teamid = GetLoggedInUserId();
    //print_r($objCategory);
    $noOfAffectedRows = deleteBankStatement($objStatement);

    header("Location: bankstatement.php");

}


if (isset($_REQUEST['action']) && $_REQUEST['action'] == "addToTally") {
    $objStatement = new stdClass();
    $objStatement->statementid = $_POST['statementid'];
    $objStatement->teamid = GetLoggedInUserId();
    $noOfAffectedRows = addStatementToTally($objStatement);
    //header("Location: bankstatement.php");
    echo $noOfAffectedRows;

}


//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Cashflow Functions">
function insertCategory($objCategory) {
    $objTeamManager = new TeamManager();
    $categoryId = $objTeamManager->insertCategory($objCategory);
    return $categoryId;
}

function updateCategory($objCategory) {
    $objTeamManager = new TeamManager();
    $noOfAffectedRows = $objTeamManager->updateCategory($objCategory);
    return $noOfAffectedRows;
}

function deleteCategory($objCategory) {
    $objTeamManager = new TeamManager();
    $noOfAffectedRows = $objTeamManager->deleteCategory($objCategory);
    return $noOfAffectedRows;
}

function getCategory($objCategory) {
    $objTeamManager = new TeamManager();
    $arrResult = $objTeamManager->getCategory($objCategory);
    return $arrResult;
}

function insertBankStatement($objStatement) {
    $objTeamManager = new TeamManager();
    $statementId = $objTeamManager->insertBankStatement($objStatement);
    return $statementId;
}

function updateBankStatement($objStatement) {
    $objTeamManager = new TeamManager();
    $noOfAffectedRows = $objTeamManager->updateBankStatement($objStatement);
    return $noOfAffectedRows;
}

function deleteBankStatement($objStatement) {
    $objTeamManager = new TeamManager();
    $noOfAffectedRows = $objTeamManager->deleteBankStatement($objStatement);
    return $noOfAffectedRows;
}

function getBankStatement($objStatement) {
    $objTeamManager = new TeamManager();
    $arrResult = $objTeamManager->getBankStatement($objStatement);
    return $arrResult;
}

function addStatementToTally($objStatement){
    $objTeamManager = new TeamManager();
    $arrResult = $objTeamManager->addStatementToTally($objStatement);
    return $arrResult;
}
//</editor-fold>

?>
