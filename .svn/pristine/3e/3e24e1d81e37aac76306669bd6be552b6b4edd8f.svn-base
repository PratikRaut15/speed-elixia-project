<?php
define('inputTypeNumeric', '.1234567890') ;

function myAddSlashes($string)
{
	if (get_magic_quotes_gpc() == 1) {
		return trim($string);
	} else {
		return addslashes(trim($string));
	}
}

function withoutColon( $string )
{

    $istring = trim($string);
    $parts = explode(":",$istring);
    if(count($parts)>1)
    {
        if($parts[1]=="")
        {
            return $parts[0];
        }
    }
    return $string;

}

function VisibleStyle( $show )
{
    if(!$show)
    {
        return "display:none";
    }
    else
    {
        return "";
    }
}

function isNumeric($value) {
	$isNumeric = false;
	
	if(strlen($value) == strspn($value, inputTypeNumeric))
	{
		$isNumeric = true;
	}
	
	return $isNumeric;
}

function StartsWith($Haystack, $Needle){
    // Recommended version, using strpos
    return strpos($Haystack, $Needle) === 0;
}

function StartsWithVowel($Haystack)
{
    $UHaystack= strtoupper( $Haystack);
    return (StartsWith( $UHaystack,"A") ||StartsWith( $UHaystack,"E") ||StartsWith( $UHaystack,"I") ||StartsWith( $UHaystack,"O") ||StartsWith( $UHaystack,"U") );
}

function EndsWith($Haystack, $Needle){
    // Recommended version, using strpos
    return strpos(strrev($Haystack), strrev($Needle)) === 0;
}

function today()
{
    $t=getdate();
    return date('Y-m-d',$t[0]);
}

function MakeMySQLDate( $inDate )
{
    $date = date_parse($inDate);
    return sprintf("%04d-%02d-%02d", $date['year'], $date['month'], $date['day']);
}

function MySQLNow()
{
    $tz = GetTimeZone();
    if (isset($tz))
    {
       $t=datefortz($tz);
       return date('Y-m-d H:i:s',strtotime($t));
    }
    else
    {
        $t=getdate();
        return date( 'Y-m-d H:i:s', $t[0] );
    }
}

function FilenameNow()
{
    // This function gets a timestamp for the file.
    // It's not based on the customer's timezone but the servers.
    // This is OK.
    
    $t=getdate();
    return date( 'Y_m_d_H_i_s', $t[0] );
}

function DateToMySQL( $inDate )
{
    $t= date_parse($inDate);
    return date( 'Y-m-d H:i:s', $t[0] );

}

function JustDate( $inDate )
{
    $t= strtotime($inDate);
    return date( 'Y-m-d', $t );

}

function localformat( $inDate )
{
    $t= strtotime($inDate);
    return date( 'm/d/Y', $t ); //TODO: Get the local format from a localization file.
}

function FormattedDateTime( $inDate )
{

    $t = strtotime($inDate);
    $display_date = date("l j F Y h:i a",$t);

    return $display_date;

}

function LongFormattedDate( $inDate )
{

    $t = strtotime($inDate);
   // list($yr,$mon,$day) = split('-',$inDate);
$display_date = date("l j F Y",$t);
//$display_date = date('D j M Y', mktime(0,0,0,$mon,$day,$yr));
    return $display_date;

}
function FormattedDate($inDate )
{
    list($yr,$mon,$day) = split('-',$inDate);

$display_date = date('D j M Y', mktime(0,0,0,$mon,$day,$yr));
    return $display_date;
}

function DatePart($inDate )
{
    list($yr,$mon,$day) = split('-',$inDate);

$display_date = date('Y-m-d', mktime(0,0,0,$mon+0,$day+0,$yr+0));
    return $display_date;
}

function DateToPath($inDate)
{
    $date = date_parse($inDate);
    return sprintf("%04d/%02d/%02d/", $date['year'], $date['month'], $date['day']);

}

