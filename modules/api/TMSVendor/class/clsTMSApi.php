<?php

/**
 * Description of clsTMSApi
 *
 * @author Mrudang
 */
define('DB_HOST_MSSQL', '110.227.250.188');
define('DB_MSSQL', 'ECTestNew');
define('DB_LOGIN_MSSQL', 'elixia');
define('DB_PWD_MSSQL', 'elixia');
define('SP_EC_LOGIN_CHECK', 'EC_Login_Check');

require_once "database.inc.php";

class clsTMSApi {

    //<editor-fold defaultstate="collapsed" desc="Constructor">
    // construct
    function __construct() {
        $this->db = new database(DB_HOST_MSSQL, DB_LOGIN_MSSQL, DB_PWD_MSSQL, DB_MSSQL);
    }

    // </editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="API functions">
    // checks for login
    function login($objInputData) {
        $pdo = $this->db->CreatePDOConn();
        $divisionId = 1;
        $currentYear = date("Y");
        $previousYear = date("Y") - 1;
        $currentMonth = date("m");
        $yearCode = ($currentMonth <= "03") ? substr($previousYear, -2) : substr($currentYear, -2);
        $macId = "";
        $outputParams = "DECLARE    @Is_Valid_User bit
                                    ,@Is_User_Active bit
                                    ,@IsEmpDivValid bit
                                    ,@Is_Activate_Divisions bit
                                    ,@Is_Mac_Id_Found bit";
        $sp_params = "'" . $divisionId . "'"
                . ",'" . $yearCode . "'"
                . ",'" . $objInputData->username . "'"
                . ",'" . $objInputData->password . "'"
                . ",@Is_Valid_User OUTPUT"
                . ",@Is_User_Active OUTPUT"
                . ",@IsEmpDivValid OUTPUT"
                . ",@Is_Activate_Divisions OUTPUT"
                . ",'" . $macId . "'"
                . ",@Is_Mac_Id_Found OUTPUT";
        $outputParamsQuery = "SELECT	@Is_Valid_User as Is_Valid_User,
		@Is_User_Active as Is_User_Active,
		@IsEmpDivValid as IsEmpDivValid,
		@Is_Activate_Divisions as Is_Activate_Divisions,
		@Is_Mac_Id_Found as Is_Mac_Id_Found";
        echo $queryCallSP = $outputParams . "; EXEC " . SP_EC_LOGIN_CHECK . " $sp_params; " . $outputParamsQuery;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        prettyPrint($arrResult);
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        prettyPrint($outputResult);
        die();
        $this->db->ClosePDOConn($pdo);
        return $retarray;
    }

    // </editor-fold>
    // 
    //<editor-fold defaultstate="collapsed" desc="Helper functions">
    function failure($text) {
        return array('Status' => '0', 'Message' => $text);
    }

    function success($message, $result) {
        return array('Status' => '1', 'Message' => $message, 'Result' => $result);
    }

    // </editor-fold>
}
