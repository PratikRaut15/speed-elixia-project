<?php

require_once '../../lib/system/utilities.php';
require_once '../../lib/autoload.php';
include '../../lib/components/ajaxpage.inc.php';
include '../common/map_common_functions.php';
include_once '../../lib/comman_function/reports_func.php';
include_once "../../lib/bo/CheckpointManager.php";
//date_default_timezone_set("Asia/Calcutta");
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['timezone'])) {
	$_SESSION['timezone'] = 'Asia/Kolkata';
}
@date_default_timezone_set($_SESSION['timezone']);

class jsonop {
	// Empty class!
}

function getvehicle($vehicleids) {
	$idarray = explode(",", $vehicleids);
	$IST = getIST();
	if (isset($idarray)) {
		$finaloutput = array();
		foreach ($idarray as $vehicleid) {
			$tempconversion = new TempConversion();
			if (isset($vehicleid) && $vehicleid != "") {
				$devicemanager = new DeviceManager($_SESSION['customerno']);
				$vehicleid = GetSafeValueString($vehicleid, "string");
				$device = $devicemanager->deviceformapping($vehicleid);
				if (isset($device)) {
					$output = new jsonop();
					$lastupdated2 = strtotime($device->lastupdated);
					$tempconversion->unit_type = $device->get_conversion;
					$output->cgeolat = $device->devicelat;
					$output->description = $device->description;
					$output->cgeolong = $device->devicelong;

					$output->cname = $device->vehicleno;
					$output->cdrivername = $device->drivername;
					$output->cdriverphone = $device->driverphone;
					$output->cspeed = $device->curspeed;
					$output->clastupdated = diff($IST, $lastupdated2);
					$output->cvehicleid = $device->vehicleid;
					$output->image = vehicleimage($device);
					$output->status = getVehicleStatus($device);
					$output->totaldist = round(getdistance_new($device->vehicleid, $_SESSION['customerno']), 2);
					if ($output->totaldist < 0) {
						$output->totaldist = round($output->totaldist, 2);
					}

					$output->t1 = (getName($device->n1) !== null) ? getName($device->n1) : 'Temperature1';
					$output->t2 = (getName($device->n2) !== null) ? getName($device->n2) : 'Temperature2';
					$output->t3 = (getName($device->n3) !== null) ? getName($device->n3) : 'Temperature3';
					$output->t4 = (getName($device->n4) !== null) ? getName($device->n4) : 'Temperature4';
					if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
						$output->temp_sensors = $device->temp_sensors;
						if ($device->temp_sensors == 1) {
							// Temperature Sensor
							$temp = 'Not Active';
							$s = "analog" . $device->tempsen1;
							if ($device->tempsen1 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp = getTempUtil($tempconversion);
							} else {
								$temp = '-';
							}

							$output->temp = $temp;
							if ($temp != '-' && $temp != "Not Active") {
								$output->tempon = 1;
							} else {
								$output->tempon = 0;
							}
						}

						if ($device->temp_sensors == 2) {
							$temp1 = 'Not Active';
							$temp2 = 'Not Active';

							$s = "analog" . $device->tempsen1;
							if ($device->tempsen1 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp1 = getTempUtil($tempconversion);
							} else {
								$temp1 = '-';
							}

							$s = "analog" . $device->tempsen2;
							if ($device->tempsen2 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp2 = getTempUtil($tempconversion);
							} else {
								$temp2 = '-';
							}

							$output->temp1 = $temp1;
							$output->temp2 = $temp2;

							if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
								$output->temp1on = 1;
							} else {
								$output->temp1on = 0;
							}

							if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
								$output->temp2on = 1;
							} else {
								$output->temp2on = 0;
							}
						}

						if ($device->temp_sensors == 3) {
							$temp1 = 'Not Active';
							$temp2 = 'Not Active';
							$temp3 = 'Not Active';

							$s = "analog" . $device->tempsen1;
							if ($device->tempsen1 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp1 = getTempUtil($tempconversion);
							} else {
								$temp1 = '-';
							}

							$s = "analog" . $device->tempsen2;
							if ($device->tempsen2 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp2 = getTempUtil($tempconversion);
							} else {
								$temp2 = '-';
							}

							$s = "analog" . $device->tempsen3;
							if ($device->tempsen3 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp3 = getTempUtil($tempconversion);
							} else {
								$temp3 = '-';
							}

							$output->temp1 = $temp1;
							$output->temp2 = $temp2;
							$output->temp3 = $temp3;

							if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
								$output->temp1on = 1;
							} else {
								$output->temp1on = 0;
							}

							if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
								$output->temp2on = 1;
							} else {
								$output->temp2on = 0;
							}

							if ($output->temp3 != '-' && $output->temp3 != "Not Active") {
								$output->temp3on = 1;
							} else {
								$output->temp3on = 0;
							}
						}

						if ($device->temp_sensors == 4) {
							$temp1 = 'Not Active';
							$temp2 = 'Not Active';
							$temp3 = 'Not Active';
							$temp4 = 'Not Active';

							$s = "analog" . $device->tempsen1;
							if ($device->tempsen1 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp1 = getTempUtil($tempconversion);
							} else {
								$temp1 = '-';
							}

							$s = "analog" . $device->tempsen2;
							if ($device->tempsen2 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp2 = getTempUtil($tempconversion);
							} else {
								$temp2 = '-';
							}

							$s = "analog" . $device->tempsen3;
							if ($device->tempsen3 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp3 = getTempUtil($tempconversion);
							} else {
								$temp3 = '-';
							}

							$s = "analog" . $device->tempsen4;
							if ($device->tempsen4 != 0 && $device->$s != 0) {
								$tempconversion->rawtemp = $device->$s;
								$temp4 = getTempUtil($tempconversion);
							} else {
								$temp4 = '-';
							}

							$output->temp1 = $temp1;
							$output->temp2 = $temp2;
							$output->temp3 = $temp3;
							$output->temp4 = $temp4;

							if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
								$output->temp1on = 1;
							} else {
								$output->temp1on = 0;
							}

							if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
								$output->temp2on = 1;
							} else {
								$output->temp2on = 0;
							}

							if ($output->temp3 != '-' && $output->temp3 != "Not Active") {
								$output->temp3on = 1;
							} else {
								$output->temp3on = 0;
							}

							if ($output->temp4 != '-' && $output->temp4 != "Not Active") {
								$output->temp4on = 1;
							} else {
								$output->temp4on = 0;
							}
						}

					} else {
						$output->temp_sensors = 0;
					}
					if ($device->use_humidity == 1 && $device->humidity != 0) {
						// Temperature Sensor
						$humidity = 'Not Active';
						$s = "analog" . $device->humidity;
						if ($device->$s != 0) {
							$tempconversion->use_humidity = $device->use_humidity;
							$tempconversion->switch_to = 3;
							$tempconversion->rawtemp = $device->$s;
							$humidity = getTempUtil($tempconversion);
						} else {
							$humidity = '-';
						}

						$output->$humidity = $humidity;

					}
					$finaloutput[] = $output;
				}
			}
		}
	}
	$ajaxpage = new ajaxpage();
	$ajaxpage->SetResult($finaloutput);
	$ajaxpage->Render();
}

