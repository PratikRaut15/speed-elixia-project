<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'config.inc.php';
include_once $RELATIVE_PATH_DOTS . 'constants/speedConstants.php';
date_default_timezone_set('Asia/Kolkata');
define('inputTypeNumeric', '.1234567890');
$freeze_radius = '0.04'; //40 meter
define("FREEZE_RADIUS", $freeze_radius);
$doLogging = true; //used in cron all for freeze notifications and mails
define('doLogging', $doLogging);
function myAddSlashes($string) {
    if (get_magic_quotes_gpc() == 1) {
        return trim($string);
    } else {
        return addslashes(trim($string));
    }
}

function withoutColon($string) {
    $istring = trim($string);
    $parts = explode(":", $istring);
    if (count($parts) > 1) {
        if ($parts[1] == "") {
            return $parts[0];
        }
    }
    return $string;
}

function VisibleStyle($show) {
    if (!$show) {
        return "display:none";
    } else {
        return "";
    }
}

function isNumeric($value) {
    $isNumeric = false;
    if (strlen($value) == strspn($value, inputTypeNumeric)) {
        $isNumeric = true;
    }
    return $isNumeric;
}

function StartsWith($Haystack, $Needle) {
    // Recommended version, using strpos
    return strpos($Haystack, $Needle) === 0;
}

function StartsWithVowel($Haystack) {
    $UHaystack = strtoupper($Haystack);
    return (StartsWith($UHaystack, "A") || StartsWith($UHaystack, "E") || StartsWith($UHaystack, "I") || StartsWith($UHaystack, "O") || StartsWith($UHaystack, "U"));
}

function EndsWith($Haystack, $Needle) {
    // Recommended version, using strpos
    return strpos(strrev($Haystack), strrev($Needle)) === 0;
}

function today() {
    $t = getdate();
    return date('Y-m-d H:i:s', $t[0]);
}

function MakeMySQLDate($inDate) {
    $date = date_parse($inDate);
    return sprintf("%04d-%02d-%02d", $date['year'], $date['month'], $date['day']);
}

function MySQLNow() {
    $tz = GetTimeZone();
    if (isset($tz)) {
        $t = datefortz($tz);
        return date('Y-m-d H:i:s', strtotime($t));
    } else {
        $t = getdate();
        return date('Y-m-d H:i:s', $t[0]);
    }
}

function FilenameNow() {
    // This function gets a timestamp for the file.
    // It's not based on the customer's timezone but the servers.
    // This is OK.
    $t = getdate();
    return date('Y_m_d_H_i_s', $t[0]);
}

function DateToMySQL($inDate) {
    $t = date_parse($inDate);
    return date('Y-m-d H:i:s', $t[0]);
}

function JustDate($inDate) {
    $t = strtotime($inDate);
    return date('Y-m-d', $t);
}

function localformat($inDate) {
    $t = strtotime($inDate);
    return date('m/d/Y', $t); //TODO: Get the local format from a localization file.
}

function FormattedDateTime($inDate) {
    $t = strtotime($inDate);
    $display_date = date("l j F Y h:i a", $t);
    return $display_date;
}

function LongFormattedDate($inDate) {
    $t = strtotime($inDate);
    // list($yr,$mon,$day) = split('-',$inDate);
    $display_date = date("l j F Y", $t);
    //$display_date = date('D j M Y', mktime(0,0,0,$mon,$day,$yr));
    return $display_date;
}

function FormattedDate($inDate) {
    list($yr, $mon, $day) = split('-', $inDate);
    $display_date = date('D j M Y', mktime(0, 0, 0, $mon, $day, $yr));
    return $display_date;
}

function DatePart($inDate) {
    list($yr, $mon, $day) = split('-', $inDate);
    $display_date = date('Y-m-d', mktime(0, 0, 0, $mon + 0, $day + 0, $yr + 0));
    return $display_date;
}

function DateToPath($inDate) {
    $date = date_parse($inDate);
    return sprintf("%04d/%02d/%02d/", $date['year'], $date['month'], $date['day']);
}

function IsValidDate($inDate) {
    $isDateValid = 0;
    $arrResult = date_parse($inDate);
    if ($arrResult["error_count"] == 0 && $arrResult["warning_count"] == 0) {
        $isDateValid = 1;
    }
    return $isDateValid;
}

function convertDateToFormat($dateTime, $format = null) {
    $formattedDate = null;
    if (isset($format)) {
        $formattedDate = date($format, strtotime($dateTime));
    } else {
        $formattedDate = date(speedConstants::DEFAULT_DATETIME, strtotime($dateTime));
    }
    return $formattedDate;
}

