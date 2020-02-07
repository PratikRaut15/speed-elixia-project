<?php
/**
 * Functions of Sales-module
 */
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
if (!isset($Mpath)) {
    $Mpath = '';
}
//include'../../config.inc.php';
include_once $Mpath . '../../lib/bo/UserManager.php';
require_once $Mpath . '../../lib/system/Log.php';
require_once $Mpath . '../../lib/system/Sanitise.php';
require_once $Mpath . '../../lib/system/DatabaseSalesManager.php';
require_once $Mpath . 'class/SalesManager.php';
require_once $Mpath . '../../lib/comman_function/reports_func.php';
//require_once $Mpath . '../cron/class.phpmailer.php';
define("SP_SPEED_FORGOT_PASSWORD", SPEEDDB . ".speed_forgot_password");
define("SP_UPDATE_NEWFORGOTPASSWORD", SPEEDDB . ".update_newforgotpassword");
define("SP_AUTHENTICATE_FOR_LOGIN", SPEEDDB . ".authenticate_for_login");
const PER_SMS_CHARACTERS = 160;
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
}
function get_asm_salespersons($asmid, $customerno) {
    $user = new Sales($customerno, $asmid);
    $sales = $user->getsales($asmid, $customerno);
    return $sales;
}

function get_salespersons_by_supervisor($userid, $customerno) {
    $user = new Sales($customerno, $userid);
    $sales = $user->getsales_bysupervisor($userid);
    return $sales;
}

function get_supervisors_by_asm($userid, $customerno) {
    $user = new Sales($customerno, $userid);
    $supervisors = $user->getsupervisors_byasm($userid, $customerno);
    return $supervisors;
}

function get_sr_by_supervisors($userid, $supids, $customerno) {
    $user = new Sales($customerno, $userid);
    $supervisors = $user->get_sr_by_supervisors($supids, $customerno);
    return $supervisors;
}

function get_sales_all($customerno, $userid) {
    $user = new Sales($customerno, $userid);
    $sales = $user->get_sales_all($customerno);
    return $sales;
}

function primary_product_count($prid, $customerno, $userid) {
    $user = new Sales($customerno, $userid);
    $sales = $user->get_primary_count($prid);
    return $sales;
}

function secondary_product_count($soid, $customerno, $userid) {
    $user = new Sales($customerno, $userid);
    $sales = $user->get_secondary_count($soid);
    return $sales;
}

function deadstock_product_count($stockid, $customerno, $userid) {
    $user = new Sales($customerno, $userid);
    $sales = $user->get_deadstock_count($stockid);
    return $sales;
}

function primary_product_details($prid, $customerno, $userid) {
    $user = new Sales($customerno, $userid);
    $sales = $user->get_primary_product_details($prid);
    return $sales;
}

function secondary_product_details($orderid, $customerno, $userid) {
    $user = new Sales($customerno, $userid);
    $sales = $user->get_secondary_product_details($orderid);
    return $sales;
}

function deadstock_product_details($orderid, $customerno, $userid) {
    $user = new Sales($customerno, $userid);
    $sales = $user->get_deadstock_product_details($orderid);
    return $sales;
}

function get_category($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->get_category();
    return $result;
}

//function get_state($customerno, $userid) {
//    $mob = new Sales($customerno, $userid);
//    $result = $mob->get_state();
//    return $result;
//}
function get_asm($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->get_asm();
    return $result;
}

function get_sales($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->get_sales();
    return $result;
}

function get_srcode($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->get_srcode();
    return $result;
}

function get_saleswise_distributor($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->get_saleswise_distributor($userid);
    return $result;
}

function get_area($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->get_area();
    return $result;
}

function get_shop($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->get_shop();
    return $result;
}

function getdevicelist($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->getdevicelist();
    return $result;
}

function getsalesforcustomer($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->getsaleslist();
    return $result;
}

function getdevicefromsales($customerno, $userid, $salesid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->getdevicefromsales($salesid);
    return $result;
}

function mapdevicetosales($customerno, $userid, $deviceid, $salesid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->mapdevicetosales($deviceid, $salesid);
    return $result;
}

function demapdevice($customerno, $userid, $deviceid) {
    $mob = new Sales($customerno, $userid);
    $result = $mob->demapdevice($deviceid);
    return $result;
}

function catedit($customerno, $userid, $catid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_catedit($catid);
    $editstatedata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $editstatedata[] = array(
                "catid" => $thisdata->categoryid,
                "categoryname" => $thisdata->categoryname,
                "customerno" => $thisdata->customerno
            );
        }
        return $editstatedata;
    }
    return null;
}

function shedit($customerno, $userid, $shid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_shedit($shid);
    $editshdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $editshdata[] = array(
                "shid" => $thisdata->shid,
                "shop_type" => $thisdata->shop_type
            );
        }
        return $editshdata;
    }
    return null;
}

function catview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_catview();
    $viewcattdata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $viewcattdata[] = array(
                "catid" => $thisdata->categoryid,
                "categoryname" => $thisdata->categoryname,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by,
                "isdeleted" => $thisdata->isdeleted
            );
        }
        return $viewcattdata;
    }
    return null;
}

function shoptypeview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_shoptypeview();
    $viewstdata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $viewstdata[] = array(
                "shid" => $thisdata->shid,
                "shop_type" => $thisdata->shop_type
            );
        }
        return $viewstdata;
    }
    return null;
}

function stateview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_stateview();
    $viewstatedata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $viewstatedata[] = array(
                "stateid" => $thisdata->stateid,
                "statename" => $thisdata->statename,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by,
                "isdeleted" => $thisdata->isdeleted
            );
        }
        return $viewstatedata;
    }
    return null;
}

function styleedit($customerno, $userid, $styleid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_styleedit($styleid);
    $editstyledata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $editstyledata[] = array(
                "styleid" => $thisdata->styleid,
                "categoryid" => $thisdata->categoryid,
                "styleno" => $thisdata->styleno,
                "mrp" => $thisdata->mrp,
                "distprice" => $thisdata->distprice,
                "retailprice" => $thisdata->retailprice,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by,
                "carton" => $thisdata->carton,
                "productimage" => $thisdata->productimage,
                "imagelink" => $thisdata->imagelink,
                "companysellingprice" => $thisdata->companysellingprice
            );
        }
        return $editstyledata;
    }
    return null;
}

function styleview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_styleview();
    $viewstyledata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $viewstyledata[] = array(
                "styleid" => $thisdata->styleid,
                "categoryid" => $thisdata->categoryid,
                "styleno" => $thisdata->styleno,
                "mrp" => $thisdata->mrp,
                "distprice" => $thisdata->distprice,
                "retailprice" => $thisdata->retailprice,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by,
                "isdeleted" => $thisdata->isdeleted,
                "addedby_name" => 'ganesh',
                "updated_by_name" => 'uday'
            );
        }
        return $viewstyledata;
    }
    return null;
}

function stockview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_stockview();
    $viewstockdata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $viewstockdata[] = array(
                "stockid" => $thisdata->stockid,
                "srcode" => $thisdata->srcode,
                "distcode" => $thisdata->distcode,
                "categoryname" => $thisdata->categoryname,
                "styleno" => $thisdata->styleno,
                "stockdate" => $thisdata->stockdate,
                "quantity" => $thisdata->quantity
            );
        }
        return $viewstockdata;
    }
    return null;
}