function getwarehouse($vehicleids) {
	$idarray = explode(",", $vehicleids);
	$IST = getIST();
	if (isset($idarray)) {
		$finaloutput = array();
		foreach ($idarray as $vehicleid) {
			if (isset($vehicleid) && $vehicleid != "") {
				$devicemanager = new DeviceManager($_SESSION['customerno']);
				$vehicleid = GetSafeValueString($vehicleid, "string");
				$device = $devicemanager->deviceformapping_warehouse($vehicleid);
				if (isset($device)) {
					$output = new jsonop();
					$lastupdated2 = strtotime($device->lastupdated);
					$output->cgeolat = $device->devicelat;
					$output->description = $device->description;
					$output->cgeolong = $device->devicelong;

					$output->cname = $device->vehicleno;
					$output->cdrivername = $device->drivername;
					$output->cdriverphone = $device->driverphone;
					$output->cspeed = $device->curspeed;
					$output->clastupdated = diff($IST, $lastupdated2);
					$output->cvehicleid = $device->vehicleid;
					$output->image = vehicleimage($device);
					$finaloutput[] = $output;
				}
			}
		}
	}
	$ajaxpage = new ajaxpage();
	$ajaxpage->SetResult($finaloutput);
	$ajaxpage->Render();
}

function getvehicles() {
	$finaloutput = Array();
	$IST = getIST();
	$devicemanager = new DeviceManager($_SESSION['customerno']);
	$tempconversion = new TempConversion();
	if ($_SESSION['use_warehouse'] == '1') {
		$devices1 = $devicemanager->deviceformappings();

		$devices = $devices1;
	} else {
		$devices = $devicemanager->deviceformappings();
	}

	if (isset($devices)) {
		foreach ($devices as $device) {
			if ($device->devicelat != '0.000000' && $device->devicelong != '0.000000') {
				$output = new jsonop();
				$tempconversion->unit_type = $device->get_conversion;
				$checkpointmanager = new CheckpointManager($_SESSION['customerno']);
				$lastupdated2 = strtotime($device->lastupdated);
				$output->cgeolat = $device->devicelat;
				$output->cgeolong = $device->devicelong;
				$output->cname = $device->vehicleno;
				$output->ckind = $device->kind;
				$output->clocation = "";
				if ($device->devicelat != '0.000000' && $device->devicelong != '0.000000') {
					$output->clocation = location_cmn($device->devicelat, $device->devicelong, 1);
				}
				$output->cdrivername = $device->drivername;
				$output->description = $device->description;
				$output->cdriverphone = $device->driverphone;
				$output->cspeed = $device->curspeed;
				$output->clastupdated = diff($IST, $lastupdated2);
				$output->cvehicleid = $device->vehicleid;
				$output->t1 = (getName($device->n1) !== null) ? getName($device->n1) : 'Temperature1';
				$output->t2 = (getName($device->n2) !== null) ? getName($device->n2) : 'Temperature2';
				$output->t3 = (getName($device->n3) !== null) ? getName($device->n3) : 'Temperature3';
				$output->t4 = (getName($device->n4) !== null) ? getName($device->n4) : 'Temperature4';
				$output->t1Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",1," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
				$output->t2Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",2," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
				$output->t3Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",3," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
				$output->t4Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",4," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
				if (!isset($_SESSION['isChkpnt'])) {
					$_SESSION['isChkpnt'] = 0;
				}
				if ($_SESSION['isChkpnt'] == 0) {
					$checkpoints1 = $checkpointmanager->get_checkpoint_from_chkmanage($device->vehicleid);
					$_SESSION['isChkpnt'] = 1;
				}
				if (!empty($checkpoints1)) {
					$chkptlist = array();
					foreach ($checkpoints1 as $row) {
						$chkptlist[] = $row->cname;
					}
					$checkpointlist = implode(",", $chkptlist);
					$output->checkpointlist = $checkpointlist;
				} else {
					$output->checkpointlist = "";
				}
				$output->image = vehicleimage($device);
				$output->status = getVehicleStatus($device);
				if ((isset($_SESSION['userid']) && $_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) || (isset($_SESSION['ecodeid']))) {
					$output->temp_sensors = $device->temp_sensors;
					if ($device->temp_sensors == 1) {
						// Temperature Sensor
						$temp = 'Not Active';
						$s = "analog" . $device->tempsen1;
						if ($device->tempsen1 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp = getTempUtil($tempconversion);
						} else {
							$temp = '-';
						}

						$output->temp = $temp;
						if ($temp != '-' && $temp != "Not Active") {
							$output->tempon = 1;
						} else {
							$output->tempon = 0;
						}
					}

					if ($device->temp_sensors == 2) {
						$temp1 = 'Not Active';
						$temp2 = 'Not Active';

						$s = "analog" . $device->tempsen1;
						if ($device->tempsen1 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp1 = getTempUtil($tempconversion);
						} else {
							$temp1 = '-';
						}

						$s = "analog" . $device->tempsen2;
						if ($device->tempsen2 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp2 = getTempUtil($tempconversion);
						} else {
							$temp2 = '-';
						}

						$output->temp1 = $temp1;
						$output->temp2 = $temp2;

						if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
							$output->temp1on = 1;
						} else {
							$output->temp1on = 0;
						}

						if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
							$output->temp2on = 1;
						} else {
							$output->temp2on = 0;
						}
					}

					if ($device->temp_sensors == 3) {
						$temp1 = 'Not Active';
						$temp2 = 'Not Active';
						$temp3 = 'Not Active';

						$s = "analog" . $device->tempsen1;
						if ($device->tempsen1 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp1 = getTempUtil($tempconversion);
						} else {
							$temp1 = '-';
						}

						$s = "analog" . $device->tempsen2;
						if ($device->tempsen2 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp2 = getTempUtil($tempconversion);
						} else {
							$temp2 = '-';
						}

						$s = "analog" . $device->tempsen3;
						if ($device->tempsen3 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp3 = getTempUtil($tempconversion);
						} else {
							$temp3 = '-';
						}

						$output->temp1 = $temp1;
						$output->temp2 = $temp2;
						$output->temp3 = $temp3;

						if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
							$output->temp1on = 1;
						} else {
							$output->temp1on = 0;
						}

						if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
							$output->temp2on = 1;
						} else {
							$output->temp2on = 0;
						}

						if ($output->temp3 != '-' && $output->temp3 != "Not Active") {
							$output->temp3on = 1;
						} else {
							$output->temp3on = 0;
						}
					}

					if ($device->temp_sensors == 4) {
						$temp1 = 'Not Active';
						$temp2 = 'Not Active';
						$temp3 = 'Not Active';
						$temp4 = 'Not Active';

						$s = "analog" . $device->tempsen1;
						if ($device->tempsen1 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp1 = getTempUtil($tempconversion);
						} else {
							$temp1 = '-';
						}

						$s = "analog" . $device->tempsen2;
						if ($device->tempsen2 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp2 = getTempUtil($tempconversion);
						} else {
							$temp2 = '-';
						}

						$s = "analog" . $device->tempsen3;
						if ($device->tempsen3 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp3 = getTempUtil($tempconversion);
						} else {
							$temp3 = '-';
						}

						$s = "analog" . $device->tempsen4;
						if ($device->tempsen4 != 0 && $device->$s != 0) {
							$tempconversion->rawtemp = $device->$s;
							$temp4 = getTempUtil($tempconversion);
						} else {
							$temp4 = '-';
						}

						$output->temp1 = $temp1;
						$output->temp2 = $temp2;
						$output->temp3 = $temp3;
						$output->temp4 = $temp4;

						if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
							$output->temp1on = 1;
						} else {
							$output->temp1on = 0;
						}

						if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
							$output->temp2on = 1;
						} else {
							$output->temp2on = 0;
						}

						if ($output->temp3 != '-' && $output->temp3 != "Not Active") {
							$output->temp3on = 1;
						} else {
							$output->temp3on = 0;
						}

						if ($output->temp4 != '-' && $output->temp4 != "Not Active") {
							$output->temp4on = 1;
						} else {
							$output->temp4on = 0;
						}
					}

				} else {
					$output->temp_sensors = 0;
				}
				if ($_SESSION['portable'] != '1') {
					$output->portable = 0;
				} else {
					$output->portable = 1;
				}

				$output->totaldist = round(getdistance_new($device->vehicleid, $_SESSION['customerno']), 2);
				if ($output->totaldist < 0) {
					$output->totaldist = round($output->totaldist, 2);
				}

				$finaloutput[] = $output;
			} // end if for latlong check
		} //end foreach
	}

	$ajaxpage = new ajaxpage();
	$ajaxpage->SetResult($finaloutput);
	$ajaxpage->Render();
}