function probablyAlreadyEscaped( $theValue )
{
    // Check to see if the value has probably been escaped.
    // So if there's a ['] without a [\'], it's not been escaped.
    // If there's a [\] without a [\\] or a [\'] then we have a rogue \ and it's not been escaped.
    

    if(strpos($theValue,"'")!==false && strpos($theValue,"\'")===false)
    {
        return false;
    }
    if(strpos($theValue,"\\")!==false && strpos($theValue,"\'")===false && strpos($theValue,"\\\\")===false)
    {
        return false;
    }
    if((strpos($theValue, "\'")!== false))
    {
        return true;
    }
    if((strpos($theValue,"\\\\")!==false))
    {
        return true;
    }
    return false;
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType="string", $theDefinedValue = "", $theNotDefinedValue = "")
{
    if(!probablyAlreadyEscaped($theValue))
    {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
    }
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
	 case "string":
      $theValue = ($theValue != "") ? "" . $theValue . "" : "";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "0";
      break;
	case "csvi":
		$theArray = explode(",",$theValue);
		$resArray = NULL;
		foreach($theArray as $a)
		{
			$resArray[] = GetSafeValueString($a,"int");
		}
      $theValue = implode(",",$resArray); // Now we've sanitised the array...
      break;
	case "csv":
		$theArray = explode(",",$theValue);
		$resArray = NULL;
		foreach($theArray as $a)
		{
			$resArray[] = GetSafeValueString($a,"string");
		}
      $theValue = implode(",",$resArray); // Now we've sanitised the array...
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "0";
      break;
    case "date":
       $theValue = ($theValue != "") ? $theValue : "";
       break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if (!function_exists("GetSafeValueString")) {
function GetSafeValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  if(!probablyAlreadyEscaped($theValue))
  {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "";
      break;
	case "string":
      $theValue = ($theValue != "") ? "" . $theValue . "" : "";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
	case "csvi":
		$theArray = explode(",",$theValue);
		$resArray = NULL;
		foreach($theArray as $a)
		{
			$resArray[] = GetSafeValueString($a,"int");
		}
      $theValue = implode(",",$resArray); // Now we've sanitised the array...
      break;

	case "csv":
		$theArray = explode(",",$theValue);
		$resArray = NULL;
		foreach($theArray as $a)
		{
			$resArray[] = GetSafeValueString($a,"string");
		}
      $theValue = implode(",",$resArray); // Now we've sanitised the array...
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "0";
      break;
    case "date":
       $theValue = ($theValue != "") ? $theValue : "";
       break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
    case 'bool':
    	$theValue = strcasecmp($theValue, 'true') == 0;
    	break;
  }
  return $theValue;
}
}

function add_days($my_date,$numdays) {
  $date_t = strtotime($my_date.' UTC');
  return gmdate('Y-m-d  H:i:s',$date_t + ($numdays*86400));
}

function add_date($givendate,$day=0,$mth=0,$yr=0) {
      $cd = strtotime($givendate);
      $newdate = gmdate('Y-m-d h:i:s', mktime(date('h',$cd),
    gmdate('i',$cd), gmdate('s',$cd), gmdate('m',$cd)+$mth,
    gmdate('d',$cd)+$day, gmdate('Y',$cd)+$yr));
      return $newdate;
              }

function add_hours($my_date,$numhours) {
  $date_t = strtotime($my_date.' UTC');
  return gmdate('Y-m-d  H:i:s',$date_t + ($numhours*3600));
}

function add_mins($my_date,$nummins) {
  $date_t = strtotime($my_date.' UTC');
  return gmdate('Y-m-d  H:i:s',$date_t + ($nummins*60));
}

//There may be bugs in this script.. I got it from http://www.ilovejackdaniels.com/php/php-datediff-function/
// Check for updates.
function sqbx_date_diff($interval, $datefrom, $dateto, $using_timestamps = false) {
/*
$interval can be:
yyyy - Number of full years
q - Number of full quarters
m - Number of full months
y - Difference between day numbers
(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
d - Number of full days
w - Number of full weekdays
ww - Number of full weeks
h - Number of full hours
n - Number of full minutes
s - Number of full seconds (default)
*/

if (!$using_timestamps) {
$datefrom = strtotime($datefrom, 0);
$dateto = strtotime($dateto, 0);
}
$difference = $dateto - $datefrom; // Difference in seconds

switch($interval) {

case 'yyyy': // Number of full years

$years_difference = floor($difference / 31536000);
if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
$years_difference--;
}
if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
$years_difference++;
}
$datediff = $years_difference;
break;