function stockedit($customerno, $userid, $stockid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_stockedit($stockid);
    $editstockdata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $editstockdata[] = array(
                "stockid" => $thisdata->stockid,
                "srcode" => $thisdata->srcode,
                "salesid" => $thisdata->salesid,
                "distcode" => $thisdata->distcode,
                "distname" => $thisdata->distname,
                "distributorid" => $thisdata->distributorid,
                "categoryname" => $thisdata->categoryname,
                "categoryid" => $thisdata->categoryid,
                "styleno" => $thisdata->styleno,
                "styleid" => $thisdata->styleid,
                "stockdate" => $thisdata->stockdate,
                "quantity" => $thisdata->quantity
            );
        }
        return $editstockdata;
    }
    return null;
}

function stateedit($customerno, $userid, $stateid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_stateedit($stateid);
    $editstatedata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $editstatedata[] = array(
                "stateid" => $thisdata->stateid,
                "statename" => $thisdata->statename,
                "customerno" => $thisdata->customerno
            );
        }
        return $editstatedata;
    }
    return null;
}

function asmview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_asmview();
    $viewasmdata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $viewasmdata[] = array(
                "asmid" => $thisdata->asmid,
                "stateid" => $thisdata->stateid,
                "asmname" => $thisdata->asmname,
                "statename" => $thisdata->statename,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by,
                "isdeleted" => $thisdata->isdeleted,
                "addedby_name" => 'ganesh',
                "updated_by_name" => 'uday'
            );
        }
        return $viewasmdata;
    }
    return null;
}

function asmedit($customerno, $userid, $asmid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_asmedit($asmid);
    $editasmdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $editasmdata[] = array(
                "asmid" => $thisdata->asmid,
                "stateid" => $thisdata->stateid,
                "asmname" => $thisdata->asmname,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by
            );
        }
        return $editasmdata;
    }
    return null;
}

function areaedit($customerno, $userid, $areaid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_areaedit($areaid);
    $editareadata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $editareadata[] = array(
                "areaid" => $thisdata->areaid,
                "distributorid" => $thisdata->distributorid,
                "areaname" => $thisdata->areaname,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by
            );
        }
        return $editareadata;
    }
    return null;
}

function areaview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_areaview();
    $viewasmdata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $viewasmdata[] = array(
                "areaid" => $thisdata->areaid,
                "distributorid" => $thisdata->distributorid,
                "areaname" => $thisdata->areaname,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by,
                "isdeleted" => $thisdata->isdeleted,
                "addedby_name" => 'ganesh',
                "updated_by_name" => 'uday'
            );
        }
        return $viewasmdata;
    }
    return null;
}

function distedit($customerno, $userid, $distid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_distedit($distid);
    $editareadata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $editareadata[] = array(
                "distid" => $thisdata->distid,
                "salesid" => $thisdata->salesid,
                "distcode" => $thisdata->distcode,
                "distname" => $thisdata->distname,
                "dob" => $thisdata->dob,
                "dphone" => $thisdata->dphone,
                "demail" => $thisdata->demail,
                "dphone" => $thisdata->dphone
            );
        }
        return $editareadata;
    }
    return null;
}

function distview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_distview();
    $viewdistdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $viewdistdata[] = array(
                "distid" => $thisdata->distid,
                "salesid" => $thisdata->salesid,
                "distcode" => $thisdata->distcode,
                "distname" => $thisdata->distname,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by
            );
        }
        return $viewdistdata;
    }
    return null;
}

function saleview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_saleview();
    $viewsaledata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $viewsaledata[] = array(
                "salesid" => $thisdata->salesid,
                "asmid" => $thisdata->asmid,
                "srcode" => $thisdata->srcode,
                "srname" => $thisdata->srname,
                "phone" => $thisdata->phone,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by
            );
        }
        return $viewsaledata;
    }
    return null;
}

function saleedit($customerno, $userid, $saleid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_saleedit($saleid);
    $editsaledata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $editsaledata[] = array(
                "salesid" => $thisdata->salesid,
                "asmid" => $thisdata->asmid,
                "srcode" => $thisdata->srcode,
                "srname" => $thisdata->srname,
                "phone" => $thisdata->phone,
                "dob" => $thisdata->dob
            );
        }
        return $editsaledata;
    }
    return null;
}

function shopview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_shopview();
    $viewshopdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $viewshopdata[] = array(
                "shopid" => $thisdata->shopid,
                "distributorid" => $thisdata->distributorid,
                "salesid" => $thisdata->salesid,
                "areaid" => $thisdata->areaid,
                "shopname" => $thisdata->shopname,
                "phone" => $thisdata->phone,
                "phone2" => $thisdata->phone2,
                "owner" => $thisdata->owner,
                "address" => $thisdata->address,
                "emailid" => $thisdata->emailid,
                "customerno" => $thisdata->customerno,
                "entrytime" => $thisdata->entrytime,
                "addedby" => $thisdata->addedby,
                "updatedtime" => $thisdata->updatedtime,
                "updated_by" => $thisdata->updated_by
            );
        }
        return $viewshopdata;
    }
    return null;
}

function shoptype($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_shoptypeview();
    $viewstdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $viewstdata[] = array(
                "shid" => $thisdata->shid,
                "shop_type" => $thisdata->shop_type
            );
        }
        return $viewstdata;
    }
}

function shopedit($customerno, $userid, $sid) {
    $mob = new Sales($customerno, $userid);
    $resulteditdata = $mob->get_shopedit($sid);
    $viewshopdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $viewshopdata[] = array(
                "shopid" => $thisdata->shopid,
                "distributorid" => $thisdata->distributorid,
                "salesid" => $thisdata->salesid,
                "areaid" => $thisdata->areaid,
                "shopname" => $thisdata->shopname,
                "phone" => $thisdata->phone,
                "phone2" => $thisdata->phone2,
                "owner" => $thisdata->owner,
                "address" => $thisdata->address,
                "emailid" => $thisdata->emailid,
                "dob" => $thisdata->dob,
                "shoptypeid" => $thisdata->shoptypeid
            );
        }
        return $viewshopdata;
    }
    return null;
}

function entryview($customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_entryview();
    $viewstatedata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $viewstatedata[] = array(
                "srcode" => $thisdata->srcode,
                "distcode" => $thisdata->distcode,
                "shopname" => $thisdata->shopname,
                "entrydate" => $thisdata->entrydate,
                "remark" => $thisdata->remark
            );
        }
        return $viewstatedata;
    }
    return null;
}

function orderedit($orderid) {
    $mob = new Sales($_SESSION['customerno'], $_SESSION['userid']);
    $resultdata = $mob->get_orderedit($orderid);
    $editstatedata = array();
    if (!empty($resultdata)) {
        foreach ($resultdata as $thisdata) {
            $editstatedata[] = array(
                "orderid" => $thisdata->orderid,
                "salesid" => $thisdata->salesid,
                "distid" => $thisdata->distid,
                "areaid" => $thisdata->areaid,
                "shopid" => $thisdata->shopid,
                "catid" => $thisdata->catid,
                "styleid" => $thisdata->styleid,
                "orderdate" => $thisdata->orderdate,
                "quantity" => $thisdata->quantity
            );
        }
        return $editstatedata;
    }
    return null;
}

function check_login($username, $password, $customerno) {
    $mob = new Sales($_SESSION['customerno'], $_SESSION['userid']);
    $resultdata = $mob->check_login($username, $password, $customerno);
    return $resultdata;
}

function getsrlists_login($role, $userid, $customerno) {
    $mob = new Sales($customerno, $userid);
    $resultdata = $mob->get_sr_list($role);
    return $resultdata;
}

function skuimagefilenameupdate($filename, $skuid) {
    $mob = new Sales($_SESSION['customerno'], $_SESSION['userid']);
    $resultdata = $mob->skuimagefilenameupdate($filename, $skuid);
    return $resultdata;
}