function getvehicles_wh() {
	$finaloutput = Array();
	$finaloutput1 = Array();
	$finaloutput2 = Array();
	$IST = getIST();
	$devicemanager = new DeviceManager($_SESSION['customerno']);
	$tempconversion = new TempConversion();
	$devices2 = $devicemanager->deviceformappings_wh();

	if (isset($devices2)) {

		foreach ($devices2 as $device) {
			$output = new jsonop();
			$checkpointmanager = new CheckpointManager($_SESSION['customerno']);
			$tempconversion->unit_type = $device->get_conversion;
			$lastupdated2 = strtotime($device->lastupdated);
			$output->cgeolat = $device->devicelat;
			$output->ckind = $device->kind;
			$output->cgeolong = $device->devicelong;
			$output->cname = $device->vehicleno;
			$output->clocation = location_cmn($device->devicelat, $device->devicelong, 0);
			$output->cdrivername = $device->drivername;
			$output->description = $device->description;
			$output->cdriverphone = $device->driverphone;
			$output->cspeed = $device->curspeed;
			$output->clastupdated = diff($IST, $lastupdated2);
			$output->cvehicleid = $device->vehicleid;
			$output->t1 = (getName($device->n1) !== null) ? getName($device->n1) : 'Temperature1';
			$output->t2 = (getName($device->n2) !== null) ? getName($device->n2) : 'Temperature2';
			$output->t3 = (getName($device->n3) !== null) ? getName($device->n3) : 'Temperature3';
			$output->t4 = (getName($device->n4) !== null) ? getName($device->n4) : 'Temperature4';
			$output->t1Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",1," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
			$output->t2Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",2," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
			$output->t3Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",3," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
			$output->t4Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",4," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
			$checkpoints1 = $checkpointmanager->get_checkpoint_from_chkmanage($device->vehicleid);
			if (!empty($checkpoints1)) {
				$chkptlist = array();
				foreach ($checkpoints1 as $row) {
					$chkptlist[] = $row->cname;
				}
				$checkpointlist = implode(",", $chkptlist);
				$output->checkpointlist = $checkpointlist;
			} else {
				$output->checkpointlist = "";
			}
			$output->image = vehicleimage($device);
			$output->status = getVehicleStatus($device);
			if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
				$output->temp_sensors = $device->temp_sensors;
				if ($device->temp_sensors == 1) {
					// Temperature Sensor
					$temp = 'Not Active';
					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp = getTempUtil($tempconversion);
					} else {
						$temp = '-';
					}

					$output->temp = $temp;
					if ($temp != '-' && $temp != "Not Active") {
						$output->tempon = 1;
					} else {
						$output->tempon = 0;
					}
				}

				if ($device->temp_sensors == 2) {
					$temp1 = 'Not Active';
					$temp2 = 'Not Active';

					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp1 = getTempUtil($tempconversion);
					} else {
						$temp1 = '-';
					}

					$s = "analog" . $device->tempsen2;
					if ($device->tempsen2 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp2 = getTempUtil($tempconversion);
					} else {
						$temp2 = '-';
					}

					$output->temp1 = $temp1;
					$output->temp2 = $temp2;

					if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
						$output->temp1on = 1;
					} else {
						$output->temp1on = 0;
					}

					if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
						$output->temp2on = 1;
					} else {
						$output->temp2on = 0;
					}
				}

				if ($device->temp_sensors == 3) {
					$temp1 = 'Not Active';
					$temp2 = 'Not Active';
					$temp3 = 'Not Active';

					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp1 = getTempUtil($tempconversion);
					} else {
						$temp1 = '-';
					}

					$s = "analog" . $device->tempsen2;
					if ($device->tempsen2 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp2 = getTempUtil($tempconversion);
					} else {
						$temp2 = '-';
					}

					$s = "analog" . $device->tempsen3;
					if ($device->tempsen3 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp3 = getTempUtil($tempconversion);
					} else {
						$temp3 = '-';
					}

					$output->temp1 = $temp1;
					$output->temp2 = $temp2;
					$output->temp3 = $temp3;

					if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
						$output->temp1on = 1;
					} else {
						$output->temp1on = 0;
					}

					if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
						$output->temp2on = 1;
					} else {
						$output->temp2on = 0;
					}

					if ($output->temp3 != '-' && $output->temp3 != "Not Active") {
						$output->temp3on = 1;
					} else {
						$output->temp3on = 0;
					}
				}

				if ($device->temp_sensors == 4) {
					$temp1 = 'Not Active';
					$temp2 = 'Not Active';
					$temp3 = 'Not Active';
					$temp4 = 'Not Active';

					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp1 = getTempUtil($tempconversion);
					} else {
						$temp1 = '-';
					}

					$s = "analog" . $device->tempsen2;
					if ($device->tempsen2 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp2 = getTempUtil($tempconversion);
					} else {
						$temp2 = '-';
					}

					$s = "analog" . $device->tempsen3;
					if ($device->tempsen3 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp3 = getTempUtil($tempconversion);
					} else {
						$temp3 = '-';
					}

					$s = "analog" . $device->tempsen4;
					if ($device->tempsen4 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp4 = getTempUtil($tempconversion);
					} else {
						$temp4 = '-';
					}

					$output->temp1 = $temp1;
					$output->temp2 = $temp2;
					$output->temp3 = $temp3;
					$output->temp4 = $temp4;

					if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
						$output->temp1on = 1;
					} else {
						$output->temp1on = 0;
					}

					if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
						$output->temp2on = 1;
					} else {
						$output->temp2on = 0;
					}

					if ($output->temp3 != '-' && $output->temp3 != "Not Active") {
						$output->temp3on = 1;
					} else {
						$output->temp3on = 0;
					}

					if ($output->temp4 != '-' && $output->temp4 != "Not Active") {
						$output->temp4on = 1;
					} else {
						$output->temp4on = 0;
					}
				}
			} else {
				$output->temp_sensors = 0;
			}
			if ($device->use_humidity == 1 && $device->humidity != 0) {
				// Temperature Sensor
				$humidity = 'Not Active';
				$s = "analog" . $device->humidity;
				if ($device->$s != 0) {
					$tempconversion->use_humidity = $device->use_humidity;
					$tempconversion->switch_to = 3;
					$tempconversion->rawtemp = $device->$s;
					$temp = getTempUtil($tempconversion);
				} else {
					$humidity = '-';
				}

				$output->$humidity = $humidity;

			}

			if ($_SESSION['portable'] != '1') {
				$output->portable = 0;
			} else {
				$output->portable = 1;
			}
			$finaloutput1[] = $output;
		}
	}

	$ajaxpage = new ajaxpage();
	$ajaxpage->SetResult($finaloutput1);
	$ajaxpage->Render();
}