function probablyAlreadyEscaped($theValue) {
    // Check to see if the value has probably been escaped.
    // So if there's a ['] without a [\'], it's not been escaped.
    // If there's a [\] without a [\\] or a [\'] then we have a rogue \ and it's not been escaped.
    if (strpos($theValue, "'") !== false && strpos($theValue, "\'") === false) {
        return false;
    }
    if (strpos($theValue, "\\") !== false && strpos($theValue, "\'") === false && strpos($theValue, "\\\\") === false) {
        return false;
    }
    if ((strpos($theValue, "\'") !== false)) {
        return true;
    }
    if ((strpos($theValue, "\\\\") !== false)) {
        return true;
    }
    return false;
}

if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType = "string", $theDefinedValue = "", $theNotDefinedValue = "") {
        if (!probablyAlreadyEscaped($theValue)) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
            //Connection created to use mysqli_real_escape_string and closed after use
            $tempConnection = new mysqli(DB_HOST, DB_LOGIN, DB_PWD, SPEEDDB);
            $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($tempConnection, $theValue) : mysqli_escape_string($tempConnection, $theValue);
            mysqli_close($tempConnection);
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
                $theArray = explode(",", $theValue);
                $resArray = NULL;
                foreach ($theArray as $a) {
                    $resArray[] = GetSafeValueString($a, "int");
                }
                $theValue = implode(",", $resArray); // Now we've sanitised the array...
                break;
            case "csv":
                $theArray = explode(",", $theValue);
                $resArray = NULL;
                foreach ($theArray as $a) {
                    $resArray[] = GetSafeValueString($a, "string");
                }
                $theValue = implode(",", $resArray); // Now we've sanitised the array...
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
    function GetSafeValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        if (!probablyAlreadyEscaped($theValue)) {
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
                $theArray = explode(",", $theValue);
                $resArray = NULL;
                foreach ($theArray as $a) {
                    $resArray[] = GetSafeValueString($a, "int");
                }
                $theValue = implode(",", $resArray); // Now we've sanitised the array...
                break;
            case "csv":
                $theArray = explode(",", $theValue);
                $resArray = NULL;
                foreach ($theArray as $a) {
                    $resArray[] = GetSafeValueString($a, "string");
                }
                $theValue = implode(",", $resArray); // Now we've sanitised the array...
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
function add_days($my_date, $numdays) {
    $date_t = strtotime($my_date . ' UTC');
    return gmdate('Y-m-d  H:i:s', $date_t + ($numdays * 86400));
}

function add_date($givendate, $day = 0, $mth = 0, $yr = 0) {
    $cd = strtotime($givendate);
    $newdate = gmdate('Y-m-d h:i:s', mktime(date('h', $cd), gmdate('i', $cd), gmdate('s', $cd), gmdate('m', $cd) + $mth, gmdate('d', $cd) + $day, gmdate('Y', $cd) + $yr));
    return $newdate;
}

function add_hours($my_date, $numhours) {
    $date_t = strtotime($my_date . ' UTC');
    return gmdate('Y-m-d  H:i:s', $date_t + ($numhours * 3600));
}

function add_mins($my_date, $nummins) {
    $date_t = strtotime($my_date . ' UTC');
    return gmdate('Y-m-d  H:i:s', $date_t + ($nummins * 60));
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
    switch ($interval) {
        case 'yyyy':
            // Number of full years
            $years_difference = floor($difference / 31536000);
            if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom) + $years_difference) > $dateto) {
                $years_difference--;
            }
            if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto) - ($years_difference + 1)) > $datefrom) {
                $years_difference++;
            }
            $datediff = $years_difference;
            break;
        case "q":
            // Number of full quarters
            $quarters_difference = floor($difference / 8035200);
            while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($quarters_difference * 3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
                $months_difference++;
            }
            $quarters_difference--;
            $datediff = $quarters_difference;
            break;
        case "m":
            // Number of full months
            $months_difference = floor($difference / 2678400);
            while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
                $months_difference++;
            }
            $months_difference--;
            $datediff = $months_difference;
            break;
        case 'y':
            // Difference between day numbers
            $datediff = date("z", $dateto) - date("z", $datefrom);
            break;
        case "d":
            // Number of full days
            $datediff = floor($difference / 86400);
            break;
        case "w":
            // Number of full weekdays
            $days_difference = floor($difference / 86400);
            $weeks_difference = floor($days_difference / 7); // Complete weeks
            $first_day = date("w", $datefrom);
            $days_remainder = floor($days_difference % 7);
            $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
            if ($odd_days > 7) {
                // Sunday
                $days_remainder--;
            }
            if ($odd_days > 6) {
                // Saturday
                $days_remainder--;
            }
            $datediff = ($weeks_difference * 5) + $days_remainder;
            break;
        case "ww":
            // Number of full weeks
            $datediff = floor($difference / 604800);
            break;
        case "h":
            // Number of full hours
            $datediff = floor($difference / 3600);
            break;
        case "n":
            // Number of full minutes
            $datediff = floor($difference / 60);
            break;
        default: // Number of full seconds (default)
            $datediff = $difference;
            break;
    }
    return $datediff;
}