function getsaleslist($userid, $role) {
    $mob = new Sales($_SESSION['customerno'], $userid);
    $resultdata = $mob->getsrlistbyusers($userid, $role);
    return $resultdata;
}

function getsupervisorlist($customerno, $userid) {
    $mob = new Sales($_SESSION['customerno'], $userid);
    $resultdata = $mob->getsupervisorslist($customerno);
    return $resultdata;
}

function getsrbysupervisor($customerno, $supid) {
    $mob = new Sales($_SESSION['customerno'], $_SESSION['userid']);
    $supids = array($supid);
    if ($supid != -1) {
        $resultdata = $mob->get_sr_by_supervisors($supids, $customerno, $all = NULL);
    } else {
        $resultdata = $mob->get_sr_by_supervisors($supids, $customerno, $all = 'ALL');
    }
    return $resultdata;
}

function getCallData_datewise($data, $type) {
    $SDate = $data['startdate'];
    $EDate = $data['enddate'];
    $mob = new Sales($data['customerno'], $data['userid']);
    $totaldays = gendays_cmn($SDate, $EDate);
    $days = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $days[$userdate] = $mob->getCallDataSR($data, $userdate);
        }
    }
    $finalreport = '';
    if ($days != null && count($days) > 0) {
        if ($type == 'pdf') {
            $finalreport = create_callhtmlpdf_from_reportdatewise($days);
        } else {
            $finalreport = create_callhtml_from_reportdatewise($days);
        }
    } else {
        $finalreport = "<tr><td style='text-align:center' colspan='100%'>No Record</td></tr>";
    }
    echo $finalreport;
}

function getSummaryReportdatewise($data, $type) {
    $mob = new Sales($data['customerno'], $data['userid']);
    $srdata = array();
    $SDate = $data['stdate'];
    $EDate = $data['edate'];
    $totaldays = gendays_cmn($SDate, $EDate);
    if ($data['bysupervisor'] == 1 && $data['suplist'] != '-1' && $data['suplist'] != '0') {
        $srdetails = $mob->getsales_bysupervisor($data['suplist']);
        foreach ($srdetails as $row) {
            $srid = $row->userid;
            $srdetail = $mob->getsrdetails($srid);
            $datewisecounts = summarydata_datewise($srid, $data, $data['bysupervisor'], $data['suplist']);
            $srdata[] = array(
                'srdetails' => $srdetail,
                'datewisecounts' => $datewisecounts
            );
        }
    } elseif ($data['suplist'] != '-1' && $data['srlist'] == '-1') {
        $srdetails = $mob->getsales_bysupervisor($data['suplist']);
        foreach ($srdetails as $row) {
            $srid = $row->userid;
            $srdetail = $mob->getsrdetails($srid);
            $datewisecounts = summarydata_datewise($srid, $data);
            $srdata[] = array(
                'srdetails' => $srdetail,
                'datewisecounts' => $datewisecounts
            );
        }
    } elseif ($data['srid'] != '-1' && $data['suplist'] != '-1') {
        $srdetail = $mob->getsrdetails($data['srid']);
        $datewisecounts = summarydata_datewise($data['srid'], $data);
        $srdata[] = array(
            'srdetails' => $srdetail,
            'datewisecounts' => $datewisecounts
        );
    } elseif ($data['srid'] == '-1' && $data['suplist'] == '-1') {
        $srlistarr = explode(',', $data['srarr']);
        foreach ($srlistarr as $key => $val) {
            $srid = $val;
            $srdetail = $mob->getsrdetails($srid);
            $datewisecounts = summarydata_datewise($srid, $data);
            $srdata[] = array(
                'srdetails' => $srdetail,
                'datewisecounts' => $datewisecounts
            );
        }
    }
    $finalreport = '';
    if ($srdata != null && count($srdata) > 0) {
        if ($type == 'pdf') {
            //  $finalreport = create_callpdf_sales_summary($srdata,$totaldays,$data['customerno'], $data['userid']);
        } else {
            $finalreport = create_callhtml_sales_summary($srdata, $totaldays, $data['customerno'], $data['userid']);
        }
    } else {
        $finalreport = "<tr><td style='text-align:center' colspan='100%'>No Record</td></tr>";
    }
    echo $finalreport;
}

function create_callhtml_sales_summary($resultdata, $totaldays, $customerno, $userid) {
    $mob = new Sales($customerno, $userid);
    foreach ($totaldays as $row) {
        $datehead[] = "<th style='width:20px; padding:1px;'>" . date('d-m', strtotime($row)) . "</th>";
    }
    $dateheadstr = implode('', $datehead);
    $html = '';
    $html .= "<table class='newTable'>";
    $html .= "<tr style='font-width:bold;'><th  style='width:100px;'>Supervisor <br>Name</th><th  style='width:100px;'>SR Name</th><th  style='width:100px;'>Distributor Name</th>" . $dateheadstr . "<th>Total</th></tr>";
    if (isset($resultdata) && count($resultdata) > 0) {
        $datedatastr = '';
        $colarr = array();
        $dayswisearr = array();
        for ($i = 0; $i < count($resultdata); $i++) {
            $srdetails = $resultdata[$i]['srdetails'][0];
            $hid = $srdetails->heirarchy_id;
            $srid = $srdetails->userid;
            $distdetails = $mob->getdistid($srid);
            $distname = isset($distdetails[0]->realname) ? $distdetails[0]->realname : '';
            $supdetails = $mob->getsrdetails($hid);
            $supdata = $supdetails[0];
            $supervisorname = isset($supdata->realname) ? $supdata->realname : "";
            $datedata = $resultdata[$i]['datewisecounts'];
            if (count($datedata) > 0) {
                $countperdatetd = array();
                $rowwise_sum = array();
                $k = 0;
                foreach ($datedata as $row1) {
                    $countperdatetd[] = "<td style='width:20px; padding:1px;'>" . $row1 . "</td>";
                    $rowwise_sum[] = $row1;
                    $k++;
                }
                $colarr[$i] = $rowwise_sum;
                $countrowwise = array_sum($rowwise_sum);
                $datedatastr = implode(' ', $countperdatetd);
            }
            $html .= "<tr><td>" . $supervisorname . "</td><td>" . $srdetails->realname . "</td><td>" . $distname . "</td>" . $datedatastr . "<td>" . $countrowwise . "</td></tr>";
        }
        $sumArray = array();
        foreach ($colarr as $k => $subArray) {
            foreach ($subArray as $key => $val) {
                $sumArray[$key][] = $val;
            }
        }
        $html .= "<tr><td colspan=3>Total</td>";
        $kk = 0;
        $grandtotalarr = array();
        foreach ($totaldays as $val) {
            $colsum = array_sum($sumArray[$kk]);
            $grandtotalarr[] = $colsum;
            $html .= "<td>" . $colsum . "</td>";
            $kk++;
        }
        $grandtotal = array_sum($grandtotalarr);
        $html .= "<td>" . $grandtotal . "</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    $html .= "</br>";
    echo $html;
}

function summarydata_datewise($srid, $data, $bysupervisor = NULL, $supid = NULL) {
    $mob = new Sales($data['customerno'], $data['userid']);
    $days = array();
    $SDate = $data['stdate'];
    $EDate = $data['edate'];
    $totaldays = gendays_cmn($SDate, $EDate);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $days[] = $mob->calldata_datewise_summary($srid, $data, $userdate, $bysupervisor, $supid);
        }
    }
    return $days;
}