function getwarehouses() {
	$finaloutput = Array();
	$IST = getIST();
	$devicemanager = new DeviceManager($_SESSION['customerno']);
	$devices = $devicemanager->deviceformappings_warehouses();
	$tempconversion = new TempConversion();
	if (isset($devices)) {
		foreach ($devices as $device) {
			$output = new jsonop();
			$checkpointmanager = new CheckpointManager($_SESSION['customerno']);
			$tempconversion->unit_type = $device->get_conversion;
			$lastupdated2 = strtotime($device->lastupdated);
			$output->cgeolat = $device->devicelat;
			$output->cgeolong = $device->devicelong;
			$output->cname = $device->vehicleno;
			$output->clocation = location_cmn($device->devicelat, $device->devicelong, 0);
			$output->cdrivername = $device->drivername;
			$output->description = $device->description;
			$output->cdriverphone = $device->driverphone;
			$output->cspeed = $device->curspeed;
			$output->clastupdated = diff($IST, $lastupdated2);
			$output->cvehicleid = $device->vehicleid;
			$checkpoints1 = $checkpointmanager->get_checkpoint_from_chkmanage($device->vehicleid);
			if (!empty($checkpoints1)) {
				$chkptlist = array();
				foreach ($checkpoints1 as $row) {
					$chkptlist[] = $row->cname;
				}
				$checkpointlist = implode(",", $chkptlist);
				$output->checkpointlist = $checkpointlist;
			} else {
				$output->checkpointlist = "";
			}
			$output->image = warehouseimage($device);
			if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
				$output->temp_sensors = $device->temp_sensors;
				if ($device->temp_sensors == 1) {
					// Temperature Sensor
					$temp = 'Not Active';
					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp = getTempUtil($tempconversion);
					} else {
						$temp = '-';
					}

					$output->temp = $temp;
					if ($temp != '-' && $temp != "Not Active") {
						$output->tempon = 1;
					} else {
						$output->tempon = 0;
					}
				}

				if ($device->temp_sensors == 2) {
					$temp1 = 'Not Active';
					$temp2 = 'Not Active';

					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp1 = getTempUtil($tempconversion);
					} else {
						$temp1 = '-';
					}

					$s = "analog" . $device->tempsen2;
					if ($device->tempsen2 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp2 = getTempUtil($tempconversion);
					} else {
						$temp2 = '-';
					}

					$output->temp1 = $temp1;
					$output->temp2 = $temp2;

					if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
						$output->temp1on = 1;
					} else {
						$output->temp1on = 0;
					}

					if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
						$output->temp2on = 1;
					} else {
						$output->temp2on = 0;
					}
				}
			} else {
				$output->temp_sensors = 0;
			}
			if ($_SESSION['portable'] != '1') {
				$output->portable = 0;
			} else {
				$output->portable = 1;
			}
			$finaloutput[] = $output;
		}
	}
	$ajaxpage = new ajaxpage();
	$ajaxpage->SetResult($finaloutput);
	$ajaxpage->Render();
}

