<?php
include_once '../../lib/system/Date.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/ElixiaCodeManager.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/components/ajaxpage.inc.php';
include_once '../common/map_common_functions.php';
include_once("../../lib/bo/CustomerManager.php");

class jsonop {}

if(!isset($_SESSION))
{
    session_start();
}

function sendMail( $to, $subject , $content)
{

    $subject = $subject;

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;
}

function sendSMS($phone, $message, $customerno) {
    $moduleid = speedConstants::MODULE_VTS;
    $cm = new CustomerManager();
    $sms = $cm->pullsmsdetails($customerno);
        if ($sms->smsleft == 20) {
            $cqm = new ComQueueManager();
            $cvo = new VOComQueue();
            $cvo->phone = $phone;
            $cvo->message = "Your SMS pack is too low. Please contact an Elixir. Powered by Elixia Tech.";
            $cvo->subject = "";
            $cvo->email = "";
            $cvo->type = 1;
            $cvo->customerno = $customerno;
            $cqm->InsertQ($cvo);
        }
        $response = '';
        $isSMSSent = sendSMSUtil($phone, $message, $response);
        if ($isSMSSent == 1) {
            $smsId = $cm->sentSmsPostProcess($customerno, array($phoneno), $message, $response, $isSMSSent, $_SESSION['userid'],0, $moduleid);
        }
        if ($isSMSSent == 1) {
            return true;
        }else{
            return false;
        }
}


function getcurrentdate()
{
    $currentdate = strtotime(date("Y-m-d H:i:s"));
    $currentdate = substr($currentdate, '0',11);
    return $currentdate;
}
function getvehicles()
{
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehicles();
    return $vehicles;
}
function generateecode($FormValues)
{
    $ECM = new ElixiaCodeManager($_SESSION['customerno']);

    $SelectedDate = GetSafeValueString($FormValues['SDate'], 'string');
    $SelectedTime = GetSafeValueString($FormValues['STime'], 'string');
    $SelectedDate = date('Y-m-d',  strtotime($SelectedDate));

    $EndDate = GetSafeValueString($FormValues['EDate'], 'string');
    $EndTime = GetSafeValueString($FormValues['ETime'], 'string');
    $EndDate = date('Y-m-d',  strtotime($EndDate));

    $days = GetSafeValueString($FormValues['days'], 'string');
    $days = isset($days)?$days:0;
    $CurrentDate = GetSafeValueString($FormValues['currentdate'], 'string');
    $CurrentDate = date('Y-m-d H:i:s', strtotime($CurrentDate));

    $SelectedDateTime = $SelectedDate." ".$SelectedTime.":00";
    $EndDateTime = $EndDate." ".$EndTime.":00";
    $checkbox = $FormValues['checkbox'];
    //print_r($checkbox);
    $menuoptions = array_sum($checkbox);
    $Vehicles = array();
    foreach($FormValues as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 11) == "to_vehicle_")
            $Vehicles[] = substr($single_post_name, 11, 12);
    }

    $ecoderandom = GetSafeValueString($FormValues['randomecode'], 'string');
    $ecodemail = GetSafeValueString($FormValues['email'], 'string');
    $ecodesms = GetSafeValueString($FormValues['sms'], 'string');
    $ECode = new VOElixiaCode();
    $ECode->ecode = $ecoderandom;
    $ECode->email = $ecodemail;
    $ECode->sms = $ecodesms;
    $ECode->datecreated = $CurrentDate;
    $ECode->startdate = $SelectedDateTime;
    $ECode->expirydate = $EndDateTime;
    $ECode->vehicles = $Vehicles;
    $ECode->menu = $menuoptions;
    $ECode->days = $days;

    $ecodeid = $ECM->SaveElixiaCode($ECode, $_SESSION['userid']);

    $shortUrl = "http://speed.elixiatech.com/modules/map/mapdashboard.php?clientcode=".$ecoderandom;
    $objShort = new ShorturlManager();

    $shortUrl = $objShort->urlToShortCode($shortUrl);
    $surl = "http://speed.elixiatech.com/modules/shorturl/r.php?c=".$shortUrl;
    //echo $surl; die();
    if(isset($ecodemail) && $ecodemail != "")
    {
        $subject = 'Client Code';
        $message = 'Your Client Code is '.$ecoderandom.'. <br/>Generated by '.$_SESSION['realname'].'. <br/>Please visit www.speed.elixiatech.com and trace your vehicle. <br/> Valid Until: '.convertDateToFormat($SelectedDateTime,speedConstants::DEFAULT_DATETIME).'<br/> Track Vehicles : '.$surl.'';
        if(sendMail($ecodemail, $subject, $message) == true)
        {
            $cvo = new VOElixiaCode();
            $cvo->ecodeid = $ecodeid;
            $cvo->comdet = $ecodemail;
            $cvo->userid = $_SESSION['userid'];
            $cvo->customerno = $_SESSION['customerno'];
            $cvo->type = 0;
            $ECM->InsertEcodeHistory($cvo);
        }
    }
    if(isset($ecodesms) && $ecodesms != "")
    {
        $smsmessage = 'Client Code: '.$ecoderandom.'. Generated by '.$_SESSION['realname'].'. Validity: '.convertDateToFormat($SelectedDateTime,speedConstants::DEFAULT_DATETIME).'. Login URL: www.speed.elixiatech.com Track Vehicles : '.$surl.'';
        if(sendSMS($ecodesms, $smsmessage, $_SESSION['customerno']) == true)
        {
            $cvo = new VOElixiaCode();
            $cvo->ecodeid = $ecodeid;
            $cvo->comdet = $ecodesms;
            $cvo->userid = $_SESSION['userid'];
            $cvo->customerno = $_SESSION['customerno'];
            $cvo->type = 1;
            $ECM->InsertEcodeHistory($cvo);
        }
    }
}