function create_callhtml_from_reportdatewise($resultdata) {
    $html = '';
    $html .= "<table class='table newTable'>";
    $html .= "<tr><th>SR Name</th><th>First Entry By</th><th>Last Entry By</th><th>First Call</th><th>First InArea</th><th>Last Call</th><th>Last InArea</th><th>Order Counts</th><th>Entry Counts</th></tr>";
    if (isset($resultdata) && count($resultdata) > 0) {
        $productivecounts = array();
        $entrycountsarr = array();
        foreach ($resultdata as $key => $row) {
            $printdate = $key;
            $printdate = date('d-m-Y', strtotime($printdate));
            $html .= '<tr><th align="center" colspan="100%" style="background:#d8d5d6">' . $printdate . '</th></tr>';
            if (isset($row) && count($row) > 0) {
                foreach ($row as $data1) {
                    $data = $data1[0];
                    $srdetails = $data['srdetails'][0];
                    $firstcall = $data['firstcall'];
                    $lastcall = $data['lastcall'];
                    $ordercounts = $data['ordercounts'];
                    $entrycounts = $data['entrycounts'];
                    $srname = $srdetails->realname;
                    $fircall = isset($firstcall['entrydate']) ? convertDateToFormat($firstcall["entrydate"], speedConstants::DEFAULT_TIME) : '';
                    $firstareaname = isset($firstcall['areaname']) ? $firstcall['areaname'] : "";
                    $lastareaname = isset($lastcall['areaname']) ? $lastcall['areaname'] : "";
                    $firstmastername = isset($firstcall['mastername']) ? $firstcall['mastername'] : "";
                    $lastmastername = isset($lastcall['mastername']) ? $lastcall['mastername'] : "";
                    $firstmastername1 = '';
                    if (isset($firstcall['entrybyrole']) && $firstcall['entrybyrole'] != 0) {
                        $masterrole = isset($firstcall['masterrolename']) ? "--(" . $firstcall['masterrolename'] . ")" : '';
                        $firstmastername1 = $firstmastername . $masterrole;
                    }
                    $lastmastername1 = '';
                    if (isset($lastcall['entrybyrole']) && $lastcall['entrybyrole'] != 0) {
                        $masterrole = isset($lastcall['masterrolename']) ? "<br>--(" . $lastcall['masterrolename'] . ")" : '';
                        $lastmastername1 = $lastmastername . $masterrole;
                    }
                    $lastcall = isset($lastcall['entrydate']) ? convertDateToFormat($lastcall["entrydate"], speedConstants::DEFAULT_TIME) : '';
                    $html .= "<tr><td>" . $srname . "</td><td>" . $firstmastername1 . "</td><td>" . $lastmastername1 . "</td><td>" . $fircall . "</td><td>" . $firstareaname . "</td> <td>" . $lastcall . "</td><td>" . $lastareaname . "</td><td>" . $ordercounts . "</td><td>" . $entrycounts . "</td></tr>";
                    $productivecounts[] = isset($ordercounts) ? $ordercounts : 0;
                    $entrycountsarr[] = isset($entrycounts) ? $entrycounts : 0;
                }
            } else {
                $html .= "<tr><td colspan='5'>Data Not Available.</td></tr>";
            }
        }
    }
    $html .= "</table>";
    $html .= "</br>";
    $html .= "<table class='table newTable' align='center' border=1 style='width:450px;'>";
    $html .= "<tr><th colspan=2>Call Summary Details</th></tr>";
    $pcount = array_sum($productivecounts);
    $totalcount = array_sum($entrycountsarr);
    $html .= "<tr><td>Productive Calls</td><td>" . $pcount . "</td></tr>";
    $html .= "<tr><td>Total Calls </td><td>" . $totalcount . "</td></tr>";
    $html .= "</table>";
    echo $html;
}

function create_callhtmlpdf_from_reportdatewise($resultdata) {
    $html = '';
    $html .= "<table id='search_table_2' align='center' border=1  border-collapse:collapse; style='width:90%; font-size:13px; text-align:center;'>";
    $html .= "<tr><th>SR<br>Name</th><th>First<br> Entry By</th><th>Last<br> Entry By</th><th>First<br>Call</th><th>First<br>InArea</th><th>Last<br>Call</th><th>Last<br>InArea</th><th>Order<br>Counts</th><th>Entry<br>Counts</th></tr>";
    if (isset($resultdata) && count($resultdata) > 0) {
        $productivecounts = array();
        $entrycountsarr = array();
        foreach ($resultdata as $key => $row) {
            $printdate = $key;
            $printdate = date('d-m-Y', strtotime($printdate));
            $html .= '<tr><th align="center" colspan="9" style="background:#d8d5d6">' . $printdate . '</th></tr>';
            if (isset($row) && count($row) > 0) {
                foreach ($row as $data1) {
                    $data = $data1[0];
                    $srdetails = $data['srdetails'][0];
                    $firstcall = $data['firstcall'];
                    $lastcall = $data['lastcall'];
                    $ordercounts = $data['ordercounts'];
                    $entrycounts = $data['entrycounts'];
                    $srname = $srdetails->realname;
                    $fircall = isset($firstcall['entrydate']) ? convertDateToFormat($firstcall["entrydate"], speedConstants::DEFAULT_TIME) : '';
                    $firstareaname = isset($firstcall['areaname']) ? $firstcall['areaname'] : "";
                    $lastareaname = isset($lastcall['areaname']) ? $lastcall['areaname'] : "";
                    $firstmastername = isset($firstcall['mastername']) ? $firstcall['mastername'] : "";
                    $lastmastername = isset($lastcall['mastername']) ? $lastcall['mastername'] : "";
                    $firstmastername1 = '';
                    if (isset($firstcall['entrybyrole']) && $firstcall['entrybyrole'] != 0) {
                        $masterrole = isset($firstcall['masterrolename']) ? "<br>--(" . $firstcall['masterrolename'] . ")" : '';
                        $firstmastername1 = $firstmastername . $masterrole;
                    }
                    $lastmastername1 = '';
                    if (isset($lastcall['entrybyrole']) && $lastcall['entrybyrole'] != 0) {
                        $masterrole = isset($lastcall['masterrolename']) ? "<br>--(" . $lastcall['masterrolename'] . ")" : '';
                        $lastmastername1 = $lastmastername . $masterrole;
                    }
                    $lastcall = isset($lastcall['entrydate']) ? convertDateToFormat($lastcall["entrydate"], speedConstants::DEFAULT_TIME) : '';
                    $html .= "<tr><td>" . $srname . "</td><td>" . $firstmastername1 . "</td><td>" . $lastmastername1 . "</td><td>" . $fircall . "</td><td style='word-break:break-all;'>" . $firstareaname . "</td> <td>" . $lastcall . "</td><td>" . $lastareaname . "</td><td>" . $ordercounts . "</td><td>" . $entrycounts . "</td></tr>";
                    $productivecounts[] = isset($ordercounts) ? $ordercounts : 0;
                    $entrycountsarr[] = isset($entrycounts) ? $entrycounts : 0;
                }
            } else {
                $html .= "<tr><td colspan='5'>Data Not Available.</td></tr>";
            }
        }
    }
    $html .= "</table>";
    $html .= "<br>";
    $html .= "<table class='table newTable' align='center' border=1 style='width:450px;'>";
    $html .= "<tr><th colspan=2>Call Summary Details</th></tr>";
    $pcount = array_sum($productivecounts);
    $totalcount = array_sum($entrycountsarr);
    $html .= "<tr><td>Productive Calls</td><td>" . $pcount . "</td></tr>";
    $html .= "<tr><td>Total Calls </td><td>" . $totalcount . "</td></tr>";
    $html .= "</table>";
    echo $html;
}