function PrepNumber($SMSnumber) {
    return str_replace(" ", "", str_replace("-", "", trim($SMSnumber)));
}

function ParseDateForTokens($inDate) {
    $year = date('Y');
    $month = date('n');
    $begin = mktime(0, 0, 0, $month, 1, $year);
    $end = strtotime("+1month -1day", $begin);
    if (strtoupper($inDate) == "STARTOFMONTH" || strtoupper($inDate) == "START_OF_MONTH") {
        return date('Y/m/d', $begin);
    }
    if (strtoupper($inDate) == "ENDOFMONTH" || strtoupper($inDate) == "END_OF_MONTH") {
        return date('Y/m/d', $end);
    }
    if (strtoupper($inDate) == "STARTOFLASTMONTH" || strtoupper($inDate) == "START_OF_LAST_MONTH") {
        return date('Y/m/d', strtotime("-1month", $begin));
    }
    if (strtoupper($inDate) == "ENDOFLASTMONTH" || strtoupper($inDate) == "END_OF_LAST_MONTH") {
        return date('Y/m/d', strtotime("-1day", $begin));
    }
    return $inDate;
}

function BuildDatePath($date, $signImagesFolder, $create = true) {
    $datepath = DateToPath($date);
    $newfolderpath = $signImagesFolder . $datepath;
    // make sure the directory exists with the correct permissions
    if (!is_dir($newfolderpath) && $create) {
        // Directory doesn't exist.
        mkdir($newfolderpath, 0777, true);
    }
    return $newfolderpath;
}

function BuildSignatureFilename($receiptid, $historyid) {
    $hist = ((isset($historyid) && $historyid != 0) ? "_" . $historyid : "");
    // return the full path with the file name
    return $receiptid . $hist . ".jpg";
}

class sorter {
    private static $sortfield = null;
    private static $sortorder = 1;
    private static function sort_callback(&$a, &$b) {
        $sorter = self::$sortfield;
        if ($a->$sorter == $b->$sorter) {
            return 0;
        }
        return ($a->$sorter < $b->$sorter) ? -self::$sortorder : self::$sortorder;
    }

    public static function sort(&$v, $field, $asc = true) {
        self::$sortfield = $field;
        self::$sortorder = $asc ? 1 : -1;
        usort($v, array('sorter', 'sort_callback'));
    }
}
function datediff($STdate, $EDdate) {
    if (strtotime($STdate) > strtotime($EDdate)) {
        return 0;
    } else {
        return 1;
    }
}

function date_SDiff($dt1, $dt2, $timeZone = 'GMT', $isAbsolute = 1) {
    $tZone = new DateTimeZone($timeZone);
    $dt1 = new DateTime($dt1, $tZone);
    $dt2 = new DateTime($dt2, $tZone);
    $ts1 = $dt1->format('Y-m-d');
    $ts2 = $dt2->format('Y-m-d');
    $diff = ($isAbsolute) ? abs(strtotime($ts1) - strtotime($ts2)) : strtotime($ts1) - strtotime($ts2);
    $diff /= 3600 * 24;
    return $diff;
}

/* Generate Formatted Data From Excel Sheet */
function get_excel_data($file_path, $max_column, $max_row, $column_names) {
    include_once '../../lib/comman_function/PHPExcel.php';
    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $excel = PHPExcel_IOFactory::load($file_path);
    $worksheet = $excel->getActiveSheet();
    $data = array();
    foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $column_count = 1;
        foreach ($cellIterator as $cell) {
            $row = $cell->getRow();
            $column = $cell->getColumn();
            if ($column_count > $max_column || $row > $max_row) {
                break;
            }
            $c_name = $column_names[$column];
            $value = $cell->getFormattedValue();
            $data[$row][$c_name] = $value;
            $column_count++;
        }
        if (!array_filter($data[$row])) {
            unset($data[$row]);
            continue;
        }
    }
    array_shift($data);
    return $data;
}

/* Create "Y-m-d" Format Date From Unitx Timestamp - Useful for Excel Import Functionality */
function date_maker($sDate) {
    $UNIX_DATE = ($sDate - 24107) * 86400;
    return gmdate("Y-m-d", $UNIX_DATE);
}

function date_maker_xls($sDate) {
    return date("Y-m-d", strtotime($sDate));
}

/* To Validate Excel File Input */
function file_upload_validation($valid_file, $valid_size, $filename) {
    if (!isset($_FILES[$filename])) {
        return "Please upload file.";
    }
    if ($_FILES[$filename]['size'] > $valid_size) {
        return "File Size cannot cannot exceed 2 MB";
    }
    if ($_FILES[$filename]['error'] == 1) {
        return "File Size cannot cannot exceed 2 MB";
    }
    $arrFileName = (explode(".", $_FILES[$filename]['name']));
    $file_ext = end($arrFileName);
    if (!in_array($file_ext, @$valid_file)) {
        return "Invalid file type.";
    }
    return null;
}