function updateecode($FormValues)
{
    $ECM = new ElixiaCodeManager($_SESSION['customerno']);



    $SelectedDate = GetSafeValueString($FormValues['SDate'], 'string');
    $SelectedTime = GetSafeValueString($FormValues['STime'], 'string');
    $SelectedDate = date('Y-m-d',  strtotime($SelectedDate));

    $EndDate = GetSafeValueString($FormValues['EDate'], 'string');
    $EndTime = GetSafeValueString($FormValues['ETime'], 'string');
    $EndDate = date('Y-m-d',  strtotime($EndDate));

    $CurrentDate = GetSafeValueString($FormValues['currentdate'], 'string');
    $CurrentDate = date('Y-m-d H:i:s', strtotime($CurrentDate));

    $SelectedDateTime = $SelectedDate." ".$SelectedTime.":00";
    $EndDateTime = $EndDate." ".$EndTime.":00";
    $checkbox = $FormValues['checkbox'];
    $days = $FormValues['days'];
    //print_r($checkbox);
    $menuoptions = array_sum($checkbox);
    $Vehicles = array();
    foreach($FormValues as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 11) == "to_vehicle_")
            $Vehicles[] = substr($single_post_name, 11, 12);
    }

    $ecoderandom = GetSafeValueString($FormValues['randomecode'], 'string');
    $ecodemail = GetSafeValueString($FormValues['email'], 'string');
    $ecodesms = GetSafeValueString($FormValues['sms'], 'string');
    $ECode = new VOElixiaCode();
    $ECode->eid = GetSafeValueString($FormValues['e_id'], 'string');;
    $ECode->ecode = $ecoderandom;
    $ECode->email = $ecodemail;
    $ECode->sms = $ecodesms;
    $ECode->startdate = $SelectedDateTime;
    $ECode->expirydate = $EndDateTime;
    $ECode->vehicles = $Vehicles;
    $ECode->menu = $menuoptions;
    $ECode->days = $days;
    $ecodeid = $ECM->UpdateElixiaCode($ECode, $_SESSION['userid']);
    if(isset($ecodemail) && $ecodemail != "")
    {
        $subject = 'Client Code';
        $message = 'Your Client Code is '.$ecoderandom.'. <br/>Generated by '.$_SESSION['realname'].'. <br/>Please visit www.speed.elixiatech.com and trace your vehicle. <br/> Valid Until: '.convertDateToFormat($SelectedDateTime,speedConstants::DEFAULT_DATETIME);
        if(sendMail($ecodemail, $subject, $message) == true)
        {
            $cvo = new VOElixiaCode();
            $cvo->ecodeid = $ecodeid;
            $cvo->comdet = $ecodemail;
            $cvo->userid = $_SESSION['userid'];
            $cvo->customerno = $_SESSION['customerno'];
            $cvo->type = 0;
            $ECM->InsertEcodeHistory($cvo);
        }
    }
    if(isset($ecodesms) && $ecodesms != "")
    {
        $smsmessage = 'Client Code: '.$ecoderandom.'. Generated by '.$_SESSION['realname'].'. Validity: '.convertDateToFormat($SelectedDateTime,speedConstants::DEFAULT_DATETIME).'. Link: www.speed.elixiatech.com';
        if(sendSMS($ecodesms, $smsmessage, $_SESSION['customerno']) == true)
        {
        $cvo = new VOElixiaCode();
        $cvo->ecodeid = $ecodeid;
        $cvo->comdet = $ecodesms;
        $cvo->userid = $_SESSION['userid'];
        $cvo->customerno = $_SESSION['customerno'];
        $cvo->type = 1;
        $ECM->InsertEcodeHistory($cvo);
        }
    }
}