function getdevices() {
	$IST = getIST();
	$devicemanager = new DeviceManager($_SESSION['customerno']);
	$devices = $devicemanager->devicesformapping();
	$finaloutput = array();
	if (isset($devices)) {
		foreach ($devices as $thisdevice) {
			$output = new jsonop();
			$lastupdated2 = strtotime($thisdevice->lastupdated);
			$output->cgeolat = $thisdevice->devicelat;
			$output->cgeolong = $thisdevice->devicelong;
			$output->cname = $thisdevice->vehicleno;
			$output->cdrivername = $thisdevice->drivername;
			$output->cdriverphone = $thisdevice->driverphone;
			$output->cspeed = $thisdevice->curspeed;
			$output->clastupdated = diff($IST, $lastupdated2);
			$output->cvehicleid = $thisdevice->vehicleid;
			$output->image = vehicleimage($thisdevice);
			$finaloutput[] = $output;
		}
	}
	$ajaxpage = new ajaxpage();
	$ajaxpage->SetResult($finaloutput);
	$ajaxpage->Render();
}

function getIST() {

	$IST = strtotime(date("Y-m-d H:i:s"));
	return $IST;
}

function gettemp($rawtemp) {
	$temp = round((($rawtemp - 1150) / 4.45), 2);
	return $temp;
}