/* Print Formated Array */
function prettyPrint($a) {
    echo "<pre>";
    print_r($a);
    echo "</pre>";
}

/* Print Variable With Line Break */
function printBreak($a, $br = false) {
    $br = isset($br) ? "<br/>" : "";
    echo $a . $br;
}

function safe_json_encode($value) {
    if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
        $encoded = json_encode($value, JSON_PRETTY_PRINT);
    } else {
        $encoded = json_encode($value);
    }
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            return $encoded;
        case JSON_ERROR_DEPTH:
            return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_STATE_MISMATCH:
            return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_CTRL_CHAR:
            return 'Unexpected control character found';
        case JSON_ERROR_SYNTAX:
            return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_UTF8:
            $clean = utf8ize($value);
            return safe_json_encode($clean);
        default:
            return 'Unknown error'; // or trigger_error() or throw new Exception()
    }
}

function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        return utf8_encode($mixed);
    }
    return $mixed;
}

function encodeBase64UrlSafeUtil($value) {
    return str_replace(array('+', '/'), array('-', '_'), base64_encode($value));
}

function decodeBase64UrlSafeUtil($value) {
    return base64_decode(str_replace(array('-', '_'), array('+', '/'), $value));
}

function signLocationUrl($myUrlToSign, $privateKey) {
    $url = parse_url($myUrlToSign);
    $urlPartToSign = $url['path'] . "?" . $url['query'];
    $decodedKey = decodeBase64UrlSafeUtil($privateKey);
    $signature = hash_hmac("sha1", $urlPartToSign, $decodedKey, true);
    $encodedSignature = encodeBase64UrlSafeUtil($signature);
    return $myUrlToSign; // . "&signature=" . $encodedSignature;
}

function IsColumnExistInSqlite($dbLocation, $tableName, $columnName) {
    $isExist = false;
    $database = new PDO($dbLocation);
    $cursor = $database->query("PRAGMA table_info(" . $tableName . ")");
    while ($tableRow = $cursor->fetch()) {
        if ($tableRow['name'] == $columnName) {
            $isExist = true;
            break;
        }
    }
    return $isExist;
}

function getTempUtil($tempConversion) {
    $unitType = isset($tempConversion->unit_type) ? $tempConversion->unit_type : 0;
    $use_humidity = isset($tempConversion->use_humidity) ? $tempConversion->use_humidity : 0;
    $is_humidity = isset($tempConversion->is_humidity) ? $tempConversion->is_humidity : 0;
    $switch_to = isset($tempConversion->switch_to) ? $tempConversion->switch_to : 0;
    if ($unitType == 0) {
        $temp = round((($tempConversion->rawtemp - 1150) / 4.45), 2);
        if ($use_humidity == 1 && $switch_to == 3 && $is_humidity == 1) {
            $temp = round(($tempConversion->rawtemp / 100), 2);
        }
    } elseif ($unitType == 1 || ($use_humidity == 1 && $switch_to == 3)) {
        $temp = round(($tempConversion->rawtemp / 100), 2);
    }
    return $temp;
}

function getAnalogUtil($tempConversion) {
    $unitType = isset($tempConversion->unit_type) ? $tempConversion->unit_type : 0;
    $use_humidity = isset($tempConversion->use_humidity) ? $tempConversion->use_humidity : 0;
    $switch_to = isset($tempConversion->switch_to) ? $tempConversion->switch_to : 0;
    if ($unitType == 0) {
        //echo "1st if"; die;
        $temp = round((($tempConversion->rawtemp * 4.45) + 1150), 2);
        if ($use_humidity == 1 && $switch_to == 3) {
            //echo "1st inner if"; die;
            $temp = round(($tempConversion->rawtemp * 100), 2);
        }
    } elseif ($unitType == 1 || ($use_humidity == 1 && $switch_to == 3)) {
        //echo "else if"; die;
        $temp = round(($tempConversion->rawtemp * 100), 2);
    }
    return $temp;
}

//<editor-fold defaultstate="collapsed" desc="Multi Sort Function ">
/*
 *  This function sets up usort to sort a multiple dimmensional array,
 *  in asc or desc order, and case sensitive or not
 */
function multiKeySortUtil(&$arrObjects, $sortFields, $reverse = false, $ignorecase = false) {
    // we want to make sure that field(s) we want to sort are in an array for the compare function
    if (!is_array($sortFields)) {
        $sortFields = array($sortFields);
    }
    // our usort function that does all the work
    // notice the parameters
    usort($arrObjects, sortcompareUtil($sortFields, $reverse, $ignorecase));
}

/*
 * This is the usort compare function
 * It is preset to sort from asc and to not ignore case
 *
 * @return  bool  We only return a 1 , -1, or 0, which is what usort expects
 */
