<?php

require_once '../../lib/system/Validator.php';
require_once '../../lib/system/DatabaseManager.php';
require_once '../../lib/system/Sanitise.php';
require_once '../../lib/system/Date.php';
require_once '../../lib/model/VOUser.php';
require_once '../../config.inc.php';

class SchoolManager {

    private $_databaseManager = null;
    private $_customerManager = null;

    function __construct() {
        // Constructor
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function getSchoolList() {
        $Query = "SELECT * FROM schools WHERE status = 0 ORDER BY schoolname";
        $this->_databaseManager->executeQuery($Query);
         $schoolList =  array();
                $schoolArray =  array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
               
                $schoolList['schoolname'] = $row['schoolname'];
                $schoolList['schoolid'] = $row['schoolid'];
                $schoolArray[]= $schoolList;
                    //    $schoolList = $row[''];
            }
            return $schoolArray;
            }
    }

    public function getStudentList($postedArray){
             $Query = sprintf("SELECT students.*,schools.schoolname FROM students 
            LEFT JOIN schools ON schools.schoolid=students.schoolid
                            WHERE students.status = %d AND students.schoolid = %d AND students.standard = %d AND students.division = '%s' "
                            ,0,Sanitise::Long($postedArray['schoolid']),Sanitise::String($postedArray['standard']),Sanitise::string($postedArray['division']));
      $this->_databaseManager->executeQuery($Query);
                $studentList =  array();
                $studentsArray =  array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $studentList['rollno']          = $row['rollno'];
                $studentList['studentname']     = $row['studentname'];
                $studentList['schoolname']      = $row['schoolname'];
                if(isset($row['checkin']) && !empty($row['checkin'])){
                    $studentList['present']         = 'Yes';
                }
                else{
                $studentList['present']         = 'No';
                }
               /* $studentList['checkin']         = $row['checkin'];
                $studentList['checkout']        = $row['checkout'];*/
                $studentList['parentname']      = $row['parentname'];
                $studentList['parentphone']     = $row['parentphone'];
                $studentList['standard']        = $row['standard'];
                $studentList['division']        = $row['division'];
                if($row['smsAlert']==0){
                    $studentList['smsAlert']        ='Sent';
                }
                else{
                     $studentList['smsAlert']        = 'Not Sent';
                }
                /* if($row['smsAlert']==0){
                    $studentList['smsAlert']        = '<img src="/opt/lampp/htdocs/speed/images/delete.png">';
                }
                else{
                     $studentList['smsAlert']        = '<&lt;span class="glyphicon glyphicon-remove"&gt;&lt;/span&gt;';
                }   */
               
                $studentsArray[]= $studentList;
            }

            echo json_encode($studentsArray);
            }
    }
}

?>