function getName($nid) {
	$vehiclemanager = new VehicleManager($_SESSION['customerno']);
	$vehicledata = $vehiclemanager->getNameForTemp($nid);
	return $vehicledata;
}

function getWarehouseMonitorDetails() {
	$arrData = Array();
	$finaloutput = Array();
	$IST = getIST();
	$devicemanager = new DeviceManager($_SESSION['customerno']);
	$tempconversion = new TempConversion();
	$devices = $devicemanager->deviceformappings_wh();

	if (isset($devices)) {

		foreach ($devices as $device) {
			$output = new jsonop();

			$tempconversion->unit_type = $device->get_conversion;
			$lastupdated2 = strtotime($device->lastupdated);
			$output->cgeolat = $device->devicelat;
			$output->cgeolong = $device->devicelong;
			$output->cname = $device->vehicleno;
			$output->clastupdated = diff($IST, $lastupdated2);
			$output->cvehicleid = $device->vehicleid;
			$output->t1 = (getName($device->n1) !== null) ? getName($device->n1) : 'Temperature1';
			$output->t2 = (getName($device->n2) !== null) ? getName($device->n2) : 'Temperature2';
			$output->t3 = (getName($device->n3) !== null) ? getName($device->n3) : 'Temperature3';
			$output->t4 = (getName($device->n4) !== null) ? getName($device->n4) : 'Temperature4';
			$output->t1Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",1," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
			$output->t2Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",2," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
			$output->t3Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",3," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
			$output->t4Link = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",4," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
			$output->image = vehicleimage($device);
			if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
				$output->temp_sensors = $device->temp_sensors;
				if ($device->temp_sensors == 1) {
					// Temperature Sensor
					$temp = 'Not Active';
					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp = getTempUtil($tempconversion);
					} else {
						$temp = '-';
					}

					$output->temp = $temp;
					if ($temp != '-' && $temp != "Not Active") {
						$output->tempon = 1;
					} else {
						$output->tempon = 0;
					}
				}

				if ($device->temp_sensors == 2) {
					$temp1 = 'Not Active';
					$temp2 = 'Not Active';

					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp1 = getTempUtil($tempconversion);
					} else {
						$temp1 = '-';
					}

					$s = "analog" . $device->tempsen2;
					if ($device->tempsen2 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp2 = getTempUtil($tempconversion);
					} else {
						$temp2 = '-';
					}

					$output->temp1 = $temp1;
					$output->temp2 = $temp2;

					if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
						$output->temp1on = 1;
					} else {
						$output->temp1on = 0;
					}

					if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
						$output->temp2on = 1;
					} else {
						$output->temp2on = 0;
					}
				}

				if ($device->temp_sensors == 3) {
					$temp1 = 'Not Active';
					$temp2 = 'Not Active';
					$temp3 = 'Not Active';

					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp1 = getTempUtil($tempconversion);
					} else {
						$temp1 = '-';
					}

					$s = "analog" . $device->tempsen2;
					if ($device->tempsen2 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp2 = getTempUtil($tempconversion);
					} else {
						$temp2 = '-';
					}

					$s = "analog" . $device->tempsen3;
					if ($device->tempsen3 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp3 = getTempUtil($tempconversion);
					} else {
						$temp3 = '-';
					}

					$output->temp1 = $temp1;
					$output->temp2 = $temp2;
					$output->temp3 = $temp3;

					if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
						$output->temp1on = 1;
					} else {
						$output->temp1on = 0;
					}

					if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
						$output->temp2on = 1;
					} else {
						$output->temp2on = 0;
					}

					if ($output->temp3 != '-' && $output->temp3 != "Not Active") {
						$output->temp3on = 1;
					} else {
						$output->temp3on = 0;
					}
				}

				if ($device->temp_sensors == 4) {
					$temp1 = 'Not Active';
					$temp2 = 'Not Active';
					$temp3 = 'Not Active';
					$temp4 = 'Not Active';

					$s = "analog" . $device->tempsen1;
					if ($device->tempsen1 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp1 = getTempUtil($tempconversion);
					} else {
						$temp1 = '-';
					}

					$s = "analog" . $device->tempsen2;
					if ($device->tempsen2 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp2 = getTempUtil($tempconversion);
					} else {
						$temp2 = '-';
					}

					$s = "analog" . $device->tempsen3;
					if ($device->tempsen3 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp3 = getTempUtil($tempconversion);
					} else {
						$temp3 = '-';
					}

					$s = "analog" . $device->tempsen4;
					if ($device->tempsen4 != 0 && $device->$s != 0) {
						$tempconversion->rawtemp = $device->$s;
						$temp4 = getTempUtil($tempconversion);
					} else {
						$temp4 = '-';
					}

					$output->temp1 = $temp1;
					$output->temp2 = $temp2;
					$output->temp3 = $temp3;
					$output->temp4 = $temp4;

					if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
						$output->temp1on = 1;
					} else {
						$output->temp1on = 0;
					}

					if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
						$output->temp2on = 1;
					} else {
						$output->temp2on = 0;
					}

					if ($output->temp3 != '-' && $output->temp3 != "Not Active") {
						$output->temp3on = 1;
					} else {
						$output->temp3on = 0;
					}

					if ($output->temp4 != '-' && $output->temp4 != "Not Active") {
						$output->temp4on = 1;
					} else {
						$output->temp4on = 0;
					}
				}
			} else {
				$output->temp_sensors = 0;
			}
			if ($device->use_humidity == 1 && $device->humidity != 0) {
				// Temperature Sensor
				$humidity = 'Not Active';
				$s = "analog" . $device->humidity;
				if ($device->$s != 0) {
					$tempconversion->use_humidity = $device->use_humidity;
					$tempconversion->switch_to = 3;
					$tempconversion->rawtemp = $device->$s;
					$temp = getTempUtil($tempconversion);
				} else {
					$humidity = '-';
				}

				$output->$humidity = $humidity;

			}

			$arrData[$device->vehicleid] = $output;
		}
	}
	$arrDataList = array();
	if (isset($arrData) && !empty($arrData)) {
		foreach ($arrData as $vehicleid => $vehicleData) {
			$arrClub = array();
			$arrClub[] = $vehicleData;

			$arrClubMapping = getWarehouseClubMapping($vehicleid);
			if (isset($arrClubMapping) && !empty($arrClubMapping)) {
				foreach ($arrClubMapping as $key => $club) {
					$arrClub[] = $arrData[$club['childId']];
					//$arrDataList[] = $arrClub;
					unset($arrData[$club['childId']]);
					unset($arrDataList[$club['childId']]);
				}
			}
			$arrDataList[$vehicleid] = $arrClub;

		}
	}

	//print_r($arrDataList); die();
	//echo json_encode($arrDataList);
	$ajaxpage = new ajaxpage();
	$ajaxpage->SetResult($arrDataList);
	$ajaxpage->Render();
}