function sortcompareUtil($sortFields, $reverse = false, $ignorecase = false) {
    return function ($a, $b) use ($sortFields, $reverse, $ignorecase) {
        $cnt = 0;
        // check each sort field in the order specified
        foreach ($sortFields as $aField) {
            // check the value for ignorecase
            $ignore = is_array($ignorecase) ? $ignorecase[$cnt] : $ignorecase;
            // Determines whether to sort with or without case sensitive
            $result = $ignore ? strnatcasecmp($a[$aField], $b[$aField]) : strnatcmp($a[$aField], $b[$aField]);
            // the $result will be 1, -1, or 0
            // check to see if you want to reverse the sort order
            // to reverse the sort order you simply flip the return value by multplying the result by -1
            $revcmp = is_array($reverse) ? $reverse[$cnt] : $reverse;
            $result = $revcmp ? ($result * -1) : $result;
            // the first key that results in a non-zero comparison determines the order of the elements
            if ($result != 0) {
                break;
            }
            $cnt++;
        }
        //returns 1, -1, or 0
        return $result;
    };
}

// </editor-fold>
function cronCustomerUsers($users) {
    $array = json_decode(json_encode($users), true);
    $customerUserArray = array_reduce($array, function ($result, $currentItem) {
        if (isset($result[$currentItem['customerno']])) {
            $user = array();
            $user['userid'] = $currentItem['userid'];
            $user['email'] = $currentItem['email'];
            $user['realname'] = $currentItem['realname'];
            $user['userkey'] = $currentItem['userkey'];
            $user['userrole'] = $currentItem['userrole'];
            $user['roleid'] = $currentItem['roleid'];
            $user['reportId'] = $currentItem['reportId'];
            $user['reportTime'] = $currentItem['reportTime'];
            $user['interval'] = $currentItem['interval'];
            $user['iterativeReportHour'] = (isset($currentItem['iterativeReportHour']) && $currentItem['iterativeReportHour']) ? $currentItem['iterativeReportHour'] : 0;
            $result[$currentItem['customerno']]['users'][] = $user;
        } else {
            $result[$currentItem['customerno']] = array();
            $user = array();
            $user['userid'] = $currentItem['userid'];
            $user['email'] = $currentItem['email'];
            $user['realname'] = $currentItem['realname'];
            $user['userkey'] = $currentItem['userkey'];
            $user['userrole'] = $currentItem['userrole'];
            $user['roleid'] = $currentItem['roleid'];
            $user['reportId'] = $currentItem['reportId'];
            $user['reportTime'] = $currentItem['reportTime'];
            $user['interval'] = $currentItem['interval'];
            $user['iterativeReportHour'] = (isset($currentItem['iterativeReportHour']) && $currentItem['iterativeReportHour']) ? $currentItem['iterativeReportHour'] : 0;
            $result[$currentItem['customerno']]['users'][] = $user;
        }
        return $result;
    });
    return $customerUserArray;
}

function cronCustomerUsersFuel($users) {
    $array = json_decode(json_encode($users), true);
    $customerUserArray = array_reduce($array, function ($result, $currentItem) {
        if (isset($result[$currentItem['customerno']])) {
            $user = array();
            $user['userid'] = $currentItem['userid'];
            $user['email'] = $currentItem['email'];
            $user['phone'] = $currentItem['phone'];
            $user['realname'] = $currentItem['realname'];
            $user['userkey'] = $currentItem['userkey'];
            $user['userrole'] = $currentItem['userrole'];
            $user['roleid'] = $currentItem['roleid'];
            $user['customerno'] = $currentItem['customerno'];
            $user['fuel_alert_percentage'] = $currentItem['fuel_alert_percentage'];
            $result[$currentItem['customerno']]['users'][] = $user;
        } else {
            $result[$currentItem['customerno']] = array();
            $user = array();
            $user['userid'] = $currentItem['userid'];
            $user['email'] = $currentItem['email'];
            $user['phone'] = $currentItem['phone'];
            $user['realname'] = $currentItem['realname'];
            $user['userkey'] = $currentItem['userkey'];
            $user['userrole'] = $currentItem['userrole'];
            $user['roleid'] = $currentItem['roleid'];
            $user['customerno'] = $currentItem['customerno'];
            $user['fuel_alert_percentage'] = $currentItem['fuel_alert_percentage'];
            $result[$currentItem['customerno']]['users'][] = $user;
        }
        return $result;
    });
    return $customerUserArray;
}

