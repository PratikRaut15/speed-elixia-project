<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
   $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS.'lib/system/DatabaseManager.php';
class VersionedManager {

    protected $_databaseManager = null;
    protected $_Customerno = 0;

    function __construct($customerno) {
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
        $this->_Customerno = $customerno;
    }

    public function StartBatch() {

    }

    public function CompleteBatch() {

    }

    public function GetNextUpdateVersion() {

    }

}

?>