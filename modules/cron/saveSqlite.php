<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
require_once 'files/push_sqlite.php';

set_time_limit(0);
ini_set('memory_limit', '2048M');
$cm = new CustomerManager();
//date_default_timezone_set("Asia/Calcutta");
if (isset($_REQUEST['date']) && $_REQUEST['date'] != '') {
	$date = $_REQUEST['date'];
} else {
	//die('Enter Date');
	$dt = date('Y-m-d');
	$date = date('Y-m-d', strtotime("-1 days", strtotime($dt)));
}

if (isset($_REQUEST['customerno']) && $_REQUEST['customerno'] != '') {
	$customernos = array($_REQUEST['customerno']);
} else {

	$customernos = $cm->getcustomernos();
}

$dateTime = $date;

if (isset($customernos)) {
	foreach ($customernos as $thiscustomerno) {
		//$thiscustomerno = '10';
		$timezone = $cm->timezone_name_cron_savesqlite('Asia/Kolkata', $thiscustomerno);
		date_default_timezone_set('' . $timezone . '');
		$cqm = new ComQueueManager($thiscustomerno);
		$dataqueues = $cqm->GetHistory($thiscustomerno, $dateTime);
		if (isset($dataqueues) && !empty($dataqueues)) {
			echo "*************** Com History Start *************<br/>";
			foreach ($dataqueues as $dataqueue) {
				$returns = '';
				$exec = CHSqlite($dataqueue->cqhid, $dataqueue->cqid, $dataqueue->customerno, $dataqueue->userid, $dataqueue->type, $dataqueue->enh_checkpointid, $dataqueue->timesent, $dateTime);
				//print_r($exec[1]);
				if ($exec[1] != '' || $exec[2] != '') {
					$returns = 'error';
					break;
				}
				$returns = 'delete';
			}
			if ($returns == 'delete') {
				$cqm->DeleteHistory($thiscustomerno, $dateTime);
				echo 'Success-History<br/>';
			} elseif ($returns == 'error') {
				echo print_r($exec);
			}

			echo "*************** Com History End *************<br/>";
		} else {
			echo "*************** Data Not Available For Com History *************<br/>";
		}

		$loginhistory = $cqm->GetLoginHistory($thiscustomerno, $dateTime);
		if (isset($loginhistory) && !empty($loginhistory)) {
			echo "*************** Login History Start *************<br/>";
			foreach ($loginhistory as $loginqueue) {
				$returns = '';
				$exec = Loginsqlite($loginqueue->logHistoryId, $loginqueue->page_master_id, $loginqueue->type, $loginqueue->customerno, $loginqueue->created_on, $loginqueue->created_by, $dateTime);
				//print_r($exec[1]);
				if ($exec[1] != '' || $exec[2] != '') {
					$returns = 'error';
					break;
				}
				$returns = 'delete';
			}
			if ($returns == 'delete') {
				$cqm->DeleteLoginHistory($thiscustomerno, $dateTime);
				echo 'Success-LoginHistory<br/>';
			} elseif ($returns == 'error') {
				echo print_r($exec);
			}

			echo "*************** Login History End *************<br/>";

		} else {
			echo "*************** Data Not Available For Login History *************<br/>";
		}

		$comdataqueues = $cqm->getcomqueue($thiscustomerno, $dateTime);
		if (isset($comdataqueues) && !empty($comdataqueues)) {
			echo "*************** Comqueue Start *************<br/>";
			foreach ($comdataqueues as $comdataqueue) {
				$returnsq = '';
				$execq = ComQueueSqlite($comdataqueue->customerno, $comdataqueue->cqid, $comdataqueue->vehicleid, $comdataqueue->lat, $comdataqueue->long, $comdataqueue->type, $comdataqueue->timeadded, $comdataqueue->status, $comdataqueue->message, $comdataqueue->processed, $comdataqueue->is_shown, $comdataqueue->chkid, $comdataqueue->fenceid, $dateTime);
				//print_r($exec[1]);
				if ($execq[1] != '' || $execq[2] != '') {
					$returnsq = 'error';
					break;
				}
				$returnsq = 'delete';
			}
			if ($returnsq == 'delete') {
				$cqm->DeleteComQueue($thiscustomerno, $dateTime);
				echo $thiscustomerno . '-Success-Comqueue<br/>';
			} elseif ($returnsq == 'error') {
				echo print_r($execq);
			}

			echo "*************** Comqueue Start *************<br/>";
		} else {
			echo "*************** Data Not Available For Comqueue *************<br/>";
		}
		/*
			        $simdataqueues = $cqm->getsimdata($thiscustomerno, $dateTime);
			        if (isset($simdataqueues)) {
			            foreach ($simdataqueues as $simdataqueue) {
			                $returnsd = '';
			                $execsd = SimdataSqlite($simdataqueue->customerno, $simdataqueue->id, $simdataqueue->type, $simdataqueue->phoneno, $simdataqueue->message, $simdataqueue->requesttime, $simdataqueue->client, $simdataqueue->lat, $simdataqueue->long, $simdataqueue->system_msg, $simdataqueue->vehicleid, $simdataqueue->success, $simdataqueue->is_processed,$dateTime);
			                //print_r($exec[1]);
			                if ($execsd[1] != '' || $execsd[2] != '') {
			                    $returnsd = 'error';
			                    break;
			                }
			                $returnsd = 'delete';
			            }
			            if ($returnsd == 'delete') {
			                $cqm->DeleteSimdata($thiscustomerno, $dateTime);
			                echo 'Success-Simdata<br/>';
			            } elseif ($returnsd == 'error') {
			                echo print_r($execsd);
			            }
			        }
		*/
	}
}