function viewecodes()
{
 $ECM = new ElixiaCodeManager($_SESSION['customerno']);
  $codes = $ECM->getelixiacodesforcustomer();
  $codeswithvehicles = $ECM->getecodedvehicles();
  if(isset($codes))
  {
      foreach ($codes as $code)
      {
          echo "<tr>";
          echo "<td>";
          ?>
  <a href = 'route.php?ecodeid=<?php echo($code->id); ?>' onclick="return confirm('Are you sure you want to delete?');">
          <?php
          echo "<img src = '../../../images/delete.png' alt='delete' title='Delete'/>
              </a>
              </td>";
          echo "<td>".convertDateToFormat($code->datecreated,speedConstants::DEFAULT_DATETIME)."</td>";
          echo "<td>$code->ecode</td>";

          if($code->startdate !='0000-00-00 00:00:00')
          {
                echo "<td>".convertDateToFormat($code->startdate,speedConstants::DEFAULT_DATETIME)."</td>";
          }
          else
          {
               echo "<td>N/A</td>";
          }

          echo "<td>".convertDateToFormat($code->expirydate,speedConstants::DEFAULT_DATETIME)."</td>";
          echo "<td>$code->status</td>";
          if($code->ecodeemail !='')
          {
               echo "<td>$code->ecodeemail</td>";
          }
          else
          {
               echo "<td>N/A</td>";
          }
          if($code->ecodesms > 0)
          {
               echo "<td>$code->ecodesms</td>";
          }
          else
          {
               echo "<td>N/A</td>";
          }
          $counter = 0;
          $vehicles = NULL;
          foreach ($codeswithvehicles as $vehicle)
          {
              if($code->id == $vehicle->id)
              {
                  $vehicles .= $vehicle->vehicleno.", ";
              }
              $counter += 1;
          }
          $vehicles = rtrim($vehicles, ' ,');
          echo "<td>$vehicles</td>";
          echo "<td>".$code->days."</td>";

          echo "<td><a href='ecode.php?id=3&ecode=$code->id'><img src = '../../images/edit.png' alt='delete' title='Edit'/></a></td>";
          echo "</tr>";
      }
  }
 else
   echo '<tr><td colspan=100%>No Codes Created</td></tr>';
}

function deleteecode($ecodeid)
{
    $ecodeid = GetSafeValueString($ecodeid, 'long');
    $ECM = new ElixiaCodeManager($_SESSION['customerno']);
    $ECM->DeleteElixiaCode($ecodeid,$_SESSION['userid']);
    return true;

}

