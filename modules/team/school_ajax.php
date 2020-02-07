

<?php
include_once("../../lib/bo/SchoolManager.php");
    if(isset($_POST) && !empty($_POST)){
			$postedArray				= array();
			$postedArray['schoolid']    = $_POST['schoolid'];
		//	$postedArray['date'] 		= $_POST['date'];
			$postedArray['standard']  	= $_POST['standard'];
			$postedArray['division'] 	= $_POST['division'];
			/*To Get requested data*/
			$schoolmanager =   new SchoolManager();
			$studentlist   =   $schoolmanager->getStudentList($postedArray);
			return json_encode($studentlist);
    }
    ?>