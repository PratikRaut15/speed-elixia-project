<?php

/* * ***************************************************************************
  Database Class for MySQL Server. Please do not change anything
 * *************************************************************************** */

class database {

    protected $Con;
    private $_dsn = null;
    private $_username = null;
    private $_password = null;

    public function __construct($database_host, $database_user, $database_password, $database_name) {
        $this->_username = $database_user;
        $this->_password = $database_password;
        //use any of these or check exact MSSQL ODBC drivername in "ODBC Data Source Administrator"
        $mssqldriver = '{SQL Server}';
        $this->_dsn = 'odbc:driver=' . $mssqldriver . ';server=' . $database_host . ';database=' . $database_name . '';
        //$this->_dsn = 'sqlsrv:server=' . $database_host . ',1433;database=' . $database_name . ';ConnectionPooling=0';
        //$this->_dsn = 'mssql:server=' . $database_host . ',1433;database=' . $database_name . ';';
    }

    public function __destruct() {
        $this->_dsn = null;
    }

    // <editor-fold defaultstate="collapsed" desc="PDOConn">
    public function CreatePDOConn() {
        $pdo = new PDO($this->_dsn, $this->_username, $this->_password);
        return $pdo;
    }

    public function ClosePDOConn($pdo) {
        $pdo = NULL;
    }

    // </editor-fold>
}
?>