case "q": // Number of full quarters

$quarters_difference = floor($difference / 8035200);
while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
$months_difference++;
}
$quarters_difference--;
$datediff = $quarters_difference;
break;

case "m": // Number of full months

$months_difference = floor($difference / 2678400);
while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
$months_difference++;
}
$months_difference--;
$datediff = $months_difference;
break;

case 'y': // Difference between day numbers

$datediff = date("z", $dateto) - date("z", $datefrom);
break;

case "d": // Number of full days

$datediff = floor($difference / 86400);
break;

case "w": // Number of full weekdays

$days_difference = floor($difference / 86400);
$weeks_difference = floor($days_difference / 7); // Complete weeks
$first_day = date("w", $datefrom);
$days_remainder = floor($days_difference % 7);
$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
if ($odd_days > 7) { // Sunday
$days_remainder--;
}
if ($odd_days > 6) { // Saturday
$days_remainder--;
}
$datediff = ($weeks_difference * 5) + $days_remainder;
break;

case "ww": // Number of full weeks

$datediff = floor($difference / 604800);
break;

case "h": // Number of full hours

$datediff = floor($difference / 3600);
break;

case "n": // Number of full minutes

$datediff = floor($difference / 60);
break;

default: // Number of full seconds (default)

$datediff = $difference;
break;
}

return $datediff;

}

function PrepNumber( $SMSnumber )
{
    return str_replace(" ","",str_replace("-","",trim($SMSnumber)));
}

function ParseDateForTokens( $inDate )
{
    $year = date('Y');
    $month= date('n');
    $begin=mktime(0,0,0,$month,1,$year);
    $end=strtotime("+1month -1day",$begin);
    if (strtoupper($inDate) == "STARTOFMONTH" || strtoupper($inDate) == "START_OF_MONTH")
    {
        return date('Y/m/d',$begin);
    }
    if (strtoupper($inDate) == "ENDOFMONTH" || strtoupper($inDate) == "END_OF_MONTH")
    {
        return date('Y/m/d',$end);
    }
    if (strtoupper($inDate) == "STARTOFLASTMONTH" || strtoupper($inDate) == "START_OF_LAST_MONTH")
    {
        return date('Y/m/d',strtotime("-1month",$begin));
    }
    if (strtoupper($inDate) == "ENDOFLASTMONTH" || strtoupper($inDate) == "END_OF_LAST_MONTH")
    {
        return date('Y/m/d',strtotime("-1day",$begin));
    }
    return $inDate;
}

function BuildDatePath( $date, $signImagesFolder, $create=true )
{
    $datepath = DateToPath($date);
    $newfolderpath = $signImagesFolder . $datepath;
    // make sure the directory exists with the correct permissions
    if(!is_dir( $newfolderpath ) && $create )
    {
        // Directory doesn't exist.
        mkdir($newfolderpath, 0777, true);
    }
    return $newfolderpath;
}


function BuildSignatureFilename($receiptid, $historyid)
{
    $hist = ( (isset($historyid)  && $historyid != 0) ? "_" . $historyid : "");

    // return the full path with the file name
    return  $receiptid . $hist .".jpg";
}


class sorter {
    static private $sortfield = null;
    static private $sortorder = 1;
    static private function sort_callback(&$a, &$b) {
        $sorter=self::$sortfield;
        if($a->$sorter == $b->$sorter) return 0;
        return ($a->$sorter < $b->$sorter)? -self::$sortorder : self::$sortorder;
    }
    static function sort(&$v, $field, $asc=true) {
        self::$sortfield = $field;
        self::$sortorder = $asc? 1 : -1;
        usort($v, array('sorter', 'sort_callback'));
    }
}


?>