function pdf_callreport($data) {
    $prevdate = $data['prevdate'];
    $customerno = $data['customerno'];
    $userid = $data['userid'];
    $title = 'First & Last Call Report';
    if (!isset($_POST['SDate'])) {
        $startdate = $prevdate;
    } else {
        $startdate = $_POST['SDate'];
    }
    if (!isset($_POST['EDate'])) {
        $enddate = $prevdate;
    } else {
        $enddate = $_POST['EDate'];
    }
    $subTitle = array(
        "Start Date: " . $startdate,
        "End Date: " . $enddate
    );
    $columns = array();
    $cm = new CustomerManager($customerno);
    $customer = $cm->getcustomerdetail_byid($customerno);
    echo $resultdata = report_header('PDF', $title, $subTitle, $columns, $customer, $fluid = false, $middlecolumn = NULL);
    echo $resultdata = getCallData_datewise($data, 'pdf');
}

function pdf_stylereport($data) {
    $resultdata = '';
    $prevdate = $data['prevdate'];
    $sdate = $data['stdate'];
    $edate = $data['edate'];
    $customerno = $data['customerno'];
    $cm = new CustomerManager($customerno);
    $customer = $cm->getcustomerdetail_byid($customerno);
    $userid = $data['userid'];
    $title = 'SKU Wise Order Report';
    if (!isset($_POST['STdate']) && isset($showdate)) {
        $startdate = $showdate;
    } elseif (!isset($showdate) && isset($stdate)) {
        $startdate = $stdate;
    } elseif (!isset($_POST['STdate'])) {
        $startdate = $_REQUEST['stdate'];
    } else {
        $startdate = $_POST['STdate'];
    }
    if (!isset($_POST['EDdate']) && isset($showdate)) {
        $enddate = $showdate;
    } elseif (!isset($showdate) && isset($edate)) {
        $enddate = $edate;
    } else {
        $enddate = $_POST['EDdate'];
    }
    $subTitle = array(
        "Start Date: " . date('d-m-Y', strtotime($startdate)),
        "End Date: " . date('d-m-Y', strtotime($enddate))
    );
    $columns = array();
    echo $resultdata = report_header('PDF', $title, $subTitle, $columns, $customer, $fluid = false, $middlecolumn = NULL);
    echo getStyleSRData($data, 'pdf');
}

function excel_callreport($data) {
    $resultdata = '';
    $prevdate = $data['prevdate'];
    $startdate = $data['startdate'];
    $enddate = $data['enddate'];
    $customerno = $data['customerno'];
    $userid = $data['userid'];
    $title = 'First & Last Call Report';
    $subTitle = array(
        "Start Date: " . $startdate,
        "End Date: " . $enddate
    );
    $columns = array();
    $cm = new CustomerManager($customerno);
    $customer = $cm->getcustomerdetail_byid($customerno);
    echo $resultdata = excel_header($title, $subTitle, $customer);
    echo $resultdata = getCallData_datewise($data, 'all');
    //return $resultdata;
}

function excel_stylereport($data) {
    $resultdata = '';
    $prevdate = $data['prevdate'];
    $sdate = $data['stdate'];
    $edate = $data['edate'];
    $customerno = $data['customerno'];
    $userid = $data['userid'];
    $cm = new CustomerManager($customerno);
    $customer = $cm->getcustomerdetail_byid($customerno);
    $title = 'SKU Wise Order Report';
    if (!isset($_POST['STdate']) && isset($showdate)) {
        $startdate = $showdate;
    } elseif (!isset($showdate) && isset($stdate)) {
        $startdate = $stdate;
    } elseif (!isset($_POST['STdate'])) {
        $startdate = $_REQUEST['stdate'];
    } else {
        $startdate = $_POST['STdate'];
    }
    if (!isset($_POST['EDdate']) && isset($showdate)) {
        $enddate = $showdate;
    } elseif (!isset($showdate) && isset($edate)) {
        $enddate = $edate;
    } else {
        $enddate = $_POST['EDdate'];
    }
    $subTitle = array(
        "Start Date: " . date('d-m-Y', strtotime($startdate)),
        "End Date: " . date('d-m-Y', strtotime($enddate))
    );
    $columns = array();
    echo $resultdata = excel_header($title, $subTitle, $customer);
    echo $resultdata = getStyleSRData($data, 'all');
}

function excel_sales_summaryreport($data) {
    $resultdata = '';
    $prevdate = $data['prevdate'];
    $sdate = $data['stdate'];
    $edate = $data['edate'];
    $customerno = $data['customerno'];
    $userid = $data['userid'];
    $cm = new CustomerManager($customerno);
    $customer = $cm->getcustomerdetail_byid($customerno);
    $title = 'Sales Summary Report';
    if (!isset($_POST['STdate']) && isset($showdate)) {
        $startdate = $showdate;
    } elseif (!isset($showdate) && isset($stdate)) {
        $startdate = $stdate;
    } elseif (!isset($_POST['STdate'])) {
        $startdate = $_REQUEST['stdate'];
    } else {
        $startdate = $_POST['STdate'];
    }
    if (!isset($_POST['EDdate']) && isset($showdate)) {
        $enddate = $showdate;
    } elseif (!isset($showdate) && isset($edate)) {
        $enddate = $edate;
    } else {
        $enddate = $_POST['EDdate'];
    }
    $subTitle = array(
        "Start Date: " . date('d-m-Y', strtotime($startdate)),
        "End Date: " . date('d-m-Y', strtotime($enddate))
    );
    $columns = array();
    echo $resultdata = excel_header($title, $subTitle, $customer);
    echo $resultdata = getSummaryReportdatewise($data, 'all');
}

function getStyleSRData($data, $type) {
    $mob = new Sales($data['customerno'], $data['userid']);
    $resultdata = $mob->getstylesrdata($data);
    $finalreport = '';
    if ($resultdata != null && count($resultdata) > 0) {
        if ($type == 'pdf') {
            $finalreport = create_stylehtmlpdf_from_report($resultdata, $data);
        } else {
            $finalreport = create_stylehtml_from_report($resultdata, $data);
        }
    } else {
        $finalreport = "<tr><td style='text-align:center' colspan='100%'>No Record</td></tr>";
    }
    echo $finalreport;
}

function create_stylehtml_from_report($resultdata, $details) {
    $html = '';
    $html .= "<table class='table newTable'>";
    if (isset($resultdata)) {
        $i = 0;
        $html .= "<tr><th>SR Name</th><th>Distributor Name</th><th>Store Name</th><th>Product Name</th> <th>Total Qty Ordered</th><th>Selling Price</th><th>Total Price</th></tr>";
        foreach ($resultdata as $row) {
            $data = $row[0];
            $srdetails = $data['srdetails'][0];
            $styledetails = $data['styledetails'];
            $mob = new Sales($details['customerno'], $details['userid']);
            $srid = $srdetails->userid;
            $distdetails = $mob->getdistid($srid);
            $distname = isset($distdetails[0]->realname) ? $distdetails[0]->realname : '';
            $srname = $srdetails->realname;
            if (count($styledetails) > 0 && isset($styledetails)) {
                foreach ($styledetails as $row) {
                    $productname = $row->styleno;
                    $categoryname = $row->categoryname;
                    $totalqty = $row->totalqty;
                    $companysellingprice = $row->companysellingprice;
                    $html .= "<tr><td>" . $srname . "</td><td>" . $distname . "</td><td>" . $categoryname . "</td><td>" . $productname . "</td> <td>" . $totalqty . "</td><td>" . $companysellingprice . "</td><td>" . round(floatval($companysellingprice) * floatval($totalqty), 2) . "</td></tr>";
                }
            } else {
                $html .= "<tr><td>" . $srname . "</td><td>" . $distname . "</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
            }
            $i++;
        }
    }
    $html .= "</table>";
    echo $html;
}