function deleteecodebyvehicleid($ecodeid,$vehicleid)
{
    $ecodeid = GetSafeValueString($ecodeid, 'long');
    $vehicleid = GetSafeValueString($vehicleid, 'long');
    $ECM = new ElixiaCodeManager($_SESSION['customerno']);
    $ECM->DeleteElixiaCodeByVehicle($ecodeid,$_SESSION['userid'],$vehicleid);
}

function getvehiclesforecode($ecodeid)
{
    $devicemanager = new DeviceManager(0);
    $devices = $devicemanager->devicesformappingwithecode($ecodeid);

    $finaloutput = array();
    if(isset($devices))
    {
        foreach ($devices as $device)
        {
            $finaloutput[] = vehicleformap($device);
        }
    }
    renderformap($finaloutput);
}
function getvehicleforecode($vehicleid)
{
    $devicemanager = new DeviceManager(0);
    $device = $devicemanager->deviceformappingforecode($vehicleid);
    $finaloutput = array();
    if(isset($device))
    {
        $finaloutput[] = vehicleformap($device);
    }
    renderformap($finaloutput);
}
function vehicleformap($device)
{
    $output = new jsonop();
    $date = new DateTime($device->lastupdated);
    $output->cgeolat = $device->devicelat;
    $output->cgeolong = $device->devicelong;
    $output->cname = $device->vehicleno;
    $output->cdrivername = $device->drivername;
    $output->cdriverphone = $device->driverphone;
    $output->cspeed = $device->curspeed;
    $output->clastupdated = $date->format('D d-M-Y H:i');
    $output->cvehicleid = $device->vehicleid;
    $output->image = vehicleimages($device);
    return $output;
}
function vehicleimages($device)
{
    $date = new Date();
    $ServerIST_less1 = $date->add_hours(date("Y-m-d H:i:s"), 0);
    $basedir = "images/vehicles/";
    $directionfile = round($device->directionchange/10);
    if($device->type=='Car' || $device->type=='Cab')
    {
        $device->type='Car';
        if ($device->ignition=='0')
        {
            if($device->lastupdated < $ServerIST_less1)
            {
                $image = $device->type."/Idle/breakdown.png";
            }
            else{
            $image = $device->type."/Idle/".$device->type.$directionfile.".png";
            }
        }
        else
        {
            if($device->status == 'B' || $device->status == 'D')
            {
                $image = $device->type."/Overspeed/".$device->type.$directionfile.".png";
            }
            else
            {
                $image = $device->type."/Normal/".$device->type.$directionfile.".png";
            }
        }
    }
    else if($device->type=='Bus')
    {
        if ($device->ignition=='0')
        {
            $image = $device->type."/Idle/".$device->type.$directionfile.".png";
        }
        else
        {
            if($device->status == 'B' || $device->status == 'D')
            {
                $image = $device->type."/Overspeed/".$device->type.$directionfile.".png";
            }
            else
            {
                $image = $device->type."/Normal/".$device->type.$directionfile.".png";
            }
        }
    }
    else
    {
        if ($device->ignition=='0')
        {
            $image = $device->type."/".$device->type."I.png";
        }
        else
        {
            if($device->status == 'B' || $device->status == 'D')
            {
                $image = $device->type."/".$device->type."O.png";
            }
            else
            {
                $image = $device->type."/".$device->type."N.png";
            }
        }
    }
    $image = $basedir.$image;
    return $image;
}
function renderformap($finaloutput)
{
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function getElixiaCode($code)
{
    $ECM = new ElixiaCodeManager($_SESSION['customerno']);
    $code = $ECM->getElixiaCode($code);
    return $code;

}

function getElixiaCodeVehicles($code)
{
    $ECM = new ElixiaCodeManager($_SESSION['customerno']);
    $vehicle = $ECM->getCodeVehicle($code);
    return $vehicle;

}
?>