function clientCodeValidation($ecodeStartDate, $ecodeEndDate, $ecodeDays, $reportStartDate, $reportEndDate) {
    $date = date('Y-m-d');
    $calculateddate = 0;
    $isError = 0;
    $startdate = date('Y-m-d', strtotime(GetSafeValueString($ecodeStartDate, 'string')));
    $enddate = date('Y-m-d', strtotime(GetSafeValueString($ecodeEndDate, 'string')));
    if (strtotime($reportStartDate) < strtotime($startdate) || strtotime($reportEndDate) > strtotime($enddate)) {
        $isError = 1; //die();
    }
    if (isset($ecodeDays) && $ecodeDays == 0) {
        $reportStartDate = date('d-m-Y', strtotime($reportStartDate));
        $reportEndDate = date('d-m-Y', strtotime($reportEndDate));
    } elseif (isset($ecodeDays) && $ecodeDays > 0) {
        $calculateddate = date('Y-m-d', strtotime($date . ' - ' . $ecodeDays . ' days'));
    } else {
        $isError = 1; //die();
    }
    if ($calculateddate != 0) {
        if (strtotime($reportStartDate) < strtotime($calculateddate)) {
            echo "<script>jQuery('#error7').show();jQuery('#error7').fadeOut(3000)</script>";
            $reportStartDate = $calculateddate;
            $reportStartDate = date('d-m-Y', strtotime($reportStartDate));
        }
        if (strtotime($reportEndDate) > strtotime($date)) {
            $reportEndDate = $date;
            $reportEndDate = date('d-m-Y', strtotime($reportEndDate));
        }
    }
    $result = array("isError" => $isError, "reportStartDate" => $reportStartDate, "reportEndDate" => $reportEndDate);
    return $result;
}

function sendMailUtil(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 0, $isElixiaTech = null) {
    include_once "class.phpmailer.php";
    $isEmailSent = 0;
    $completeFilePath = '';
    $mail = new PHPMailer(true);
    try {
        if (!$isTemplatedMessage) {
            $message .= "<br/>";
            $message .= speedConstants::Thanks;
            $message .= speedConstants::CompanyName;
            $message .= speedConstants::Portallink;
            $message .= speedConstants::CompanyImage;
        }
        if ($attachmentFilePath != '' && $attachmentFileName != '') {
            $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
        }
        $mail->IsMail();
        /* Clear Email Addresses */
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
        $mail->ClearCustomHeaders();
        $mail->WordWrap = speedConstants::WORDWRAP_COUNT_EMAIL_BODY;
        $mail->CharSet = 'utf-8';
        //unset($arrToMailIds);
        //$arrToMailIds = array('sshrikanth@elixiatech.com', 'mrudangvora@gmail.com', 'shrisurya24@gmail.com');
        //$strCCMailIds = '';
        if (!empty($arrToMailIds)) {
            foreach ($arrToMailIds as $mailto) {
                $mail->AddAddress($mailto);
            }
            if (!empty($strCCMailIds)) {
                $mail->AddCustomHeader("CC: " . $strCCMailIds);
            }
            if (!empty($strBCCMailIds)) {
                $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
            }
        }
        $mail->From = speedConstants::FROM_EMAIL;
        $mail->Sender = speedConstants::FROM_EMAIL;
        if ($isElixiaTech == 1) {
            $mail->FromName = "Elixia Tech";
            $mail->AddReplyTo(speedConstants::FROM_EMAIL, "Elixia Tech");
        } else {
            $mail->FromName = speedConstants::FROM_NAME;
            $mail->AddReplyTo(speedConstants::FROM_EMAIL, "Elixia Speed");
        }
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHtml(true);
        if ($attachmentFilePath != '' && $attachmentFileName != '') {
            $mail->AddAttachment($attachmentFilePath, $attachmentFileName);
        }
        //SEND Mail
        $isEmailSent = 1;
        /*
        if ($mail->Send()) {
        $isEmailSent = 1; // or use booleans here
        }
        else {
        echo 'Message was not sent.';
        echo 'Mailer error: ' . $mail->ErrorInfo;
        }
         */
        /* Clear Email Addresses */
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
        $mail->ClearCustomHeaders();
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
    return $isEmailSent;
}

function sendSMSUtil($phonearray, $message, &$response, $smsUrl = SMS_URL) {
    $isSMSSent = 0;
    $countryCode = "91";
    $arrPhone = array();
    if (is_array($phonearray)) {
        foreach ($phonearray as $phone) {
            if (preg_match('/^\d{10}$/', $phone)) {
                $arrPhone[] = $countryCode . $phone;
            }
        }
    } else {
        $arrPhone[] = $countryCode . $phonearray;
    }
    $phone = implode(",", $arrPhone);
    $url = str_replace("{{PHONENO}}", urlencode($phone), $smsUrl);
    $url = str_replace("{{MESSAGETEXT}}", urlencode($message), $url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    /*
    $response = curl_exec($ch);
    if ($response === false) {
    //echo 'Curl error: ' . curl_error($ch);
    $isSMSSent = 0;
    }
    else {
    $isSMSSent = 1;
    }
    curl_close($ch);
     */
    return $isSMSSent;
}

function sendFCMUtil($registatoin_ids, $message, $customerno = null) {
    $isNotify = 0;
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
        'registration_ids' => $registatoin_ids,
        'data' => $message
    );
    // print_r($fields);
    // die();
    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY_FCM,
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        $isNotify = 0;
    } else {
        $isNotify = 1;
    }
    // Close connection
    curl_close($ch);
    return $result;
}