function create_stylehtmlpdf_from_report($resultdata, $details) {
    $width = 'style:=height=20px;';
    $html = '';
    $html .= "<table id='search_table_2' align='center' border=1 style='width:100%; font-size:13px; text-align:center;'>";
    $html .= "<tr><th>SR Name</th><th>Distributor Name</th><th>Store Name</th><th>Product Name</th> <th>Total Qty Ordered</th><th>Selling Price</th><th>Total Price</th></tr>";
    if (isset($resultdata)) {
        $i = 0;
        foreach ($resultdata as $row) {
            $data = $row[0];
            // print("<pre>");
            // print_r($data); die;
            $srdetails = $data['srdetails'][0];
            $styledetails = $data['styledetails'];
            $srid = $srdetails->userid;
            $mob = new Sales($details['customerno'], $details['userid']);
            $distdetails = $mob->getdistid($srid);
            $distname = isset($distdetails[0]->realname) ? $distdetails[0]->realname : '';
            //echo count($styledetails); die();
            $srname = $srdetails->realname;
            // $html .= "<tr><td colspan=3><h3>" . $srname . "</h3></td></tr>";
            if (count($styledetails) > 0 && isset($styledetails)) {
                foreach ($styledetails as $row) {
                    $productname = $row->styleno;
                    $categoryname = $row->categoryname;
                    $totalqty = $row->totalqty;
                    $companysellingprice = $row->companysellingprice;
                    $html .= "<tr><td " . $width . ">" . $srname . "</td><td " . $width . ">" . $distname . "</td><td " . $width . ">" . $categoryname . "</td><td " . $width . ">" . $productname . "</td> <td " . $width . ">" . $totalqty . "</td><td " . $width . ">" . $companysellingprice . "</td><td " . $width . ">" . round(floatval($companysellingprice) * floatval($totalqty), 2) . "</td></tr>";
                }
            } else {
                $html .= "<tr><td>" . $srname . "</td><td>" . $distname . "</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
            }
            $i++;
        }
    }
    $html .= "</table>";
    echo $html;
}

function getSecondarySummary_excel($customer_id, $dpd, $date) {
    $html = "";
    $srdata = array();
    //$prevdate = date('Y-m-d', strtotime(' -1 day'));
    $prevdate = date('Y-m-d', strtotime($date . ' -1 day'));
    //$prevdate = '2017-04-03';
    $data = array(
        'startdate' => $prevdate,
        'enddate' => $prevdate
    );
    $mob = new Sales($customer_id, $dpd['userid']);
    $srlist = $mob->getsrlistbyusers($dpd['userid'], $dpd['role']);
    $html .= "<table border=1 style='width:100%;'>";
    $prevdatetitle = date('d-m-Y', strtotime($prevdate));
    $html .= "<tr><td colspan=9><h4> Summary Report (" . $prevdatetitle . ")</h4></td></tr>";
    if (isset($srlist) && count($srlist) > 0) {
        foreach ($srlist as $row) {
            $srid = $row->userid;
            $srname = $row->realname;
            $srdata[] = $mob->calldata_datewise($srid, $data, $prevdate);
        }
        $html .= "<tr>
                        <td>SR Name</td>
                        <td>First Entry By</td>
                        <td>Last Entry By</td>
                        <td>First Call</td>
                        <td>Last Call</td>
                        <td>First Entry Area</td>
                        <td>Last Entry Area</td>
                        <td>Order Counts</td>
                        <td>Entry Counts</td>
                    </tr>";
        foreach ($srdata as $data1) {
            $data = $data1[0];
            $srdetails = $data['srdetails'][0];
            $firstcall = $data['firstcall'];
            $lastcall = $data['lastcall'];
            $ordercounts = $data['ordercounts'];
            $entrycounts = $data['entrycounts'];
            $srname = $srdetails->realname;
            $fircall = isset($firstcall['entrydate']) ? convertDateToFormat($firstcall["entrydate"], speedConstants::DEFAULT_TIME) : '';
            $firstareaname = isset($firstcall['areaname']) ? $firstcall['areaname'] : "";
            $lastareaname = isset($lastcall['areaname']) ? $lastcall['areaname'] : "";
            $firstmastername = isset($firstcall['mastername']) ? $firstcall['mastername'] : "";
            $lastmastername = isset($lastcall['mastername']) ? $lastcall['mastername'] : "";
            $firstmastername1 = '';
            $lastmastername1 = '';
            if (isset($firstcall['entrybyrole']) && $firstcall['entrybyrole'] != 0) {
                $masterrole = isset($firstcall['masterrolename']) ? "<br>--(" . $firstcall['masterrolename'] . ")" : '';
                $firstmastername1 = $firstmastername . $masterrole;
            }
            if (isset($lastcall['entrybyrole']) && $lastcall['entrybyrole'] != 0) {
                $masterrole = isset($lastcall['masterrolename']) ? "<br>--(" . $lastcall['masterrolename'] . ")" : '';
                $lastmastername1 = $lastmastername . $masterrole;
            }
            $lastcall = isset($lastcall['entrydate']) ? convertDateToFormat($lastcall["entrydate"], speedConstants::DEFAULT_TIME) : '';
            $html .= "<tr><td>" . $srname . "</td><td>" . $firstmastername1 . "</td><td>" . $lastmastername1 . "</td><td>" . $fircall . "</td><td>" . $lastcall . "</td><td style='word-break:break-all;'>" . $firstareaname . "</td> <td>" . $lastareaname . "</td><td>" . $ordercounts . "</td><td>" . $entrycounts . "</td></tr>";
            $productivecounts[] = isset($ordercounts) ? $ordercounts : 0;
            $entrycountsarr[] = isset($entrycounts) ? $entrycounts : 0;
        }
    } else {
        $html .= "<tr><td colspan=5>SR data not available.</td></tr>";
    }
    $html .= "</table>";
    $html .= "<br><br>";
    $html .= "<table class='table newTable' align='center' border=1 style='width:450px;'>";
    $html .= "<tr><th colspan=2>Call Summary Details</th></tr>";
    $pcount = array_sum($productivecounts);
    $totalcount = array_sum($entrycountsarr);
    $html .= "<tr><td>Productive Calls</td><td>" . $pcount . "</td></tr>";
    $html .= "<tr><td>Total Calls </td><td>" . $totalcount . "</td></tr>";
    $html .= "</table>";
    echo $html;
}

///
function getCallSummaryData($data, $type = null) {
    $SDate = $data['startdate'];
    // echo $SDate; die;
    $mob = new Sales($data['customerno'], $data['userid']);
    $totaldays = gendays_cmn($SDate, $SDate);
    $days = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $days[$userdate] = $mob->getCallSummaryData($data, $userdate);
        }
    }
    //print("<pre>"); print_r($days); die;
    $finalreport = '';
    if ($days != null && count($days) > 0) {
        if ($type == 'pdf') {
            $finalreport = create_callhtmlpdf_from_reportdatewise($days);
        } else {
            $finalreport = create_callSummaryhtml_from_report($days);
        }
    } else {
        $finalreport = "<tr><td style='text-align:center' colspan='100%'>No Record</td></tr>";
    }
    echo $finalreport;
}