function getWarehouseClubMapping($vehicleid) {
	$arrClubMapping = array();
	$devicemanager = new DeviceManager($_SESSION['customerno']);
	$arrClubMapping = $devicemanager->getWarehouseClubMapping($vehicleid, $_SESSION['customerno']);
	return $arrClubMapping;
}

function getCheckPointsByCheckPointId($checkPointId) {
	$chkptmanager = new CheckpointManager($_SESSION['customerno']);
	return $chkptmanager->getCheckPointsByCheckPointId($checkPointId);
}

if (isset($_POST['action']) && $_POST['action'] == 'fetchCheckPoints' && isset($_POST['checkPointTypeId'])) {
	echo json_encode(getCheckPointsByCheckPointId($_POST['checkPointTypeId']));
}

function get_vehicle_details($vehId) {
	$vm = new VehicleManager($_SESSION['customerno']);
	$data = $vm->get_vehicle_details($vehId);
	return $data;
}

function getRadarDetails($vehicleId) {
	$objRadar = new stdClass();
	$objRadar->vehicleNo = 'NL 01 N 5139';
	$objRadar->group = 'Reehal';
	$objRadar->from = 'Mumbai';
	$objRadar->to = 'Delhi';
	$objRadar->scheduleFrom = '12:25 PM';
	$objRadar->scheduleTo = '10:40 AM';
	$objRadar->actualFrom = '12:50 PM';
	$objRadar->actualTo = '11:10 AM';
	$objRadar->totalDistance = '1423';
	$objRadar->travelledDistance = '600';
	$objRadar->realtimeLocation = 'Near Bhiwandi - Wada Rd,Vadunavaghar, Maharashtra 421302, India';
	$objRadar->speed = '32 km/r';
	$objRadar->realtimeLocation = 'Near Bhiwandi - Wada Rd,Vadunavaghar, Maharashtra 421302, India';
	echo json_encode($objRadar);
}

?>