function sendTelAlertUtil($objTelAlertDetails) {
    $objTelAlertResponse = new stdClass();
    $objTelAlertResponse->isSuccess = 0;
    $flowId = isset($objTelAlertDetails->flowId) ? $objTelAlertDetails->flowId : "234988";
    $phoneNo = isset($objTelAlertDetails->phoneNo) ? $objTelAlertDetails->phoneNo : "";
    $custNo = isset($objTelAlertDetails->customerno) ? $objTelAlertDetails->customerno : 0;
    $userId = isset($objTelAlertDetails->userId) ? $objTelAlertDetails->userId : 0;
    $telAlertLogId = isset($objTelAlertDetails->telAlertLogId) ? $objTelAlertDetails->telAlertLogId : 0;
    $cqId = isset($objTelAlertDetails->cqId) ? $objTelAlertDetails->cqId : 0;
    $customMessage = isset($objTelAlertDetails->customMessage) ? $objTelAlertDetails->customMessage : "";
    if ($phoneNo != "" && $telAlertLogId != 0 && $cqId != 0) {
        // Your Exotel SID - Get it here: https://my.exotel.in/Exotel/settings/site#exotel-settings
        $exotel_sid = "elixiatech1"; //"elixiatech";
        // Your exotel token - Get it here: https://my.exotel.in/Exotel/settings/site#exotel-settings
        $exotel_token = "c7568cd80b5d54e9d20ae15dc6318fa98346e4a6761af2cc"; //"2a58829d5022e65a2a2e5cc2db7b23f4fb90a9e0";
        $api_key = "58c52a2a274bb7615058d8e6d7a613677272082ac5bbfb7a"; // Your `API KEY`.
        $url = "https://" . $api_key . ":" . $exotel_token . "@twilix.exotel.in/v1/Accounts/" . $exotel_sid . "/Calls/connect";
        //$url = "https://" . $api_key . ":" . $api_token . "@twilix.exotel.in/v1/Accounts/" . $exotel_sid . "/Calls/connect";
        $post_data = array(
            'From' => $phoneNo
            , 'To' => "02248974898"
            , 'CallerId' => "02248974898"
            , 'Url' => "https://my.exotel.in/exoml/start/" . $flowId
            /*
             * Max no of seconds after which the call would not wait for user response and would hang up the call.
             * Maximum value could be set as 4 hours = 14400 secs.
             * We have set it to 45 sec as we are sure that call should be complete before that.
             * We have minute pulse rate per minute and would be cost - efficient too.
             */
            /*
             * Max no of seconds for which the call would ring before it automatically hangs up.
             * Such calls would be considered as missed calls from user point of view.
             * As per TRAI, the max value can be 60 secs
             */
            , 'CallType' => "trans"
            /*
             * Exotel would give the status of the call by hitting the below mentioned URL asynchronously.
             * There are following stauses: queued, in-progress, completed, failed, busy, no-answer
             */
            , 'StatusCallback' => "http://mahindrafs.elixiatech.com/lib/exotel/telAlertStatusCallback.php"
            /*
             * JSON string for custom fields
             */
            , 'CustomField' => '{"telAlertLogId":"' . $telAlertLogId . '","cqId":"' . $cqId . '","custNo":"' . $custNo . '","userId":"' . $userId . '","customMessage":"' . $customMessage . '"}'
        );
        echo json_encode($post_data);
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        print_r($response);
        print_r($curlError);exit;
        if (isset($curlError)) {
            $objTelAlertResponse->response = $curlError;
        } elseif ($http_code == "200") {
            $objTelAlertResponse->response = simplexml_load_string($response);
            $objTelAlertResponse->isSuccess = 1;
        } else {
            $objTelAlertResponse->response = simplexml_load_string($response);
        }
    } else {
        $objTelAlertResponse->response = "No phone number found or no alert generated.";
    }
    return $objTelAlertResponse;
}

function array_sum_byproperty($arrObj, $property) {
    $sum = 0;
    foreach ($arrObj as $data) {
        if (isset($data[$property])) {
            $sum += $data[$property];
        }
    }
    return $sum;
}

function findTimeDifference($start_date, $end_date, $intervalType) {
    $d1 = new DateTime($start_date);
    $d2 = new DateTime($end_date);
    $interval = $d1->diff($d2);
    if ($intervalType != '') {
        if ($intervalType == 'hour') {
            return ($interval->days * 24) + $interval->h;
        } else {
            return null;
        }
    }
}

function convertDegreeToKelvin($temperature) {
    $kelvin = 0;
    $kelvin = ($temperature + speedConstants::KELVIN_VALUE);
    return $kelvin;
}

