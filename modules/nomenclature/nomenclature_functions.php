<?php 

if (!isset($_SESSION)) {
    session_start();
}
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
include_once '../../lib/bo/VehicleManager.php';

  function getNomensList($nid='0') {
       $VehicleManager 	= new VehicleManager($_SESSION['customerno']);
       $nomens 			= $VehicleManager->getNomensList($_SESSION['customerno'],$nid);
       return $nomens;
    }

	function addNomens($nomensName, $customerno, $created_on, $created_by){
		 $VehicleManager 		 	= new VehicleManager($_SESSION['customerno']);
		 $nomenobj 				 	= new stdClass();
		 $nomenobj->nomens 			= $nomensName;
		 $nomenobj->customerno 		= $customerno;
		 $nomenobj->created_on 		= $created_on;
		 $nomenobj->created_by 		= $created_by;
		 $nomens 					= $VehicleManager->addNomens($nomenobj);
		 return $nomens['varNomensid'];
	}

    function editNomens($nomensName,$nid, $customerno, $created_on, $created_by,$isdeleted){
		 $VehicleManager 		 	= new VehicleManager($_SESSION['customerno']);
		 $nomenobj 				 	= new stdClass();
		 $nomenobj->nomens 			= $nomensName;
		 $nomenobj->nid 			= $nid;
		 $nomenobj->isdeleted 		= $isdeleted;
		 $nomenobj->customerno 		= $customerno;
		 $nomenobj->updated_on 		= $created_on;
		 $nomenobj->updated_by 		= $created_by;
		 $nomens 					= $VehicleManager->editNomens($nomenobj);
		 return $nomens['varNomensid'];
	}
	function delNomens($nid){
		 $VehicleManager 		 	= new VehicleManager($_SESSION['customerno']);
		 $nomenobj 				 	= new stdClass();
		 $nomenobj->nid 			= $nid;
		 $nomenobj->nomens			= '';
		 $nomenobj->isdeleted 		= 1;
		 $nomenobj->customerno 		= $_SESSION['customerno'];
		 $nomenobj->updated_on 		= date('Y-m-d h:i:s');
		 $nomenobj->updated_by 		= $_SESSION['userid'];
		 $nomens 					= $VehicleManager->deleteNomens($nomenobj);
		 return $nomens;
	}
	function checkNomensIfExist($nomensName, $customerno){
		 $VehicleManager 		 	= new VehicleManager($_SESSION['customerno']);
		 $nomenobj 				 	= new stdClass();
		 $nomenobj->nomens 			= $nomensName;
		 $nomenobj->customerno 		= $customerno;
		 $nomens 					= $VehicleManager->checkNomensIfExist($nomenobj);
		 return $nomens;
	}
?>