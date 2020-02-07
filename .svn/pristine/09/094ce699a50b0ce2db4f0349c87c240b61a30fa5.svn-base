<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');
set_time_limit(240);
include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once 'files/cronmail_helper.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
$cqm = new ComQueueManager();
$cronm = new CronManager();
$cvo = new VOComQueue();
$queues = $cqm->getcomqueuedata();
print_r($queues);
$cron_record_count = count($queues);
if (isset($queues)) {
	$firstObj = reset($queues);
	$lastObj = end($queues);
	if (isset($firstObj) && isset($lastObj)) {
		$cqm->markedQueued($firstObj->cqid, $lastObj->cqid);
	}
	foreach ($queues as $thisqueue) {
		$hms = date("H:i:s", strtotime($thisqueue->ctTimeZoneTimestamp));
		$fileid = '';
		$tripdetails = '';
		$location = '';
		$objType = getComQueType($thisqueue->type);
		$email = $objType->email;
		$sms = $objType->sms;
		$telephone = $objType->telephone;
		$mobile = $objType->mobile;
		$subject = $objType->subject;
		$dates = new Date();
		$encodekey = sha1($thisuser->userkey);
		$hourminutes = $dates->return_hours($thisqueue->timeadded);
		/* Trip Details for trip customers */
		if ($thisqueue->use_trip == 1) {
			$tripdetails = getTripdetails($thisqueue->vehicleid, $thisqueue->customerno);
		}
		/* Pull location if lat lng is present */
		if ($thisqueue->lat != 0 && $thisqueue->long != 0) {
			$location = 'Location: ' . location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
		}
		/* Email Template path and email message string */
		$email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
		if (file_exists($email_path)) {
			$emailmessage = file_get_contents($email_path);
			$emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
			$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
			$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
			$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
		} else {
			$emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
			$emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
			$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
			$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
			$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
		}
		/* SMS / Telephonic / Mobile Alerts */
		$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;

		if ($thisqueue->type == 9 || $thisqueue->type == 10) {
			$thisuser = $cronm->get_all_details($thisqueue->userid);
			if (
				(strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time))
				&& (strtotime($thisuser->start_alert_time) <= strtotime($hms)
					&& strtotime($hms) <= strtotime($thisuser->stop_alert_time))) {
				/* Email Notification */
				if (isset($thisuser->$email) && $thisuser->$email == 1 && isset($thisuser->email) && $thisuser->email != "") {
					if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
						$cvo->cqid = $thisqueue->cqid;
						$cvo->customerno = $thisqueue->customerno;
						$cvo->userid = $thisqueue->userid;
						$cvo->type = 0;
						$cvo->enh_checkpointid = 0;
						$cqm->InsertComHistory($cvo);
						$cqm->UpdateComQueue($thisqueue->cqid);
					}
				}
				/* SMS Notification */
				if (isset($thisuser->$sms) && $thisuser->$sms == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
					if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisqueue->userid) == true) {
						$cvo->cqid = $thisqueue->cqid;
						$cvo->customerno = $thisqueue->customerno;
						$cvo->userid = $thisqueue->userid;
						$cvo->type = 1;
						$cvo->enh_checkpointid = 0;
						$cqm->InsertComHistory($cvo);
						$cqm->UpdateComQueue($thisqueue->cqid);
					}
				}
				/* Telephonic Notification */
				if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
					if (telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
						$cvo->cqid = $thisqueue->cqid;
						$cvo->customerno = $thisqueue->customerno;
						$cvo->userid = $thisqueue->userid;
						$cvo->type = 2;
						$cvo->enh_checkpointid = 0;
						$cqm->InsertComHistory($cvo);
						$cqm->UpdateComQueue($thisqueue->cqid);
					}
				}
				/* Mobile App Notification */
				if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
					$vehicleid = $thisqueue->vehicleid;
					$cqm = new ComQueueManager();
					$gcmid = array($thisuser->gcmid);
					$message = array(
						"message" => $smsmessage,
						"vehicleid" => $vehicleid,
						"type" => $thisqueue->type,
					);
					$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
				}
			} elseif (
				(strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time))
				&& ((strtotime($thisuser->stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($thisuser->start_alert_time))
					|| (strtotime($thisuser->stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($thisuser->start_alert_time)))) {
				/* Email Notification */
				if (isset($thisuser->$email) && $thisuser->$email == 1 && isset($thisuser->email) && $thisuser->email != "") {
					if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
						$cvo->cqid = $thisqueue->cqid;
						$cvo->customerno = $thisqueue->customerno;
						$cvo->userid = $thisqueue->userid;
						$cvo->type = 0;
						$cvo->enh_checkpointid = 0;
						$cqm->InsertComHistory($cvo);
						$cqm->UpdateComQueue($thisqueue->cqid);
					}
				}
				/* SMS Notification */
				if (isset($thisuser->$sms) && $thisuser->$sms == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
					if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisqueue->userid) == true) {
						$cvo->cqid = $thisqueue->cqid;
						$cvo->customerno = $thisqueue->customerno;
						$cvo->userid = $thisqueue->userid;
						$cvo->type = 1;
						$cvo->enh_checkpointid = 0;
						$cqm->InsertComHistory($cvo);
						$cqm->UpdateComQueue($thisqueue->cqid);
					}
				}
				/* Telephonic Notification */
				if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
					$fileid = '';
					if (telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
						$cvo->cqid = $thisqueue->cqid;
						$cvo->customerno = $thisqueue->customerno;
						$cvo->userid = $thisqueue->userid;
						$cvo->type = 2;
						$cvo->enh_checkpointid = 0;
						$cqm->InsertComHistory($cvo);
						$cqm->UpdateComQueue($thisqueue->cqid);
					}
				}
				/* Mobile App Notification */
				if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
					$vehicleid = $thisqueue->vehicleid;
					$cqm = new ComQueueManager();
					$gcmid = array($thisuser->gcmid);
					$message = array(
						"message" => $smsmessage,
						"vehicleid" => $vehicleid,
						"type" => $thisqueue->type,
					);
					$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
				}
			}
		} elseif ($thisqueue->type == 2) {
			$chkpoints = $cqm->get_enh_checkpoints($thisqueue->chkid, $thisqueue->vehicleid, $thisqueue->customerno);
			if (isset($chkpoints)) {
				foreach ($chkpoints as $chkpoint) {
					if ($chkpoint->com_type == 0) {
						if (sendMail($chkpoint->com_det, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
							$cvo->cqid = $thisqueue->cqid;
							$cvo->customerno = $thisqueue->customerno;
							$cvo->userid = 0;
							$cvo->type = 0;
							$cvo->enh_checkpointid = $chkpoint->enh_checkpointid;
							$cqm->InsertComHistory($cvo);
							$cqm->UpdateComQueue($thisqueue->cqid);
						}
					} elseif ($chkpoint->com_type == 1) {
						if (sendSMS($chkpoint->com_det, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisqueue->userid) == true) {
							$cvo->cqid = $thisqueue->cqid;
							$cvo->customerno = $thisqueue->customerno;
							$cvo->userid = 0;
							$cvo->type = 1;
							$cvo->enh_checkpointid = $chkpoint->enh_checkpointid;
							$cqm->InsertComHistory($cvo);
							$cqm->UpdateComQueue($thisqueue->cqid);
						}
					} elseif ($chkpoint->com_type == 2) {
						if (telephonic_Alert($chkpoint->com_det, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
							$cvo->cqid = $thisqueue->cqid;
							$cvo->customerno = $thisqueue->customerno;
							$cvo->userid = 0;
							$cvo->type = 2;
							$cvo->enh_checkpointid = $chkpoint->enh_checkpointid;
							$cqm->InsertComHistory($cvo);
							$cqm->UpdateComQueue($thisqueue->cqid);
						}
					}
				}
			} else {
				$um = new UserManager();
				$users = $um->getusersforcustomerbytype($thisqueue->customerno, $thisqueue->type);
				if (isset($users)) {
					foreach ($users as $thisuser) {
						$groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
						if (isset($groups)) {
							foreach ($groups as $group) {
								$vehiclemanager = new VehicleManager($thisuser->customerno);
								$vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid, $thisqueue->vehicleid, $thisuser->id, $thisuser->roleid);
								if ($vehicles == true) {
									if ((strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time)) && (strtotime($thisuser->start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->stop_alert_time))) {
										/* Email Notification */
										if (isset($thisuser->$email) && $thisuser->$email == 1 && isset($thisuser->email) && $thisuser->email != "") {
											if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
												$cvo->cqid = $thisqueue->cqid;
												$cvo->customerno = $thisqueue->customerno;
												$cvo->userid = $thisuser->id;
												$cvo->type = 0;
												$cvo->enh_checkpointid = 0;
												$cqm->InsertComHistory($cvo);
												$cqm->UpdateComQueue($thisqueue->cqid);
											}
										}
										/* SMS Notification */
										if (isset($thisuser->$sms) && $thisuser->$sms == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
											if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id) == true) {
												$cvo->cqid = $thisqueue->cqid;
												$cvo->customerno = $thisqueue->customerno;
												$cvo->userid = $thisuser->id;
												$cvo->type = 1;
												$cvo->enh_checkpointid = 0;
												$cqm->InsertComHistory($cvo);
												$cqm->UpdateComQueue($thisqueue->cqid);
											}
										}
										/* Telephonic Notification */
										if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
											if (telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
												$cvo->cqid = $thisqueue->cqid;
												$cvo->customerno = $thisqueue->customerno;
												$cvo->userid = $thisuser->id;
												$cvo->type = 2;
												$cvo->enh_checkpointid = 0;
												$cqm->InsertComHistory($cvo);
												$cqm->UpdateComQueue($thisqueue->cqid);
											}
										}
										/* Mobile App Notification */
										if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
											$dates = new Date();
											$hourminutes = $dates->return_hours($thisqueue->timeadded);
											$vehicleid = $thisqueue->vehicleid;
											$cqm = new ComQueueManager();
											$gcmid = array($thisuser->gcmid);
											$message = array(
												"message" => $smsmessage,
												"vehicleid" => $vehicleid,
												"type" => $thisqueue->type,
											);
											$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
										}
									} else {
										if ((strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time)) && ((strtotime($thisuser->stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($thisuser->start_alert_time)) || (strtotime($thisuser->stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($thisuser->start_alert_time)))) {
											/* Email Notification */
											if (isset($thisuser->$email) && $thisuser->$email == 1 && isset($thisuser->email) && $thisuser->email != "") {
												if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 0;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
											}
											/* SMS Notification */
											if (isset($thisuser->$sms) && $thisuser->$sms == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
												if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id) == true) {
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 1;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
											}
											/* Telephonic Notification */
											if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
												if (telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 2;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
											}
											/* Mobile App Notification */
											if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
												$dates = new Date();
												$hourminutes = $dates->return_hours($thisqueue->timeadded);
												$vehicleid = $thisqueue->vehicleid;
												$cqm = new ComQueueManager();
												$gcmid = array($thisuser->gcmid);
												$message = array(
													"message" => $smsmessage,
													"vehicleid" => $vehicleid,
													"type" => $thisqueue->type,
												);
												$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		} elseif ($thisqueue->type == 17) {
			$um = new UserManager();
			$users = $um->getusersforcustomerbytype($thisqueue->customerno, $thisqueue->type, $thisqueue->vehicleid);
			if (!empty($users)) {
				$useradmin = $um->getadministrator($thisqueue->customerno);
				$users1 = array_merge($users, $useradmin);
				$arraylist = json_decode(json_encode($users1), True);
				$users2 = array_reduce($arraylist, function ($result, $currentItem) {
					if (isset($result[$currentItem['id']])) {
						$result[$currentItem['id']] = $currentItem;
					} else {
						$result[$currentItem['id']] = $currentItem;
					}
					return $result;
				});
				$userobject = json_decode(json_encode($users2), FALSE);
				if (isset($userobject)) {
					foreach ($userobject as $thisuser) {
						/* Email Notification */
						if (isset($thisuser->email) && $thisuser->email != "") {
							if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
								$cvo->cqid = $thisqueue->cqid;
								$cvo->customerno = $thisqueue->customerno;
								$cvo->userid = $thisuser->id;
								$cvo->type = 0;
								$cvo->enh_checkpointid = 0;
								$cqm->InsertComHistory($cvo);
								$cqm->UpdateComQueue($thisqueue->cqid);
							}
							if (doLogging == true) {
								sendEmailTologfreeze($emailmessage, $thisqueue->customerno);
							}
						}
						/* SMS Notification */
						if (isset($thisuser->phone) && $thisuser->phone != "") {
							$smsmessage = $thisqueue->message . ' at ' . $hourminutes . "." . $location;
							if ($thisqueue->type == '5' && $thisqueue->status == 1) {
								//no sms
							} else {
								if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
									$cvo->cqid = $thisqueue->cqid;
									$cvo->customerno = $thisqueue->customerno;
									$cvo->userid = $thisuser->id;
									$cvo->type = 1;
									$cvo->enh_checkpointid = 0;
									$cqm->InsertComHistory($cvo);
									$cqm->UpdateComQueue($thisqueue->cqid);
								}
							}
						}
						/* Telephonic Notification */
						if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
							$smsmessage = $thisqueue->message . ' at ' . $hourminutes . "." . $location;
							if ($thisqueue->type == '5' && $thisqueue->status == 1) {
								//no sms
							} else {
								$sendsms = telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
								$cvo->cqid = $thisqueue->cqid;
								$cvo->customerno = $thisqueue->customerno;
								$cvo->userid = $thisuser->id;
								$cvo->type = 2;
								$cvo->enh_checkpointid = 0;
								$cqm->InsertComHistory($cvo);
								$cqm->UpdateComQueue($thisqueue->cqid);
							}
						}
						/* Mobile App Notification */
						if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
							$dates = new Date();
							$hourminutes = $dates->return_hours($thisqueue->timeadded);
							$vehicleid = $thisqueue->vehicleid;
							$cqm = new ComQueueManager();
							$gcmid = array($thisuser->gcmid);
							$message = array(
								"message" => $smsmessage,
								"vehicleid" => $vehicleid,
								"type" => $thisqueue->type,
							);
							$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
						}
					}
				}
			}
		} elseif ($thisqueue->type == 8) {
			$um = new UserManager();
			$users = $um->getusersforcustomerbytype($thisqueue->customerno, $thisqueue->type, $thisqueue->vehicleid);
			if ($thisqueue->userid > 0 && !empty($users)) {
				$searchUser = $thisqueue->userid;
				$users = array_filter($users, function ($var) use ($searchUser) {
					return ($var->id == $searchUser);
				});
			}
			if (isset($users)) {
				$arrUnitIdWithBuzzCmdSet = array();
				$includeUser = TRUE; // Flag for users who wants alert when temperature comes in range after conflict or not
				$includeUser1 = FALSE; //Flag for users who wants alert for advance temperature settings
				foreach ($users as $thisuser) {
					$isTmpInrngeAlrtRqrd = isset($thisuser->isTmpInrngeAlrtRqrd) ? $thisuser->isTmpInrngeAlrtRqrd : 1;
					if ($isTmpInrngeAlrtRqrd == 0 && (strpos($thisqueue->message, 'is between') !== false)) {
						$includeUser = FALSE;
					}
					$isAdvTempConfRange = isset($thisuser->isAdvTempConfRange) ? $thisuser->isAdvTempConfRange : 0;
					if ($isAdvTempConfRange == 1) {
						$includeUser1 = TRUE;
					}
					if ($includeUser) {
						If ($includeUser1) {
							$isNomens = 0;
							if ($thisqueue->customerno == speedConstants::CUSTNO_PERKINELMER) {
								if ($thisqueue->n1 > 0) {
									$isNomens = 1;
								} elseif ($thisqueue->n2 > 0) {
									$isNomens = 1;
								} elseif ($thisqueue->n3 > 0) {
									$isNomens = 1;
								} elseif ($thisqueue->n4 > 0) {
									$isNomens = 1;
								}
								$unitNomens = array(
									$thisqueue->n1,
									$thisqueue->n2,
									$thisqueue->n3,
									$thisqueue->n4,
								);
								if ($isNomens) {
									$userNomens = $um->getUserNomenclatureMapping($thisuser->id, $thisqueue->customerno);
									if (isset($userNomens) && !empty($userNomens)) {
										$data = array_map(function ($element) {
											return $element['nomenclatureid'];
										}, $userNomens);
										$arrIntersect = array_intersect($unitNomens, $data);
									}
								}
							}
							$sub_temp = substr($thisqueue->message, strpos($thisqueue->message, '[') + strlen('['), strlen($thisqueue->message));
							$message_temp = (int) (substr($sub_temp, 0, strpos($sub_temp, ']')));
							$temperatures = $um->getSmsEmailTemp($thisuser->id, $thisqueue->vehicleid, $thisqueue->tempsensor);
							$isSms = FALSE;
							$isEmail = FALSE;
							$min_nomen = 'temp' . $thisqueue->tempsensor . '_min';
							$temp_min = isset($thisuser->$min_nomen) ? $thisuser->$min_nomen : NULL;
							if (isset($temp_min)) {
								if (($message_temp < $temp_min && $message_temp < $temperatures['temp' . $thisqueue->tempsensor . '_min_email']) || $message_temp > $temperatures['temp' . $thisqueue->tempsensor . '_max_email']) {
									$isEmail = TRUE;
								}
								if (($message_temp < $temp_min && $message_temp < $temperatures['temp' . $thisqueue->tempsensor . '_min_sms']) || $message_temp > $temperatures['temp' . $thisqueue->tempsensor . '_max_sms']) {
									$isSms = TRUE;
								}
							} else {
								if ($message_temp < $temperatures['temp' . $thisqueue->tempsensor . '_min_email'] || $message_temp > $temperatures['temp' . $thisqueue->tempsensor . '_max_email']) {
									$isEmail = TRUE;
								}
								if ($message_temp < $temperatures['temp' . $thisqueue->tempsensor . '_min_sms'] || $message_temp > $temperatures['temp' . $thisqueue->tempsensor . '_max_sms']) {
									$isSms = TRUE;
								}
							}
							if (isset($thisuser->new_active_status)) {
								if ($thisuser->new_active_status != 1) {
									continue;
								}
								$start_alert_time = $thisuser->alert_start_time;
								$stop_alert_time = $thisuser->alert_end_time;
							} else {
								$start_alert_time = $thisuser->start_alert_time;
								$stop_alert_time = $thisuser->stop_alert_time;
							}
							$groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
							//echo $thisuser->id."--Groups -- ".date("H:i:s")."<br/>";
							if (isset($groups)) {
								foreach ($groups as $group) {
									$vehiclemanager = new VehicleManager($thisuser->customerno);
									$vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid, $thisqueue->vehicleid);
									if ($vehicles == true &&
										(
											($isNomens && isset($arrIntersect) && !empty($arrIntersect)) || (!$isNomens)
										)
									) {
										//echo "Vehicle -- ".date("H:i:s")."<br/>";die();
										if ((strtotime($start_alert_time) < strtotime($stop_alert_time)) && (strtotime($start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($stop_alert_time))) {
											/* Email Notification */
											if (isset($thisuser->$email) && $thisuser->$email == 1 && $isEmail && isset($thisuser->email) && $thisuser->email != "") {
												$subject = $subject . " - " . $thisqueue->vehicleno;
												$message = explode("$thisqueue->vehicleno - ", $thisqueue->message);
												$email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
												if (file_exists($email_path)) {
													$emailmessage = file_get_contents($email_path);
													$emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
													$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
													$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
													$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
												} else {
													$emailmessage = file_get_contents('../emailtemplates/alertTemperatureMailTemplate.html');
													$emailmessage = str_replace("{{MESSAGE}}", $message[1], $emailmessage);
													$emailmessage = str_replace("{{VEHICLEHEAD}}", $thisqueue->kind, $emailmessage);
													$emailmessage = str_replace("{{REALNAME}}", $thisuser->realname, $emailmessage);
													$emailmessage = str_replace("{{CUSTOMER}}", $thisqueue->customerno, $emailmessage);
													$emailmessage = str_replace("{{VEHICLENO}}", $thisqueue->vehicleno, $emailmessage);
													$emailmessage = str_replace("{{TRIPDETAIL}}", $tripdetails, $emailmessage);
													$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
													$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
													$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
												}
												$cvo = new VOComQueue();
												if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 0;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
											}
											/* SMS Notification */
											if (isset($thisuser->$sms) && $thisuser->$sms == 1 && $isSms && isset($thisuser->phone) && $thisuser->phone != "") {
												$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
												if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 1;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
											}
											/* Telephonic Notification */
											if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
												if ($thisqueue->type == '8') {
													$fileid = '2807_4_20160422164452';
												}
												$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
												$sendsms = telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
												$cvo->cqid = $thisqueue->cqid;
												$cvo->customerno = $thisqueue->customerno;
												$cvo->userid = $thisuser->id;
												$cvo->type = 2;
												$cvo->enh_checkpointid = 0;
												$cqm->InsertComHistory($cvo);
												$cqm->UpdateComQueue($thisqueue->cqid);
											}
											/* Mobile App Notification */
											if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
												$vehicleid = $thisqueue->vehicleid;
												$cqm = new ComQueueManager();
												$gcmid = array($thisuser->gcmid);
												$message = array(
													"message" => $smsmessage,
													"vehicleid" => $vehicleid,
													"type" => $thisqueue->type,
												);
												$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
											}
											//BUZZER ALERTS FOR Temperature Conflict
											/*
	                                              Need to ring buzzer for the unique unit ids.
	                                              Different users might be mapped to same units and buzzer should not ring multiple times.
											*/
											if ((!in_array($thisqueue->uid, $arrUnitIdWithBuzzCmdSet)) && ($thisqueue->customerno == speedConstants::CUSTNO_PERKINELMER || $thisqueue->customerno == speedConstants::CUSTNO_NESTLE)) {
												$arrUnitIdWithBuzzCmdSet[] = $thisqueue->uid;
												$objUnitManager = new UnitManager($thisuser->customerno);
												$objCommandDetails = new stdClass();
												$objCommandDetails->uid = $thisqueue->uid;
												$objCommandDetails->command = "BUZZ";
												//Chaukas Device would have unitno with 7 digits or more
												if (isset($thisqueue->unitno) && strlen($thisqueue->unitno) >= 7) {
													$objCommandDetails->command .= "=30";
												}
												$objUnitManager->setCommand($objCommandDetails);
											}
										} else {
											if ((strtotime($start_alert_time) > strtotime($stop_alert_time)) && ((strtotime($stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($start_alert_time)) || (strtotime($stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($start_alert_time)))) {
												if (isset($thisuser->$email) && $thisuser->$email == 1 && $isEmail && isset($thisuser->email) && $thisuser->email != "") {
													$subject = $subject . " - " . $thisqueue->vehicleno;
													$message = explode("$thisqueue->vehicleno - ", $thisqueue->message);
													$email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
													if (file_exists($email_path)) {
														$emailmessage = file_get_contents($email_path);
														$emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
														$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
														$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
														$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
													} else {
														$emailmessage = file_get_contents('../emailtemplates/alertTemperatureMailTemplate.html');
														$emailmessage = str_replace("{{MESSAGE}}", $message[1], $emailmessage);
														$emailmessage = str_replace("{{VEHICLEHEAD}}", $thisqueue->kind, $emailmessage);
														$emailmessage = str_replace("{{REALNAME}}", $thisuser->realname, $emailmessage);
														$emailmessage = str_replace("{{CUSTOMER}}", $thisqueue->customerno, $emailmessage);
														$emailmessage = str_replace("{{VEHICLENO}}", $thisqueue->vehicleno, $emailmessage);
														$emailmessage = str_replace("{{TRIPDETAIL}}", $tripdetails, $emailmessage);
														$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
														$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
														$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
													}
													$cvo = new VOComQueue();
													if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
														$cvo->cqid = $thisqueue->cqid;
														$cvo->customerno = $thisqueue->customerno;
														$cvo->userid = $thisuser->id;
														$cvo->type = 0;
														$cvo->enh_checkpointid = 0;
														$cqm->InsertComHistory($cvo);
														$cqm->UpdateComQueue($thisqueue->cqid);
													}
												}
												if (isset($thisuser->$sms) && $thisuser->$sms == 1 && $isSms && isset($thisuser->phone) && $thisuser->phone != "") {
													$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
													if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
														$cvo->cqid = $thisqueue->cqid;
														$cvo->customerno = $thisqueue->customerno;
														$cvo->userid = $thisuser->id;
														$cvo->type = 1;
														$cvo->enh_checkpointid = 0;
														$cqm->InsertComHistory($cvo);
														$cqm->UpdateComQueue($thisqueue->cqid);
													}
												}
												if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
													if ($thisqueue->type == '8') {
														$fileid = '2807_4_20160422164452';
													}
													$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
													telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 2;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
												if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
													$dates = new Date();
													$hourminutes = $dates->return_hours($thisqueue->timeadded);
													$vehicleid = $thisqueue->vehicleid;
													$cqm = new ComQueueManager();
													$gcmid = array($thisuser->gcmid);
													$message = array(
														"message" => $smsmessage,
														"vehicleid" => $vehicleid,
														"type" => $thisqueue->type,
													);
													$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
												}
											}
										}
									}
								}
							}
						} elseif (!$includeUser1) {
							$isNomens = 0;
							if ($thisqueue->customerno == speedConstants::CUSTNO_PERKINELMER) {
								if ($thisqueue->n1 > 0) {
									$isNomens = 1;
								} elseif ($thisqueue->n2 > 0) {
									$isNomens = 1;
								} elseif ($thisqueue->n3 > 0) {
									$isNomens = 1;
								} elseif ($thisqueue->n4 > 0) {
									$isNomens = 1;
								}
								$unitNomens = array(
									$thisqueue->n1,
									$thisqueue->n2,
									$thisqueue->n3,
									$thisqueue->n4,
								);
								if ($isNomens) {
									$userNomens = $um->getUserNomenclatureMapping($thisuser->id, $thisqueue->customerno);
									if (isset($userNomens) && !empty($userNomens)) {
										$data = array_map(function ($element) {
											return $element['nomenclatureid'];
										}, $userNomens);
										$arrIntersect = array_intersect($unitNomens, $data);
									}
								}
							}
							if (isset($thisuser->new_active_status)) {
								if ($thisuser->new_active_status != 1) {
									continue;
								}
								$start_alert_time = $thisuser->alert_start_time;
								$stop_alert_time = $thisuser->alert_end_time;
							} else {
								$start_alert_time = $thisuser->start_alert_time;
								$stop_alert_time = $thisuser->stop_alert_time;
							}
							$groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
							//echo $thisuser->id."--Groups -- ".date("H:i:s")."<br/>";
							if (isset($groups)) {
								foreach ($groups as $group) {
									$vehiclemanager = new VehicleManager($thisuser->customerno);
									$vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid, $thisqueue->vehicleid);
									if ($vehicles == true && (($isNomens && isset($arrIntersect) && !empty($arrIntersect)) || (!$isNomens))) {
										//echo "Vehicle -- ".date("H:i:s")."<br/>";die();
										if ((strtotime($start_alert_time) < strtotime($stop_alert_time)) && (strtotime($start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($stop_alert_time))) {
											if (isset($thisuser->$email) && $thisuser->$email == 1 && isset($thisuser->email) && $thisuser->email != "") {
												$subject = $subject . " - " . $thisqueue->vehicleno;
												$message = explode("$thisqueue->vehicleno - ", $thisqueue->message);
												$email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
												if (file_exists($email_path)) {
													$emailmessage = file_get_contents($email_path);
													$emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
													$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
													$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
													$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
												} else {
													$emailmessage = file_get_contents('../emailtemplates/alertTemperatureMailTemplate.html');
													$emailmessage = str_replace("{{MESSAGE}}", $message[1], $emailmessage);
													$emailmessage = str_replace("{{VEHICLEHEAD}}", $thisqueue->kind, $emailmessage);
													$emailmessage = str_replace("{{REALNAME}}", $thisuser->realname, $emailmessage);
													$emailmessage = str_replace("{{CUSTOMER}}", $thisqueue->customerno, $emailmessage);
													$emailmessage = str_replace("{{VEHICLENO}}", $thisqueue->vehicleno, $emailmessage);
													$emailmessage = str_replace("{{TRIPDETAIL}}", $tripdetails, $emailmessage);
													$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
													$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
													$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
												}
												$cvo = new VOComQueue();
												if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 0;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
											}
											if (isset($thisuser->$sms) && $thisuser->$sms == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
												$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
												if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 1;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
											}
											if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
												if ($thisqueue->type == '8') {
													$fileid = '2807_4_20160422164452';
												}
												$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
												$sendsms = telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
												$cvo->cqid = $thisqueue->cqid;
												$cvo->customerno = $thisqueue->customerno;
												$cvo->userid = $thisuser->id;
												$cvo->type = 2;
												$cvo->enh_checkpointid = 0;
												$cqm->InsertComHistory($cvo);
												$cqm->UpdateComQueue($thisqueue->cqid);
											}
											if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
												$dates = new Date();
												$hourminutes = $dates->return_hours($thisqueue->timeadded);
												$vehicleid = $thisqueue->vehicleid;
												$cqm = new ComQueueManager();
												$gcmid = array($thisuser->gcmid);
												$message = array(
													"message" => $smsmessage,
													"vehicleid" => $vehicleid,
													"type" => $thisqueue->type,
												);
												$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
											}
											//BUZZER ALERTS FOR Temperature Conflict
											/*
	                                              Need to ring buzzer for the unique unit ids.
	                                              Different users might be mapped to same units and buzzer should not ring multiple times.
											*/
											if ((!in_array($thisqueue->uid, $arrUnitIdWithBuzzCmdSet)) && ($thisqueue->customerno == speedConstants::CUSTNO_PERKINELMER || $thisqueue->customerno == speedConstants::CUSTNO_NESTLE)) {
												$arrUnitIdWithBuzzCmdSet[] = $thisqueue->uid;
												$objUnitManager = new UnitManager($thisuser->customerno);
												$objCommandDetails = new stdClass();
												$objCommandDetails->uid = $thisqueue->uid;
												$objCommandDetails->command = "BUZZ";
												//Chaukas Device would have unitno with 7 digits or more
												if (isset($thisqueue->unitno) && strlen($thisqueue->unitno) >= 7) {
													$objCommandDetails->command .= "=30";
												}
												$objUnitManager->setCommand($objCommandDetails);
											}
										} else {
											if ((strtotime($start_alert_time) > strtotime($stop_alert_time)) && ((strtotime($stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($start_alert_time)) || (strtotime($stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($start_alert_time)))) {
												if (isset($thisuser->$email) && $thisuser->$email == 1 && isset($thisuser->email) && $thisuser->email != "") {
													$subject = $subject . " - " . $thisqueue->vehicleno;
													$message = explode("$thisqueue->vehicleno - ", $thisqueue->message);
													$email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
													if (file_exists($email_path)) {
														$emailmessage = file_get_contents($email_path);
														$emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
														$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
														$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
														$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
													} else {
														$emailmessage = file_get_contents('../emailtemplates/alertTemperatureMailTemplate.html');
														$emailmessage = str_replace("{{MESSAGE}}", $message[1], $emailmessage);
														$emailmessage = str_replace("{{VEHICLEHEAD}}", $thisqueue->kind, $emailmessage);
														$emailmessage = str_replace("{{REALNAME}}", $thisuser->realname, $emailmessage);
														$emailmessage = str_replace("{{CUSTOMER}}", $thisqueue->customerno, $emailmessage);
														$emailmessage = str_replace("{{VEHICLENO}}", $thisqueue->vehicleno, $emailmessage);
														$emailmessage = str_replace("{{TRIPDETAIL}}", $tripdetails, $emailmessage);
														$emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
														$emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
														$emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
													}
													$cvo = new VOComQueue();
													if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
														$cvo->cqid = $thisqueue->cqid;
														$cvo->customerno = $thisqueue->customerno;
														$cvo->userid = $thisuser->id;
														$cvo->type = 0;
														$cvo->enh_checkpointid = 0;
														$cqm->InsertComHistory($cvo);
														$cqm->UpdateComQueue($thisqueue->cqid);
													}
												}
												if (isset($thisuser->$sms) && $thisuser->$sms == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
													$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
													if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
														$cvo->cqid = $thisqueue->cqid;
														$cvo->customerno = $thisqueue->customerno;
														$cvo->userid = $thisuser->id;
														$cvo->type = 1;
														$cvo->enh_checkpointid = 0;
														$cqm->InsertComHistory($cvo);
														$cqm->UpdateComQueue($thisqueue->cqid);
													}
												}
												if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
													if ($thisqueue->type == '8') {
														$fileid = '2807_4_20160422164452';
													}
													$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
													telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 2;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
												if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
													$vehicleid = $thisqueue->vehicleid;
													$cqm = new ComQueueManager();
													$gcmid = array($thisuser->gcmid);
													$message = array(
														"message" => $smsmessage,
														"vehicleid" => $vehicleid,
														"type" => $thisqueue->type,
													);
													$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			//echo "End  --".date("H:i:s");
		} else {
			$um = new UserManager();
			$users = $um->getusersforcustomerbytype($thisqueue->customerno, $thisqueue->type, $thisqueue->vehicleid);
			if (isset($users)) {
				foreach ($users as $thisuser) {
					if (isset($thisuser->new_active_status)) {
						if ($thisuser->new_active_status != 1) {
							continue;
						}
						$start_alert_time = $thisuser->alert_start_time;
						$stop_alert_time = $thisuser->alert_end_time;
						//echo $thisuser->new_active_status."======$email---------$start_alert_time-$stop_alert_time<br/>";
					} else {
						$start_alert_time = $thisuser->start_alert_time;
						$stop_alert_time = $thisuser->stop_alert_time;
					}
					$groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
					if (isset($groups)) {
						foreach ($groups as $group) {
							$vehiclemanager = new VehicleManager($thisuser->customerno);
							$vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid, $thisqueue->vehicleid);
							if ($vehicles == true) {
								if ((strtotime($start_alert_time) < strtotime($stop_alert_time)) && (strtotime($start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($stop_alert_time))) {
									if (isset($thisuser->$email) && $thisuser->$email == 1 && isset($thisuser->email) && $thisuser->email != "") {

										if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
											$cvo->cqid = $thisqueue->cqid;
											$cvo->customerno = $thisqueue->customerno;
											$cvo->userid = $thisuser->id;
											$cvo->type = 0;
											$cvo->enh_checkpointid = 0;
											$cqm->InsertComHistory($cvo);
											$cqm->UpdateComQueue($thisqueue->cqid);
										}
									}
									if (isset($thisuser->$sms) && $thisuser->$sms == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
										if ($thisqueue->type == '1' || $thisqueue->type == '4') {
											$smsmessage = $thisqueue->message . "." . $location;
										} else {
											$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
										}
										if ($thisqueue->type == '5' && $thisqueue->status == 1) {
											//no sms
										} else {
											if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
												$cvo->cqid = $thisqueue->cqid;
												$cvo->customerno = $thisqueue->customerno;
												$cvo->userid = $thisuser->id;
												$cvo->type = 1;
												$cvo->enh_checkpointid = 0;
												$cqm->InsertComHistory($cvo);
												$cqm->UpdateComQueue($thisqueue->cqid);
											}
										}
									}
									if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
										if ($thisqueue->type == '8') {
											$fileid = '2807_4_20160422164452';
										}
										if ($thisqueue->type == '1' || $thisqueue->type == '4') {
											$smsmessage = $thisqueue->message . "." . $location;
										} else {
											$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
										}
										if ($thisqueue->type == '5' && $thisqueue->status == 1) {
											//no sms
										} else {
											$sendsms = telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
											$cvo->cqid = $thisqueue->cqid;
											$cvo->customerno = $thisqueue->customerno;
											$cvo->userid = $thisuser->id;
											$cvo->type = 2;
											$cvo->enh_checkpointid = 0;
											$cqm->InsertComHistory($cvo);
											$cqm->UpdateComQueue($thisqueue->cqid);
										}
									}
									if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
										$dates = new Date();
										$hourminutes = $dates->return_hours($thisqueue->timeadded);
										$vehicleid = $thisqueue->vehicleid;
										$cqm = new ComQueueManager();
										$gcmid = array($thisuser->gcmid);
										$message = array(
											"message" => $smsmessage,
											"vehicleid" => $vehicleid,
											"type" => $thisqueue->type,
										);
										$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
									}
								} else {
									if ((strtotime($start_alert_time) > strtotime($stop_alert_time)) && ((strtotime($stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($start_alert_time)) || (strtotime($stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($start_alert_time)))) {
										if (isset($thisuser->$email) && $thisuser->$email == 1 && isset($thisuser->email) && $thisuser->email != "") {
											if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
												$cvo->cqid = $thisqueue->cqid;
												$cvo->customerno = $thisqueue->customerno;
												$cvo->userid = $thisuser->id;
												$cvo->type = 0;
												$cvo->enh_checkpointid = 0;
												$cqm->InsertComHistory($cvo);
												$cqm->UpdateComQueue($thisqueue->cqid);
											}
										}
										if (isset($thisuser->$sms) && $thisuser->$sms == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
											if ($thisqueue->type == '1' || $thisqueue->type == '4') {
												$smsmessage = $thisqueue->message . "." . $location;
											} else {
												$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
											}
											if ($thisqueue->type == '5' && $thisqueue->status == 1) {
												//no sms
											} else {
												if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
													$cvo->cqid = $thisqueue->cqid;
													$cvo->customerno = $thisqueue->customerno;
													$cvo->userid = $thisuser->id;
													$cvo->type = 1;
													$cvo->enh_checkpointid = 0;
													$cqm->InsertComHistory($cvo);
													$cqm->UpdateComQueue($thisqueue->cqid);
												}
											}
										}
										if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
											if ($thisqueue->type == '8') {
												$fileid = '2807_4_20160422164452';
											}
											if ($thisqueue->type == '1' || $thisqueue->type == '4') {
												$smsmessage = $thisqueue->message . "." . $location;
											} else {
												$smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . "." . $location;
											}
											if ($thisqueue->type == '5' && $thisqueue->status == 1) {
												//no sms
											} else {
												telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
												$cvo->cqid = $thisqueue->cqid;
												$cvo->customerno = $thisqueue->customerno;
												$cvo->userid = $thisuser->id;
												$cvo->type = 2;
												$cvo->enh_checkpointid = 0;
												$cqm->InsertComHistory($cvo);
												$cqm->UpdateComQueue($thisqueue->cqid);
											}
										}
										if (isset($thisuser->$mobile) && $thisuser->$mobile == 1 && $thisuser->notification_status == 1 && $thisuser->gcmid != "") {
											$dates = new Date();
											$hourminutes = $dates->return_hours($thisqueue->timeadded);
											$vehicleid = $thisqueue->vehicleid;
											$cqm = new ComQueueManager();
											$gcmid = array($thisuser->gcmid);
											$message = array(
												"message" => $smsmessage,
												"vehicleid" => $vehicleid,
												"type" => $thisqueue->type,
											);
											$sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>