function create_callSummaryhtml_from_report($resultdata) {
    $html = '';
    $html .= "<table class='table newTable'>";
    $html .= "<tr><th>Date</th><th>SR Name</th><th>Supervisor Name</th><th>Area</th><th>Shop Name</th></tr>";
    if (isset($resultdata) && count($resultdata) > 0) {
        $productivecounts = array();
        $entrycountsarr = array();
        foreach ($resultdata as $key => $row) {
            $printdate = $key;
            $printdate = date('d-m-Y', strtotime($printdate));
            //$html .= '<tr><th align="center" colspan="100%" style="background:#d8d5d6">' . $printdate . '</th></tr>';
            if (isset($row) && count($row) > 0) {
                $i = 0;
                foreach ($row as $data1) {
                    foreach ($data1 as $data) {
                        // print("<pre>"); print_r($data); die;
                        $entrytime = date("d-m-Y h:i A", strtotime($data['entrytime']));
                        $srName = $data['realname'];
                        $supName = $data['supervisor'];
                        $areaName = $data['areaname'];
                        $shopName = $data['shopname'];
                        $html .= "<tr><td>" . $entrytime . "</td><td>" . $srName . "</td><td>" . $supName . "</td><td>" . $areaName . "</td><td>" . $shopName . "</td></tr>";
                        /*$productivecounts[] = isset($ordercounts) ? $ordercounts : 0;
                    $entrycountsarr[] = isset($entrycounts) ? $entrycounts : 0;*/
                    }
                }
            } else {
                $html .= "<tr><td colspan='5'>Data Not Available.</td></tr>";
            }
        }
    }
    $html .= "</table>";
    $html .= "</br>";
    /*$html .= "<table class='table newTable' align='center' border=1 style='width:450px;'>";
    $html .= "<tr><th colspan=2>Call Summary Details</th></tr>";
    $pcount = array_sum($productivecounts);
    $totalcount = array_sum($entrycountsarr);
    $html .= "<tr><td>Productive Calls</td><td>" . $pcount . "</td></tr>";
    $html .= "<tr><td>Total Calls </td><td>" . $totalcount . "</td></tr>";
    $html .= "</table>";*/
    echo $html;
}

function excel_sales_call_summaryreport($data) {
    //print("<pre>"); print_r($data); die;
    $resultdata = '';
    // $prevdate = $data['prevdate'];
    $startdate = $data['startdate'];
    $enddate = $data['startdate'];
    $customerno = $data['customerno'];
    $userid = $data['userid'];
    $cm = new CustomerManager($customerno);
    $customer = $cm->getcustomerdetail_byid($customerno);
    $title = 'Call Adition Summary Report';
    $subTitle = array(
        "Start Date: " . date('d-m-Y', strtotime($startdate)),
        "End Date: " . date('d-m-Y', strtotime($enddate))
    );
    $columns = array();
    echo $resultdata = excel_header($title, $subTitle, $customer);
    // echo $resultdata = getSummaryReportdatewise($data, 'all');
    echo $resultdata = getCallSummaryData($data);
}

function getlocation($lat, $long, $geocode, $customerno) {
    $address = null;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function excel_attendancereport($data) {
    $resultdata = '';
    // $prevdate = $data['prevdate'];
    $sdate = $data['stdate'];
    $edate = $data['edate'];
    $customerno = $data['customerno'];
    // $userid = $data['userid'];
    $cm = new CustomerManager($customerno);
    $customer = $cm->getcustomerdetail_byid($customerno);
    $title = 'Attendance Report';
    if (!isset($_POST['STdate']) && isset($showdate)) {
        $startdate = $showdate;
    } elseif (!isset($showdate) && isset($stdate)) {
        $startdate = $stdate;
    } elseif (!isset($_POST['STdate'])) {
        $startdate = $_REQUEST['stdate'];
    } else {
        $startdate = $_POST['STdate'];
    }
    if (!isset($_POST['EDdate']) && isset($showdate)) {
        $enddate = $showdate;
    } elseif (!isset($showdate) && isset($edate)) {
        $enddate = $edate;
    } else {
        $enddate = $_POST['EDdate'];
    }
    $subTitle = array(
        "Start Date: " . date('d-m-Y', strtotime($startdate)),
        "End Date: " . date('d-m-Y', strtotime($enddate))
    );
    $columns = array();
    // echo "start date ".$sdate." end date". $enddate; die;
    echo $resultdata = excel_header($title, $subTitle, $customer);
    echo $resultdata = getAttendanceData($data, 'all');
}

function getAttendanceData($data, $type) {
    $mob = new Sales($data['customerno'], $data['userid']);
    $resultdata = $mob->getattendancedata($data);
    // print("<pre>"); print_r($resultdata); die;
    $finalreport = '';
    if ($resultdata != null && count($resultdata) > 0) {
        if ($type == 'pdf') {
            $finalreport = create_stylehtmlpdf_from_report($resultdata, $data);
        } else {
            $finalreport = create_attendancehtml_from_report($resultdata, $data);
        }
    } else {
        $finalreport = "<tr><td style='text-align:center' colspan='100%'>No Record</td></tr>";
    }
    echo $finalreport;
}

function create_attendancehtml_from_report($resultdata, $details) {
    $html = '';
    $html .= "<table class='table newTable'>";
    if (isset($resultdata)) {
        $i = 0;
        $html .= "<tr><th>Name</th>
        <th>Role</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
        <th>Location</th></tr>";
        if (count($resultdata) > 0 && isset($resultdata)) {
            foreach ($resultdata as $row) {
                $mob = new Sales($details['customerno'], $details['userid']);
                $lat = $row->lat;
                $lng = $row->lng;
                $name = $row->realname;
                $role = $row->role;
                $startdate = $row->createdon;
                $enddate = $row->updatedon;
                $status = $row->status;
                if ($lat == 0 OR $lng == 0) {
                    $location = "N/A";
                } else {
                    $location = getlocation($lat, $lng, '0', $details['customerno']);
                }
                $html .= "<tr><td>" . $name . "</td><td>" . $role . "</td><td>" . $startdate . "</td><td>" . $enddate . "</td> <td>" . $status . "</td><td>" . $location . "</td></tr>";
                $i++;
            }
        }
    }
    $html .= "</table>";
    echo $html;
}

function getCountOfProducts($obj) {
    $mob = new Sales($_SESSION['customerno'], $_SESSION['userid']);
    $resultdata = $mob->getCountOfProducts_SalesSummary($obj);
    return $resultdata;
}

function addSecondarySalesOrder($orderObj) {
    //print_r($orderObj);
    $orderId = 0;
    $mob = new Sales($orderObj->customerno, $orderObj->userid);
    $orderId = $mob->add_secondarysalesdata_api($orderObj);
    $orderObj->orderId = $orderId;
    // $orderId = 12121332323;
    //$orderId = $mob->add_secondarysalesdata_api($role, $shopid, $skudata, $orderdate, $is_deadstock, $reason, $discount, $distid, $srid, $otId, $deliverydatetime);
    if (isset($orderId)) {
        $orderStatus = $mob->getOrderStatus($orderId, $orderObj->customerno);
        $arr_p['Status'] = "successful";
        $arr_p['message'] = 'Added ' . $orderObj->msg . ' order sucessfully';
        $arr_p['orderid'] = $orderId;
        if (isset($orderObj->use_erp) && $orderObj->use_erp == 1 && isset($orderStatus) && $orderStatus == 0) {
            $objCN = new stdClass();
            if (isset($orderObj->manual_cn) and $orderObj->manual_cn != 0 and $orderObj->manual_cn != "") {
                $orderObj->manual_cn = $orderObj->manual_cn;
                $manual_booking_no = $orderId;
            } else {
                $orderObj->manual_cn = $orderId;
                $manual_booking_no = $orderId;
            }
            $objCN->userkey = $orderObj->erpusertoken;
            $objCN->manualCnNo = $orderObj->manual_cn;
            $objCN->deliveryDatetime = $orderObj->deliverydatetime;
            $objCN->moreProductDetails = $orderObj->skudata;
            $objCN->consigneeSalesRefId = $orderObj->shopid;
            $objCN->clientSalesRefId = $orderObj->shopid;
            $objCN->isDriverApp = $orderObj->driverid;
            $objCN->manualBookingNo = $manual_booking_no;
            if (isset($orderObj->otId) && $orderObj->otId == 3) {
                $objCN->isClosed = 1;
            } else {
                $objCN->isClosed = 0;
            }
            //            ordertye = 3 then isClosed = 1
            // print_r($objCN);die();
            $ch = curl_init();
            $objCN = json_encode($objCN);
            $post_data = array(
                'jsonreq' => $objCN
            );
            // print_r($post_data);
            //echo speedConstants::API_ERP_BOOKING;
            // curl_setopt($ch, CURLOPT_URL, speedConstants::API_ERP_BOOKING);
            if (isset($orderObj->customerno) && $orderObj->customerno == 193) {
                $insertConsignorNote = "/insertConsignorNote";
            } else if (isset($orderObj->customerno) && $orderObj->customerno == 698) {
                $insertConsignorNote = "/insertMultipleCnWithSingleBr";
            }
            // echo $insertConsignorNote; die;
            curl_setopt($ch, CURLOPT_URL, API_ERP_BOOKING . $insertConsignorNote);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::REQUEST_TIMEOUT);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
            $response = curl_exec($ch);
            if (curl_error($ch)) {
                echo 'error:' . curl_error($ch);
            }
            curl_close($ch);
            $arrResponse[] = $response;
            //print_r($arrResponse);
        }

        /* Generate Sales Order In Books */
        $booksSalesOrderId = pushSalesOrderInBooks($orderObj);

        //die();

        if (isset($orderStatus) && $orderStatus != 0) {
            $shopDetails = shopedit($orderObj->customerno, $orderObj->userid, $orderObj->shopid);
            if (isset($shopDetails) && !empty($shopDetails)) {
                $shop = array();
                $shop = $shopDetails[0];
                $subject = 'Order On Hold - ' . $orderId;
                $message = "Your order is on hold, Please contact to distributor for approval of order";
                $attachmentFilePath = '';
                $attachmentFileName = '';
                $CCEmail = '';
                $BCCEmail = '';
                $emailId = 'ritesh.d@elixiatech.com';
                $isMailSent = sendMailUtil(array($emailId), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
            }
            //sendAlertForOnHoldOrders($orderObj->shopid, $orderId, $orderStatus);
            //
        }
    }
    return $orderId;
}

function addSecondarySalesShop($shopObj) {
    $shopId = 0;
    $mob = new Sales($shopObj->customerno, $shopObj->userkeyid);
    $shopId = $mob->add_shopdata_api($shopObj);
    if (isset($shopId)) {
        if (isset($shopObj->use_erp) && $shopObj->use_erp == 1) {
            $objCN = new stdClass();
            $objCN->userkey = $shopObj->erpusertoken;
            $objCN->shopName = $shopObj->shopname;
            $objCN->phone = $shopObj->sphoneno;
            $objCN->address = $shopObj->saddress;
            $objCN->email = $shopObj->semail;
            $objCN->cGroupId = $shopObj->cGroupId;
            $objCN->salesRefId = $shopId;
            // print_r($objCN);die();
            $ch = curl_init();
            $objCN = json_encode($objCN);
            $post_data = array(
                'jsonreq' => $objCN
            );
            curl_setopt($ch, CURLOPT_URL, API_ERP_CONSIGNEE_AND_CLIENT);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::REQUEST_TIMEOUT);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
            $response = curl_exec($ch);
            if (curl_error($ch)) {
                echo 'error:' . curl_error($ch);
            }
            curl_close($ch);
            $arrResponse[] = $response;
            //print_r($arrResponse);
        }
    }
    return $shopId;
}