function convertKelvinToDegree($temperature) {
    $degree = 0;
    $degree = ($temperature - speedConstants::KELVIN_VALUE);
    return $degree;
}

function getMeanKineticTemp($obj) {
    $finalMKTValue = 0;
    if (isset($obj) && isset($obj->temptotalreadingcount) && $obj->temptotalreadingcount > 0 && isset($obj->totalKelvinTemperature)) {
        $mktNumeratorComplete = ((speedConstants::DELTA_H) / speedConstants::GAS_CONSTANT);
        $mktDenominatorComplete = log($obj->totalKelvinTemperature / $obj->temptotalreadingcount) * -1;
        $finalMktTemperature = ($mktNumeratorComplete / $mktDenominatorComplete);
        $finalMKTValue = convertKelvinToDegree($finalMktTemperature);
    }
    return $finalMKTValue;
}

function isOverlap($x1, $x2, $y1, $y2) {
    $max = ((float) ($x1) < (float) ($y1)) ? $y1 : $x1;
    $min = ((float) ($x2) < (float) ($y2)) ? $x2 : $y2;
    if ((float) ($max) < (float) ($min)) {
        return true;
    } elseif ((float) ($max) >= (float) ($min)) {
        return false;
    }
}

function cleanNonPritableChars($string) {
    $string = str_replace(array('[\', \']'), '', $string);
    $string = str_replace("'", "'", $string);
    $string = str_replace('"', "", $string);
    $string = str_replace(' ', "", $string); // invisible space
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;| &nbsp;)?#?[a-z0-9]+;/i', '-', $string);
    //$string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
    //    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , ' ', $string);
    $string = preg_replace(array('/[^a-z0-9-.,:(&)\']/i'), ' ', $string);
    //    return strtolower(trim($string, '-'));
    return $string;
}

function calculateRouteDistanceAndTime($cgeolat, $cgeolong, $devicelat, $devicelong) {
    $distance = array("min" => -1, "km" => -1);
    try {
        $url = signLocationUrl("https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $cgeolat . "," . $cgeolong . "&destinations=" . $devicelat . "," . $devicelong . "&mode=driving&language=pl-PLsensor=false&key=" . GOOGLE_MAP_API_KEY, ''); //echo "<br/><br/>";
        $json = file_get_contents($url);
        $details = json_decode($json, true);
        //prettyPrint($details);
        if (isset($details['rows'][0]['elements'][0]['status']) && $details['rows'][0]['elements'][0]['status'] == "OK") {
            $distance['min'] = ceil(($details['rows'][0]['elements'][0]['duration']['value']) / 60);
            $distance['km'] = round(($details['rows'][0]['elements'][0]['distance']['value']) / 1000, 2);
        }
    } catch (Exception $ex) {
        $log = new Log();
        $log->createlog($customerno, $ex, "CRON_ALL", speedConstants::MODULE_VTS, __FUNCTION__);
    }
    return $distance;
}

function p($param = '') {
    echo "<pre>";
    print_r($param);
    echo "</pre>";
    die;
}

function pr($param = '') {
    echo "<pre>";
    print_r($param);
    echo "</pre>";
}

function get4DigitsOfVehicleNo($vehicleNo) {
    $vehicleNo = str_replace("-", "", $vehicleNo);
    $vehicleNo = str_replace(" ", "", $vehicleNo);
    $last4Digits = "";
    //Check whether the vehicle is in correct format
    $objVehicleValidity = isVehicleRegNoValid($vehicleNo);
    if ($objVehicleValidity->isValidVehicleRegNo) {
        if (!empty($objVehicleValidity->matchArray)) {
            foreach ($objVehicleValidity->matchArray AS $match) {
                if (strlen($match) == 4) {
                    $last4Digits = $match;
                }
            }
        }
    }
    return $last4Digits;
}

function vehicleDetails($vehicleNo) {
    $vehicleNo = str_replace("-", "", $vehicleNo);
    $vehicleNo = str_replace(" ", "", $vehicleNo);
    $last4Digits = "";
    //Check whether the vehicle is in correct format
    $objVehicleValidity = isVehicleRegNoValid($vehicleNo);
    if ($objVehicleValidity->isValidVehicleRegNo) {
        //print_r($objVehicleValidity->matchArray);
        if (!empty($objVehicleValidity->matchArray)) {
            return $objVehicleValidity->matchArray;
        }
    }
    return $last4Digits;
}

function isVehicleRegNoValid($strVehRegNo) {
    $objVehicleValidity = new stdClass();
    $objVehicleValidity->isValidVehicleRegNo = false;
    $objVehicleValidity->matchArray = array();
    if (preg_match(speedConstants::VEH_REGNO_EXPRESSION, $strVehRegNo, $objVehicleValidity->matchArray)) {
        $objVehicleValidity->isValidVehicleRegNo = true;
    }
    return $objVehicleValidity;
}

?>