function sendAlertForOnHoldOrders($shopId, $iderId, $orderStatus) {
    // sdff;
}

function pushSalesOrderInBooks($objRequestData) {
    $salesOrderId = 0;
    //print_r($objRequestData);
    $arrProd = array();
    $objSO = new stdClass();

    $objSO->userkey = sha1($objRequestData->booksUserToken);
    $objSO->customerPoNumber = $objRequestData->orderId;
    $objSO->customerPoDate = $objRequestData->orderdate;
    $objSO->salesOrderDate = $objRequestData->orderdate;
    $objSO->clientId = $objRequestData->shopid;
    //print_r($objRequestData->skudata);
    if (isset($objRequestData->skudata) && !empty($objRequestData->skudata)) {
        foreach ($objRequestData->skudata as $data) {
            $objProd = new stdClass();
            $objProd->productId = $data['skuid'];
            $objProd->qty = $data['quantity'];
            $arrProd[] = $objProd;
            //print_r($arrProd);
        }
    }
    $objSO->skuData = $arrProd;
    $ch = curl_init();
    $objSO = json_encode($objSO);
    $post_data['jsonreq'] = $objSO;
    //print_r($post_data); //die();

    $insertSalesOrder = API_BOOKS . "/insertSalesOrder";
    // echo $insertConsignorNote; die;
    curl_setopt($ch, CURLOPT_URL, $insertSalesOrder);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::REQUEST_TIMEOUT);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    /*
    echo "<br/><br/>";
    echo " Request Header";
    echo "<br/><br/>";
    print_r($info);
    echo "<br/><br/>";
    echo " Response";
    echo "<br/><br/>" . $response;
    */
    if (curl_error($ch)) {
        echo 'error:' . curl_error($ch);
    }
    curl_close($ch);
    $arrResponse = $response;
    //print_r($arrResponse);
}

function pushCustomerInvoiceInBooks($objRequestData) {
    $salesOrderId = 0;
    //print_r($objRequestData);
    $arrProd = array();
    $objSO = new stdClass();

    $objSO->userkey = $objRequestData->erpUserToken;
    $objSO->clinetSoId = $objRequestData->salesOrderId;
    $objSO->customerPoNumber = $objRequestData->orderId;
    $objSO->invoiceNumber = "INV" . $objRequestData->orderId;
    $objSO->invoiceDate = $objRequestData->orderdate;
    $objSO->clientId = $objRequestData->shopid;

    if (isset($objRequestData->skudata) && !empty($objRequestData->skudata)) {
        foreach ($objRequestData->skudata as $data) {
            $objProd = new stdClass();
            $objProd->productId = $data['skuid'];
            $objProd->qty = $data['quantity'];
            $arrProd[] = $objProd;
        }
    }
    $objSO->skuData = $arrProd;
    $ch = curl_init();
    $objSO = json_encode($objSO);
    $post_data = array(
        'jsonreq' => $objSO
    );
    // print_r($post_data);

    $insertSalesOrder = "/insertCustomerInvoicePro";

    // echo $insertConsignorNote; die;
    curl_setopt($ch, CURLOPT_URL, API_BOOKS . $insertSalesOrder);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::REQUEST_TIMEOUT);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        echo 'error:' . curl_error($ch);
    }
    curl_close($ch);
    $arrResponse[] = $response;
}

?>
