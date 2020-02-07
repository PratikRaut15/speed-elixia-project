<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// $status   //live =0 & offline=1
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once 'sales_function.php';
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
$action = exit_issetor($_REQUEST['action'], failure('Action not found')); //'Action not found'
/* Login */
if ($action == 'pullcredentials') {
    $username = exit_issetor($_REQUEST['username'], json_encode(array('result' => 'failure'))); //'Username not found'
    $password = exit_issetor($_REQUEST['password'], json_encode(array('result' => 'failure'))); //'Password not found'
    //failure($text)
    $dm1 = new sales(0, 0);
    if (isset($username) && isset($password)) {
        $test = $dm1->check_login($username, $password);
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// ************** PULL DETAILS *************** //
// Pull Shops
if ($action == 'pullshops') {
    // pull all shops
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = isset($_REQUEST['srid']) ? $_REQUEST['srid'] : ""; //'User ID not found'
    $areaid = isset($_REQUEST['areaid']) ? $_REQUEST['areaid'] : "";
    if ($userid != "" && $areaid != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $userid);
        $alldata = $dm1->get_shop_api($userid, $areaid); //get shop lists
        if (empty($alldata)) {
            echo failure('No Shops data');
            exit;
        } else {
            echo success_json($alldata);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Pull Distributor
if ($action == 'pulldistributor') {
    // pull all Distributor
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = $_REQUEST['srid']; //'User ID not found'
    if (!empty($userid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $role = '';
        $role = $dpd['role'];
        $dm1 = new sales($dpd['customerno'], $userid);
// $alldata = $dm1->getdistid($userid);//get all distributor list  by srid
        $alldata = $dm1->getdistributordata_api($userid);
        if (empty($alldata)) {
            echo failure('No Distributor data');
            exit;
        } else {
            echo success_json($alldata);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Pull SKU
if ($action == 'pullsku') {
    // pull all sku
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $dm1 = new sales($dpd['customerno'], $dpd['userid']);
    $result = $dm1->get_sku_api(); //get category
    if (empty($result)) {
        echo failure('No Sku Data');
        exit;
    } else {
        echo success_json($result);
        exit;
    }
}
// Pull Area
if ($action == 'pullarea') {
    // pull all area
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = $_REQUEST['srid']; //'User ID not found'
    $distid = $_REQUEST['distid']; //'distributor ID not found'
    if ($userid && $distid != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $role = '';
        $role = $dpd['role'];
        $dm1 = new sales($dpd['customerno'], $userid);
//$distdata = $dm1->getdistributordata_api($userid);
        $distdata = $dm1->getdistributor_api($userid, $distid);
//$distdata = $dm1->getdistid($userid);
        $userid = array();
        if (isset($distdata)) {
            foreach ($distdata as $row) {
                $userid[] = $row->distributorid;
            }
        }
        $result = $dm1->get_area_api($userid); //get area
        if (empty($result)) {
            echo failure('No area data');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Pull Category
if ($action == 'pullcategory') {
    // pull all category
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $dm1 = new sales($dpd['customerno'], $dpd['userid']);
    $result = $dm1->get_category_api(); //get category
    if (empty($result)) {
        echo failure('No Category data');
        exit;
    } else {
        echo success_json($result);
        exit;
    }
}
// Pull Shop Type
if ($action == 'pullshoptype') {
    // pull all shop type
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $dm1 = new sales($dpd['customerno'], $dpd['userid']);
    $result = $dm1->get_shoptype_api(); //get shop type
    if (empty($result)) {
        echo failure('No shoptype data');
        exit;
    } else {
        echo success_json($result);
        exit;
    }
}
// ********** PRIMARY SALES ************* //
// Pull Primary Sales
if ($action == 'pullprimarysales') {
    //get all primary sales  view by sr or asm
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = $_REQUEST['srid']; //'User ID not found'
    $distid = $_REQUEST['distid']; //'User ID not found'
    if ($userid != "" && $distid != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $userid);
        $userkey_id = $dpd['userid'];
        $result = $mob->get_primarysales_orders_api($userid, $userkey_id, $distid); //get primary sales orders
        if (empty($result)) {
            echo failure('No primary sales orders');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'pullprimaryorders') {
    //Pull todays allprimary orders by roles SR / SUP / ASM
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = $_REQUEST['srid']; //'SR ID not found'
    $distributorid = $_REQUEST['distid']; //'User ID not found'
    $selecteddate = isset($_REQUEST['selected_date']) ? $_REQUEST['selected_date'] : ""; //'User ID not found'
    if (!empty($userid) && !empty($distributorid) && !empty($selecteddate)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $userid);
        $userkey_id = $dpd['userid'];
        $result = $mob->get_primarysales_orderlist_api($userid, $userkey_id, $distributorid, $selecteddate); //get primary sales orders
        if (empty($result)) {
            echo failure('No primary sales orders');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Pull Primary Sales Details
if ($action == 'pullprimarysales_details') {
    //get all primary sales  view by sr or asm
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = (int) ri($_REQUEST['orderid']);
    if (!empty($orderid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->get_primarysales_details_api($orderid); //get primary sales orders
        if (empty($result)) {
            echo failure('No primary sales orders');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Push Primary Sales
if ($action == "pushprimarysales") {
    //add primary sales
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = $_REQUEST['srid']; //'User ID not found'
    $skudata = json_decode($_REQUEST['skudata'], TRUE);
    $distid = (int) ri($_REQUEST['distributorid']);
    $orderdate = ri($_REQUEST['orderdate']);
    if (!empty($userid) && !empty($distid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkeyid = $dpd['userid']; //userkey -> userid
        $mob = new Sales($dpd['customerno'], $userid);
        $role = $dpd['role'];
        $id = $mob->add_primarysalesdata_api($role, $distid, $skudata, $orderdate, $userkeyid);
        if (isset($id)) {
            $arr_p['Status'] = "successful";
            $arr_p['message'] = "Added Primary Sales sucessfully";
            $arr_p['orderid'] = $id;
        } else {
            $arr_p['Status'] = "unsuccessful";
            $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
    }
    echo json_encode($arr_p);
}
if ($action == "editprimarysales") {
    //Update primary sales
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = $_REQUEST['srid']; //'User ID not found'
    $skudata = json_decode($_REQUEST['skudata'], TRUE);
    $orderid = $_REQUEST['orderid'];
    if (!empty($userid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkeyid = $dpd['userid']; //userkey -> userid
        $mob = new Sales($dpd['customerno'], $userid);
        $role = $dpd['role'];
        $mob->edit_primarysalesdata_api($role, $skudata, $orderid, $userkeyid);
        echo success('Update Primary Sales sucessfully');
        exit;
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'approve_primarysales') {
    //(-1 for Reject, 1 for Approval , 0 - Pending)
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $approval = (int) ri($_REQUEST['approval']);
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $orderid = $_REQUEST['orderid'];
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    $result = $mob->approve_primarydata_api($orderid, $approval); //Approve primarysales order
    if (empty($result)) {
        echo failure('Approval order failed');
        exit;
    } else {
        echo success_json($result);
        exit;
    }
}
if ($action == 'delete_primarysales') {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $orderid = $_REQUEST['orderid'];
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    if (!empty($orderid)) {
        $result = $mob->delete_primarysales_api($orderid); //get primary sales orders
        if (empty($result)) {
            echo failure('Deleting Primary Sales Failed');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        echo failure('Orderid empty.');
        exit;
    }
}
// *************** SECONDARY SALES ****************** //
// ********************* Push Shops ****************** //
if ($action == "pushshops") {
    // add shops
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    /*$shoptype = 0;
		    $sphoneno2 = "";
		    $signature = "";
		    $photo = "";
		    $status = "";
		    $android_shopid = "0";
		    $prior_shopid = "0";
		    $goods_avail = '0';
		    $userkeyid = $dpd['userid'];
		    $srid = (int) ri($_REQUEST['srid']);
		    $areaid = (int) ri($_REQUEST['areaid']);
		    $prior_shopid = (int) ri($_REQUEST['prior_shopid']);
		    $shopname = ri($_REQUEST['shopname']);
		    $sphoneno = ri($_REQUEST['sphoneno']);
		    $sphoneno2 = ri($_REQUEST['sphoneno2']);
		    $owner = ri($_REQUEST['owner']);
		    $saddress = ri($_REQUEST['saddress']);
		    $semail = ri($_REQUEST['semail']);
		    $dob = ri($_REQUEST['cdob']);
		    $shoptype = ri($_REQUEST['shoptype']);
		    $signature = ri($_REQUEST['signature']);
		    $photo = ri($_REQUEST['photo']);
		    $status = ri($_REQUEST['status']); //live =0 & offline=1
		    $goods_avail = ri($_REQUEST['goodavail']);
	*/
    $shopObj = new stdClass();
    $shopObj->srid = (int) ri($_REQUEST['srid']);
    $shopObj->areaid = (int) ri($_REQUEST['areaid']);
    $shopObj->prior_shopid = isset($_REQUEST['prior_shopid']) ? $_REQUEST['prior_shopid'] : 0;
    $shopObj->shopname = ri($_REQUEST['shopname']);
    $shopObj->sphoneno = ri($_REQUEST['sphoneno']);
    $shopObj->sphoneno2 = isset($_REQUEST['sphoneno2']) ? $_REQUEST['sphoneno2'] : '';
    $shopObj->owner = ri($_REQUEST['owner']);
    $shopObj->saddress = ri($_REQUEST['saddress']);
    $shopObj->semail = ri($_REQUEST['semail']);
    $shopObj->dob = ri($_REQUEST['cdob']);
    $shopObj->shoptype = isset($_REQUEST['shoptype']) ? $_REQUEST['shoptype'] : 0;
    $shopObj->signature = isset($_REQUEST['signature']) ? $_REQUEST['signature'] : '';
    $shopObj->photo = isset($_REQUEST['photo']) ? $_REQUEST['photo'] : '';
    $shopObj->status = ri($_REQUEST['status']); //live =0 & offline=1
    $shopObj->goods_avail = isset($_REQUEST['goodavail']) ? $_REQUEST['goodavail'] : 0;
    $shopObj->androidshopid = ri($_REQUEST['androidshopid']);
    $shopObj->erpusertoken = ri($_REQUEST['erpusertoken']);
    $shopObj->use_erp = $dpd['use_erp'];
    $shopObj->userkeyid = $dpd['userid'];
    $shopObj->customerno = $dpd['customerno'];
    $shopObj->cGroupId = ri($_REQUEST['cGroupId']);
    if ($shopObj->srid == 0 || $shopObj->areaid == 0 || $shopObj->shopname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new sales($dpd['customerno'], $dpd['userid']);
        $shopObj->distid = $mob->getareaidtodistid_api($shopObj->areaid);
        if ($mob->is_shopname_exists($shopObj->shopname, $shopObj->areaid)) {
            echo failure('Shop Name already exists');
            exit;
        }
        $shopId = addSecondarySalesShop($shopObj);
        if (isset($shopId) && $shopId != 0) {
            echo success('Shop Added sucessfully');
        } else {
            echo success('Shop Added unsucessfully');
        }
        exit;
    }
}
if ($action == "pushshopsi") {
    // add shops
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $shoptype = 0;
    $sphoneno2 = "";
    $signature = "";
    $photo = "";
    $status = "";
    $android_shopid = "0";
    $prior_shopid = "0";
    $goods_avail = '0';
    $attachdata = $_REQUEST['attachdata'];
    $adata = json_decode($attachdata);
    if (isset($adata)) {
        foreach ($adata as $row) {
            $srid = isset($row->srid) ? $row->srid : '0';
            $areaid = isset($row->areaid) ? $row->areaid : '0';
            $prior_shopid = isset($row->prior_shopid) ? $row->prior_shopid : '';
            $shopname = isset($row->shopname) ? $row->shopname : '';
            $sphoneno = isset($row->sphoneno) ? $row->$row->sphoneno : '';
            $owner = isset($row->owner) ? $row->owner : '';
            $saddress = isset($row->saddress) ? $row->saddress : "";
            $semail = isset($row->semail) ? $row->semail : '';
            $dob = isset($row->cdob) ? $row->cdob : "";
            $shoptype = isset($row->shoptype) ? $row->shoptype : '';
            $signature = isset($row->signature) ? $row->signature : "";
            $photo = isset($row->photo) ? $row->photo : "";
//$status = $row->status;  //live =0 & offline=1
            $goods_avail = isset($row->goodavail) ? $row->goodavail : "";
            $androidshopid = isset($row->androidshopid) ? $row->androidshopid : '';
        }
    }
    if ($srid == "0" || $areaid == '0' || $shopname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new sales($dpd['customerno'], $dpd['userid']);
        $distid = $mob->getareaidtodistid_api($areaid);
        if ($mob->is_shopname_exists($shopname)) {
            echo failure('Shop Name already exists');
            exit;
        }
        if ($status == 1) {
            $mob->add_shopdata_api($prior_shopid, $shoptype, $distid, $srid, $areaid, $shopname, $sphoneno, $sphoneno2, $owner, $saddress, $semail, $dob, $signature, $photo, 1, $androidshopid, $goods_avail);
            echo success('Shop Added sucessfully');
        } else {
            $mob->add_shopdata_api($prior_shopid, $shoptype, $distid, $srid, $areaid, $shopname, $sphoneno, $sphoneno2, $owner, $saddress, $semail, $dob, $signature, $photo, 0, $androidshopid, $goods_avail);
            echo success('Shop Added sucessfully');
        }
// Resequence
        exit;
    }
}
if ($action == 'pullsecondarysales') {
    //get all secondary sales  view by sr or asm
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $shopid = exit_issetor($_REQUEST['shopid'], json_encode(array('result' => 'failure'))); //'Shop ID not found'
    $userid = exit_issetor($_REQUEST['srid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    $is_deadstock = exit_issetor($_REQUEST['is_deadstock'], json_encode(array('result' => 'failure'))); //'Deadstock??
    if (!empty($userid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkey_id = $dpd['userid'];
        $role = $dpd['role'];
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->get_secondarysales_orders_api($userid, $userkey_id, $shopid); //get secondary sales orders
        if (empty($result)) {
            echo failure('No secondary sales order');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'pullsecondaryorders') {
    //get all secondary sales  view for sr or asm
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = exit_issetor($_REQUEST['srid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    $distid = exit_issetor($_REQUEST['distid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    $selected_date = exit_issetor($_REQUEST['selected_date'], json_encode(array('result' => 'failure'))); //'selected date ID not found'
    if (!empty($userid) && !empty($selected_date)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkey_id = $dpd['userid'];
        $role = $dpd['role'];
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->get_secondarysales_orderlist_api($userid, $userkey_id, $distid, $selected_date); //get secondary sales orders
        if (empty($result)) {
            echo failure('No secondary sales order');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
//pull deadstock list
if ($action == 'pulldeadstock') {
    //get deadstock  orders
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $shopid = exit_issetor($_REQUEST['shopid'], json_encode(array('result' => 'failure'))); //'Shop ID not found'
    $userid = exit_issetor($_REQUEST['srid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    if (!empty($userid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkey_id = $dpd['userid'];
        $role = $dpd['role'];
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->get_deadstock_orders_api($userid, $userkey_id, $shopid); //get deadstock
        if (empty($result)) {
            echo failure('No deadstock');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'pulldeadstockorders') {
    //get deadstock orders for view only todays date
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = exit_issetor($_REQUEST['srid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    $distid = exit_issetor($_REQUEST['distid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    $selected_date = exit_issetor($_REQUEST['selected_date'], json_encode(array('result' => 'failure'))); //'User ID not found'
    if (!empty($userid) && !empty($selected_date)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkey_id = $dpd['userid'];
        $role = $dpd['role'];
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->get_deadstock_orderlist_api($userid, $userkey_id, $distid, $selected_date); //get deadstock
        if (empty($result)) {
            echo failure('No deadstock');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Pull Secondary Sales Details
if ($action == 'pullsecondarysales_details') {
    //get  secondary sales order by orderid
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = (int) ri($_REQUEST['orderid']);
    if (!empty($orderid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->get_secondarysales_details_api($orderid); //get primary sales orders
        if (empty($result)) {
            echo failure('No secondary sales orders');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Pull deadstock Sales Details
if ($action == 'pulldeadstock_details') {
    //get  secondary sales order by orderid
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = (int) ri($_REQUEST['orderid']);
    if (!empty($orderid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->get_deadstock_details_api($orderid); //get primary sales orders
        if (empty($result)) {
            echo failure('No secondary sales orders');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Push Secondary Sales
if ($action == "pushretailer_order") {
    //add secondary sales or deadstock
    $is_deadstock = 0;
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $is_deadstock = exit_issetor($_REQUEST['is_deadstock'], json_encode(array('result' => 'failure'))); //'Deadstock??
    $reason = exit_issetor($_REQUEST['reason'], json_encode(array('result' => 'failure'))); //'Reason not found'
    $discount = exit_issetor($_REQUEST['discount'], json_encode(array('result' => 'failure'))); //'Discount not found'
    $distid = exit_issetor($_REQUEST['distid'], json_encode(array('result' => 'failure'))); //'Dist  not found'
    //$skudata = exit_issetor($_REQUEST['skudata'], json_encode(array('result' => 'failure'))); //'Sku data  not found'
    $shopid = exit_issetor($_REQUEST['shopid'], json_encode(array('result' => 'failure'))); //'shopid data  not found'
    $srid = exit_issetor($_REQUEST['srid'], json_encode(array('result' => 'failure'))); //'srid data  not found'
    $manual_cn = ri($_REQUEST['manual_cn']); //'srid data  not found'
    $deliverydatetime = (isset($_REQUEST['deliverydatetime']) AND $_REQUEST['deliverydatetime'] != 0) ? $_REQUEST['deliverydatetime'] : '';
    $driverid = ri($_REQUEST['driverid']); //'srid data  not found'
    $erpusertoken = ri($_REQUEST['erpusertoken']); //'srid data  not found'
    $skudata = json_decode($_REQUEST['skudata'], true);
    $orderdate = exit_issetor($_REQUEST['orderdate'], json_encode(array('result' => 'failure'))); //'orderdate data  not found'
    $otId = isset($_REQUEST['otId']) ? $_REQUEST['otId'] : 1;
    //$otId = exit_issetor($_REQUEST['otId'], json_encode(array('result' => 'failure'))); //otId data  not found(order type id)
    //echo "ot Id - ". $otId; die;
    $orderAmt = isset($_REQUEST['orderAmt']) ? $_REQUEST['orderAmt'] : 0;
    $orderStatus = 0; // Success
    $pendingAmt = $orderAmt;
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    if ($is_deadstock == "1") {
        $msg = "Deadstock";
    } else {
        $msg = "Secondary";
    }
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    if ($dpd['customerno'] == 698) {
        $userId = $um->getDistributorId($dpd['customerno']);
        if (isset($userId) && $userId != 0) {
            $distid = $userId;
        }
    }
    //print_r($skudata);die();
    $role = $dpd['role'];
    $orderObj = new stdClass();
    $orderObj->role = $role;
    $orderObj->shopid = $shopid;
    $orderObj->skudata = $skudata;
    $orderObj->orderdate = $orderdate;
    $orderObj->is_deadstock = $is_deadstock;
    $orderObj->reason = $reason;
    $orderObj->discount = $discount;
    $orderObj->distid = $distid;
    $orderObj->srid = $srid;
    $orderObj->otId = $otId;
    $orderObj->deliverydatetime = $deliverydatetime;
    $orderObj->manual_cn = $manual_cn;
    $orderObj->driverid = $driverid;
    $orderObj->erpusertoken = $erpusertoken;
    $orderObj->use_erp = $dpd['use_erp'];
    $orderObj->msg = $msg;
    $orderObj->orderStatus = $orderStatus;
    $orderObj->orderAmt = $orderAmt;
    $orderObj->pendingAmt = $pendingAmt;
    $orderObj->userid = $dpd['userid'];
    $orderObj->booksUserToken = $dpd['booksUserToken'];
    $orderObj->customerno = $dpd['customerno'];
    //print_r($orderObj);die();
    $orderId = addSecondarySalesOrder($orderObj);
    //$id = $mob->add_secondarysalesdata_api($role, $shopid, $skudata, $orderdate, $is_deadstock, $reason, $discount, $distid, $srid, $otId, $deliverydatetime);
    if (isset($orderId) && $orderId != 0) {
        $arr_p['Status'] = "successful";
        $arr_p['message'] = 'Added ' . $msg . ' order sucessfully';
        $arr_p['orderid'] = $orderId;
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = 'Added ' . $msg . ' order unsucessfully';
    }
    /*if (isset($id)) {
		    $arr_p['Status'] = "successful";
		    $arr_p['message'] = 'Added ' . $msg . ' order sucessfully';
		    $arr_p['orderid'] = $id;
		    if (isset($dpd['use_erp']) && $dpd['use_erp']==1) {
		    $objCN = new stdClass();
		    if(isset($manual_cn) and $manual_cn!=0 and $manual_cn !=""){
		    $manual_cn = $manual_cn;
		    $manual_booking_no = $id;
		    }else{
		    $manual_cn = $id;
		    $manual_booking_no = $id;
		    }
		    $objCN->userkey = $erpusertoken;
		    $objCN->manualCnNo = $manual_cn;
		    $objCN->deliveryDatetime = $deliverydatetime;
		    $objCN->moreProductDetails = $skudata;
		    $objCN->consigneeId = $shopid;
		    $objCN->clientId = $shopid;
		    $objCN->deliveryDatetime = $deliverydatetime;
		    $objCN->isDriverApp = $driverid;
		    $objCN->manualBookingNo = $manual_booking_no;
		    if(isset($otId) && $otId == 3){
		    $objCN->isClosed = 1;
		    }else{
		    $objCN->isClosed = 0;
		    }
		    //            ordertye = 3 then isClosed = 1
		    // print_r($objCN);die();
		    $ch = curl_init();
		    $objCN = json_encode($objCN);
		    $post_data = array(
		    'jsonreq' => $objCN,
		    );
		    // print_r($post_data);
		    //echo speedConstants::API_ERP_BOOKING;
		    curl_setopt($ch, CURLOPT_URL, speedConstants::API_ERP_BOOKING);
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
		    } else {
		    $arr_p['Status'] = "unsuccessful";
		    $arr_p['message'] = 'Added ' . $msg . ' order unsucessfully';
	*/
    echo json_encode($arr_p);
    exit;
}
// ------------------------------Push entry ----------------------------
if ($action == 'pushentry') {
    //add entry
    $latitude = null;
    $longitude = null;
    $radius = null;
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $srid = exit_issetor($_REQUEST['srid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $status = "";
    $userkeyid = $dpd['userid'];
    $shopid = (int) ri($_REQUEST['shopid']);
    $remark = ri($_REQUEST['remark']);
    $status = ri($_REQUEST['status']); //live =0 & offline=1
    $entrydate = ri($_REQUEST['entrydate']);
    if ($_GET["latitude"] != null) {
        $latitude = ri($_GET["latitude"], "string");
    }
    if ($_GET["longitude"] != null) {
        $longitude = ri($_GET["longitude"], "string");
    }
    if ($_GET["radius"] != null) {
        $radius = ri($_GET["radius"], "string");
    }
    if ($srid == "0" || $srid == "" || $shopid == "0" || $shopid == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($dpd['customerno'], $userkeyid);
        $role = $dpd['role'];
        if ($mob->is_entry_exists_today($srid, $shopid, $latitude, $longitude, $radius, $status)) {
            echo failure('entry already exists');
            exit;
        }
        if ($status == 1) {
            $mob->add_entrydata_api($role, $srid, $shopid, NULL, $latitude, $longitude, $radius, $entrydate, 1);
            echo success('Entry Added sucessfully');
        } else {
            $mob->add_entrydata_api($role, $srid, $shopid, NULL, $latitude, $longitude, $radius, $entrydate, 0);
            echo success('Entry Added sucessfully');
        }
        exit;
    }
}
//--------------pull entry ---------------------
// Pull shop entry
if ($action == 'pullentry') {
    //get all entry  view by sr or asm
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = $_REQUEST['srid']; //'User ID not found'
    if (!empty($userid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $userid);
        $userkey_id = $dpd['userid'];
        $result = $mob->get_entry_api($userid, $userkey_id); //get entry
        if (empty($result)) {
            echo failure('No shop entry data');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'delete_secondarysales') {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = $_REQUEST['orderid'];
    if (!empty($orderid) && $orderid != 'null') {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->delete_secondarysales_api($orderid); //delete secondarysales
        if (empty($result)) {
            echo failure('Deleting Secondary Sales Failed');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'delete_deadstock') {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = $_REQUEST['orderid'];
    if (!empty($orderid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->delete_deadstock_api($orderid); //delete deadstock
        if (empty($result)) {
            echo failure('Deleting Deadstock Failed');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == "get_otp_forgotpwd") {
    $username = $_REQUEST['username'];
    $mob = new Sales(0, 0);
    if (isset($username)) {
        $result = $mob->get_otp_forgotpwd($username);
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == "update_password") {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $newpwd = exit_issetor($_REQUEST['newpwd'], json_encode(array('result' => 'failure'))); //'newpassword not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    if (isset($userkey) && isset($newpwd)) {
        $result = $mob->update_password($userkey, $newpwd);
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
//update Secondary sales order
if ($action == "editsecondarysales") {
    //Update Secondary sales
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $skudata = json_decode($_REQUEST['skudata'], TRUE);
    $orderid = isset($_REQUEST['orderid']) ? $_REQUEST['orderid'] : 0;
    $otId = isset($_REQUEST['otId']) ? $_REQUEST['otId'] : 1;
    $deliverydatetime = isset($_REQUEST['deliverydatetime']) ? $_REQUEST['deliverydatetime'] : "";
    $erpusertoken = ri($_REQUEST['erpusertoken']); //'srid data  not found'
    $msg = "Secondary";
    if ($orderid != 0) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkeyid = $dpd['userid']; //userkey -> userid
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $role = $dpd['role'];
        $isUpdated = $mob->edit_secondarysalesdata_api($role, $skudata, $orderid, $userkeyid, $otId, $deliverydatetime);
#############################################################################################################
        if (isset($isUpdated) && $isUpdated == 1) {
            $arr_p['Status'] = "successful";
            $arr_p['message'] = 'Updated ' . $msg . ' order sucessfully';
            $arr_p['orderid'] = $orderid;
            if (isset($dpd['use_erp']) && $dpd['use_erp'] == 1) {
                $objCN = new stdClass();
                $manual_cn = $orderid;
                $manual_booking_no = $orderid;
                $objCN->userkey = $erpusertoken;
                $objCN->manualCnNo = $manual_cn;
                $objCN->deliveryDatetime = $deliverydatetime;
                $objCN->moreProductDetails = $skudata;
                $objCN->manualBookingNo = $manual_booking_no;
                if (isset($otId) && $otId == 3) {
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
                // print_r($post_data); die;
                //echo speedConstants::API_ERP_BOOKING;
                // curl_setopt($ch, CURLOPT_URL, speedConstants::API_ERP_UPDATE_CN);
                curl_setopt($ch, CURLOPT_URL, API_ERP_UPDATE_CN);
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
                // print_r($arrResponse);
            }
        } else {
            $arr_p['Status'] = "unsuccessful";
            $arr_p['message'] = 'Updated ' . $msg . ' order unsucessfully';
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
    }
    echo json_encode($arr_p);
    exit;
}
//update Deadstock
if ($action == "editdeadstock") {
    //Update deadstock
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $skudata = json_decode($_REQUEST['skudata'], TRUE);
    $orderid = $_REQUEST['orderid'];
    if (!empty($orderid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkeyid = $dpd['userid']; //userkey -> userid
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $role = $dpd['role'];
        $mob->edit_deadstockdata_api($role, $skudata, $orderid, $userkeyid);
        echo success('Update deadstock sucessfully');
        exit;
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Push inventory for distributor
if ($action == "pushinventory") {
    //add inventory stocks details
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $skudata = json_decode($_REQUEST['skudata'], TRUE);
    $distid = $_REQUEST['distributorid'];
    if ($distid != "" || $distid != NULL && !empty($userkey)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $role = $dpd['role'];
        $userid = $dpd['userid'];
        $result = $mob->add_inventorydata_api($role, $distid, $skudata, $userid);
        if ($result == TRUE) {
            echo success('Added inventory sucessfully');
        } else {
            echo failure('Inventory already exists.');
        }
    } else {
        echo failure('Mandatory parameter missing.');
    }
    exit;
}
if ($action == "pullinventory") {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $distid = $_REQUEST['distributorid'];
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    if ($distid != "" || $distid != NULL) {
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $role = $dpd['role'];
        $distinvdetails = $mob->get_inventory_list($distid, $dpd['userid']);
        if (empty($distinvdetails)) {
            echo failure('No inventory data');
            exit;
        } else {
            echo success_json($distinvdetails);
            exit;
        }
    } else {
        echo failure('Mandatory parameter missing.');
    }
}
if ($action == "editinventory") {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $qty = $_REQUEST['qty'];
    $invid = $_REQUEST['orderid'];
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    $role = $dpd['role'];
    $distinvdetails = $mob->edit_inventorydata_api($role, $qty, $invid, $dpd['userid']);
    echo success('Update inventory sucessfully');
}
if ($action == 'editinventorydata') {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $invid = $_REQUEST['orderid'];
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    $editdata = $mob->get_editinventorydata($invid);
    if (empty($editdata)) {
        echo failure('No inventory data');
        exit;
    } else {
        echo success_json($editdata);
        exit;
    }
}
if ($action == 'deleteinventory') {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = $_REQUEST['orderid'];
    if (!empty($orderid)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $result = $mob->deleteinventory($orderid); //delete inventory
        if (empty($result)) {
            echo failure('Deleting Inventory Failed');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Pull Category by inventory stocks
if ($action == 'pullcategoryi') {
    // pull all category by distributor inventory
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $dm1 = new sales($dpd['customerno'], $dpd['userid']);
    $result = $dm1->get_category_api(); //get category
    if (empty($result)) {
        echo failure('No Category data');
        exit;
    } else {
        echo success_json($result);
        exit;
    }
}
// Search SKU
if ($action == 'pullskui') {
    // pull all sku by distributor inventory
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $distid = isset($_REQUEST['distid']) ? $_REQUEST['distid'] : '';
    $searchtxt = isset($_REQUEST['searchtxt']) ? $_REQUEST['searchtxt'] : '';
    $categoryid = isset($_REQUEST['categoryid']) ? $_REQUEST['categoryid'] : '';
    if (!empty($distid) && $distid != 'NULL' && !empty($categoryid) && $categoryid != 'null') {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $dpd['userid']);
        $result = $dm1->get_sku_invapi_search($distid, $categoryid, $searchtxt);
        if (empty($result)) {
            echo failure('No Sku Data');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        echo failure('Parameters missing');
    }
}
if ($action == 'pullalldatam') {
    //merge
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userkey = isset($_REQUEST['userkey']) ? $_REQUEST['userkey'] : ""; //'User ID not found'
    $is_Sendall = isset($_REQUEST['issendall']) ? $_REQUEST['issendall'] : "0";
//$srid = exit_issetor($_REQUEST['srid'], json_encode(array('result' => 'failure')));
    if ($userkey != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $dpd['userid']);
        $alldata = $dm1->getAllDataApimerge($dpd, $is_Sendall);
        if (empty($alldata)) {
            echo failure('No Data found');
            exit;
        } else {
            echo success_json($alldata);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'pushstatus') {
    // pushstatus
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userkey = isset($_REQUEST['userkey']) ? $_REQUEST['userkey'] : ""; //'User ID not found'
    if ($userkey != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $dpd['userid']);
        $alldata = $dm1->pushstatus_updated_data($dpd); //push stautus
        echo success_json($alldata);
        exit;
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == "syncdata") {
    //get all syncdata  push shops, push orders, push entry
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $alldata = json_decode($_REQUEST['jsondata'], TRUE);
    $resultarr = array();
    if (!empty($userkey)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        $role = $dpd['role'];
        $userid = $dpd['userid'];
        if (isset($alldata)) {
            foreach ($alldata as $key => $val) {
                if ($key == "primaryorder") {
                    $primarydata = $val;
                }
                if ($key == "secondaryorder") {
                    $secondarydata = $val;
                }
                if ($key == "deadstockorder") {
                    $deadstockdata = $val;
                }
                if ($key == "entrydetails") {
                    $entrydata = $val;
                }
                if ($key == "shopdetials") {
                    $shopdata = $val;
                }
            }
        }
        $primaryinsertcount = array();
        $secorderinsertcount = array();
        $deadstockinsertcount = array();
        $entryinsertcount = array();
        $shopinsertcount = array();
        if (!empty($primarydata)) {
            $primarydatacount = count($primarydata);
            for ($i = 0; $i < $primarydatacount; $i++) {
                $data = $primarydata[$i];
                $distid = $data['distributorid'];
                $orderdate = $data['orderdate'];
                $skudata = $data['skudata'];
                $userid = $data['srid'];
                $userkeyid = $dpd['userid']; //userkey -> userid
                $customerno = $dpd['customerno'];
                $mob = new Sales($customerno, $userid); //userid as srid
                $primaryinsertcount[] = $mob->add_primarysalesdata_api($role, $distid, $skudata, $orderdate, $userkeyid);
            }
        }
        if (!empty($secondarydata)) {
            $secondarydatacount = count($secondarydata);
            for ($i = 0; $i < $secondarydatacount; $i++) {
                $data = $secondarydata[$i];
                /*$is_deadstock = $data['is_deadstock'];
					                $reason = $data['reason'];
					                $discount = $data['discount'];
					                $shopid = $data['shopid'];
					                $distid = $data['distid'];
					                $orderdate = $data['orderdate'];
					                $srid = $data['srid'];
					                $role = $dpd['role'];
				*/
                $orderObj = new stdClass();
                $orderObj->role = $dpd['role'];
                $orderObj->shopid = $data['shopid'];
                $orderObj->skudata = $data['skudata'];
                $orderObj->orderdate = $data['orderdate'];
                $orderObj->is_deadstock = $data['is_deadstock'];
                $orderObj->reason = $data['reason'];
                $orderObj->discount = $data['discount'];
                $orderObj->distid = $data['distid'];
                $orderObj->srid = $data['srid'];
                $orderObj->otId = isset($data['otId']) ? $data['otId'] : 1;
                $orderObj->deliverydatetime = isset($data['deliverydatetime']) ? $data['deliverydatetime'] : '';
                $orderObj->manual_cn = isset($data['manual_cn']) ? $data['manual_cn'] : '';
                $orderObj->driverid = isset($data['driverid']) ? $data['driverid'] : '';
                $orderObj->erpusertoken = isset($data['erpusertoken']) ? $data['erpusertoken'] : '';
                $orderObj->use_erp = $dpd['use_erp'];
                $orderObj->msg = isset($msg) ? $msg : 'Secondary';
                $orderObj->userid = $dpd['userid'];
                $orderObj->customerno = $dpd['customerno'];
                $secorderinsertcount[] = addSecondarySalesOrder($orderObj);
                // $secorderinsertcount[] = $mob->add_secondarysalesdata_api($role, $shopid, $skudata, $orderdate, $is_deadstock, $reason, $discount, $distid, $srid,$otId, $deliverydatetime);
            }
        }
        if (!empty($deadstockdata)) {
            $deadstockinsertcount = array();
            $deadstockdatacount = count($deadstockdata);
            for ($i = 0; $i < $deadstockdatacount; $i++) {
                $data = $deadstockdata[$i];
                /*$is_deadstock = $data['is_deadstock'];
					                $reason = $data['reason'];
					                $discount = $data['discount'];
					                $shopid = $data['shopid'];
					                $distid = $data['distid'];
					                $orderdate = $data['orderdate'];
					                $srid = $data['srid'];
					                $role = $dpd['role'];
				*/
                $orderObj = new stdClass();
                $orderObj->role = $dpd['role'];
                $orderObj->shopid = $data['shopid'];
                $orderObj->skudata = $data['skudata'];
                $orderObj->orderdate = $data['orderdate'];
                $orderObj->is_deadstock = $data['is_deadstock'];
                $orderObj->reason = $data['reason'];
                $orderObj->discount = $data['discount'];
                $orderObj->distid = $data['distid'];
                $orderObj->srid = $data['srid'];
                $orderObj->otId = isset($data['otId']) ? $data['otId'] : '';
                $orderObj->deliverydatetime = isset($data['deliverydatetime']) ? $data['deliverydatetime'] : '';
                $orderObj->manual_cn = isset($data['manual_cn']) ? $data['manual_cn'] : '';
                $orderObj->driverid = isset($data['driverid']) ? $data['driverid'] : '';
                $orderObj->erpusertoken = isset($data['erpusertoken']) ? $data['erpusertoken'] : '';
                $orderObj->use_erp = $dpd['use_erp'];
                $orderObj->msg = isset($msg) ? $msg : 'Deadstock';
                $orderObj->userid = $dpd['userid'];
                $orderObj->customerno = $dpd['customerno'];
                $deadstockinsertcount[] = addSecondarySalesOrder($orderObj);
                // $deadstockinsertcount[] = $mob->add_secondarysalesdata_api($role, $shopid, $skudata, $orderdate, $is_deadstock, $reason, $discount, $distid, $srid, $otId, $deliverydatetime);
            }
        }
        if (!empty($entrydata)) {
            $entrydatacount = count($entrydata);
            for ($i = 0; $i < $entrydatacount; $i++) {
                $data = $entrydata[$i];
                $shopid = isset($data['shopid']) ? $data['shopid'] : "";
                $latitude = isset($data['latitude']) ? $data['latitude'] : "";
                $longitude = isset($data['longitude']) ? $data['longitude'] : "";
                $radius = isset($data['radius']) ? $data['radius'] : "";
                $srid = isset($data['srid']) ? $data['srid'] : "";
                $status = isset($data['status']) ? $data['status'] : "";
                //$entrydate = $data['entrydate'];
                $entrydate = NULL;
                if ($mob->is_entry_exists_today($userid, $shopid, $latitude, $longitude, $radius, $status)) {
                    continue;
                } else {
                    $mob->add_entrydata_api($role, $userid, $shopid, NULL, $latitude, $longitude, $radius, $entrydate, $status);
                    $entryinsertcount[] = 1;
                }
            }
        }
        if (!empty($shopdata)) {
            $shoptype = 0;
            $sphoneno2 = "";
            $signature = "";
            $photo = "";
            $status = "";
            $android_shopid = "0";
            $prior_shopid = "0";
            $goods_avail = '0';
            $shopcount = count($shopdata);
            // $shopinsertedidarr = array();
            for ($i = 0; $i < $shopcount; $i++) {
                $data = $shopdata[$i];
                /*$dob = isset($data['cdob']) ? $data['cdob'] : "";
					                $areaid = isset($data['areaid']) ? $data['areaid'] : "";
					                $shopname = isset($data['shopname']) ? $data['shopname'] : "";
					                $shoptype = isset($data['shoptype']) ? $data['shoptype'] : "";
					                $sphoneno = isset($data['sphoneno']) ? $data['sphoneno'] : "";
					                $sphoneno2 = isset($data['sphoneno2']) ? $data['sphoneno2'] : "";
					                $owner = isset($data['owner']) ? $data['owner'] : "";
					                $saddress = isset($data['saddress']) ? $data['saddress'] : "";
					                $semail = isset($data['semail']) ? $data['semail'] : "";
					                $srid = $data['srid'];
					                $goodavail = isset($data['goodavail']) ? $data['goodavail'] : "";
					                $prior_shopid = isset($data['prior_shopid']) ? $data['prior_shopid'] : "";
					                $androidshopid = isset($data['androidshopid']) ? $data['androidshopid'] : "";
					                $photo = isset($data['photo']) ? $data['photo'] : "";
					                $signature = isset($data['signature']) ? $data['signature'] : "";
				*/
                $shopObj = new stdClass();
                $shopObj->srid = (int) ri($data['srid']);
                $shopObj->areaid = (int) ri($data['areaid']);
                $shopObj->prior_shopid = isset($data['prior_shopid']) ? $data['prior_shopid'] : 0;
                $shopObj->shopname = ri($data['shopname']);
                $shopObj->sphoneno = ri($data['sphoneno']);
                $shopObj->sphoneno2 = isset($data['sphoneno2']) ? $data['sphoneno2'] : '';
                $shopObj->owner = ri($data['owner']);
                $shopObj->saddress = ri($data['saddress']);
                $shopObj->semail = ri($data['semail']);
                $shopObj->dob = ri($data['cdob']);
                $shopObj->shoptype = isset($data['shoptype']) ? $data['shoptype'] : 0;
                $shopObj->signature = isset($data['signature']) ? $data['signature'] : '';
                $shopObj->photo = isset($data['photo']) ? $data['photo'] : '';
                $shopObj->status = isset($data['status']) ? $data['status'] : 1; //live =0 & offline=1
                $shopObj->goods_avail = isset($data['goodavail']) ? $data['goodavail'] : 0;
                $shopObj->androidshopid = ri($data['androidshopid']);
                $shopObj->erpusertoken = ri($data['erpusertoken']);
                $shopObj->use_erp = $dpd['use_erp'];
                $shopObj->userkeyid = $dpd['userid'];
                $shopObj->customerno = $dpd['customerno'];
                $shopObj->distid = $mob->getareaidtodistid_api($shopObj->areaid);
                if ($mob->is_shopname_exists($shopObj->shopname, $shopObj->areaid)) {
                    continue;
                } else {
                    $shopId = addSecondarySalesShop($shopObj);
                    // $shopid = $mob->add_shopdata_api($prior_shopid, $shoptype, $distid, $srid, $areaid, $shopname, $sphoneno, $sphoneno2, $owner, $saddress, $semail, $dob, $signature, $photo, $status, $androidshopid, $goods_avail);
                    $shopinsertcount[] = 1;
                }
            }
        }
        $resultarr = array(
            'primaryinsertcount' => count($primaryinsertcount),
            'secondaryinsertcount' => count($secorderinsertcount),
            'deadstockinsertcount' => count($deadstockinsertcount),
            'entryinsertcount' => array_sum($entryinsertcount),
            'shopinsertcount' => array_sum($shopinsertcount)
        );
        echo success_json($resultarr);
    } else {
        echo failure('Mandatory parameter missing.');
    }
    exit;
}
if ($action == 'pushattendance') {
    // Attendance
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userkey = isset($_REQUEST['userkey']) ? $_REQUEST['userkey'] : ""; //'User ID not found'
    $status = exit_issetor($_REQUEST['status'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $lat = isset($_REQUEST['latitude']) ? $_REQUEST['latitude'] : '';
    $long = isset($_REQUEST['longitude']) ? $_REQUEST['longitude'] : '';
    $obj = new stdClass();
    $obj->lat = $lat;
    $obj->lng = $long;
    $obj->status = $status;
    if ($userkey != "" && $status != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // Attendance
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $dpd['userid']);
        $alldata = $dm1->pushattendance($obj);
        echo success_json($alldata);
        exit;
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'getattendancestatus') {
    // Attendance status
    //0-ON , 1-OFF
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userkey = isset($_REQUEST['userkey']) ? $_REQUEST['userkey'] : ""; //'User ID not found'
    if ($userkey != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // Attendance
        //echo"<pre>"; print_r($dpd); exit;
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $dpd['userid']);
        $alldata = $dm1->pullattendance_status($dpd);
        echo success_json($alldata);
        exit;
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// Pull Order Type
if ($action == 'pullordertype') {
    // pull all order type
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $dm1 = new sales($dpd['customerno'], $dpd['userid']);
    $result = $dm1->get_ordertype_api(); //get order type
    if (empty($result)) {
        echo failure('No ordertype data');
        exit;
    } else {
        echo success_json($result);
        exit;
    }
}
######## Vora Enterprise ########
// getFranchiseList
if ($action == 'getFranchiseList') {
    // pull all shops
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = isset($_REQUEST['srid']) ? $_REQUEST['srid'] : ""; //'User ID not found'
    if ($userid != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $userid);
        $alldata = $dm1->getFranchiseList(); //get shop lists
        if (empty($alldata)) {
            echo failure('No franchise Found');
            exit;
        } else {
            echo success_json($alldata);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
// getProductList
if ($action == 'getProductList') {
    // pull all shops
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = isset($_REQUEST['srid']) ? $_REQUEST['srid'] : ""; //'User ID not found'
    $franchiseId = isset($_REQUEST['franchiseId']) ? $_REQUEST['franchiseId'] : "";
    $pageIndex = isset($_REQUEST['pageIndex']) ? $_REQUEST['pageIndex'] : 1;
    $pageSize = isset($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : 10;
    $searchString = isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : '';
    $shopId = isset($_REQUEST['shopId']) ? $_REQUEST['shopId'] : 0;
    if ($userid != "" && $franchiseId != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $userid);
        $stdObj = new stdClass();
        $stdObj->franchiseId = $franchiseId;
        $stdObj->userId = $userid;
        $stdObj->pageIndex = $pageIndex;
        $stdObj->pageSize = $pageSize;
        $stdObj->customerNo = $dpd['customerno'];
        $stdObj->searchString = $searchString;
        $stdObj->shopId = $shopId;
        $alldata = $dm1->getProductList($stdObj); //get shop lists
        //print_r($alldata);die();
        if (isset($alldata) && !empty($alldata)) {
            foreach ($alldata as $detail) {
                foreach ($detail as $key => $val) {
                    if (isset($val) && ($val == '0' || $val == '0000-00-00' || $val == '1970-01-01' || $val == '00-00-0000' || $val == '01-01-1970')) {
                        $val = '';
                    }
                    $detail_temp[$key] = cleanNonPritableChars($val);
                    if ($key == 'productId') {
                        $objOffer = new stdClass();
                        $objOffer->productId = $val;
                        $objOffer->customerNo = $dpd['customerno'];
                        $arrOffers = $dm1->getOffers($objOffer);
                        if (isset($arrOffers) && !empty($arrOffers)) {
                            $detail_temp['offers'] = $arrOffers;
                        } else {
                            $detail_temp['offers'] = array();
                        }
                    }
                }
                $arrFinalArray[] = $detail_temp;
            }
        }
        if (empty($arrFinalArray)) {
            echo failure('No Products Found');
            exit;
        } else {
            echo success_json($arrFinalArray);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'getAllShopsOrder') {
    //get all secondary sales  view for sr or asm
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = exit_issetor($_REQUEST['srid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    // $distid = exit_issetor($_REQUEST['distid'], json_encode(array('result' => 'failure'))); //'User ID not found'
    $selected_date = exit_issetor($_REQUEST['selected_date'], json_encode(array('result' => 'failure'))); //'selected date ID not found'
    if (!empty($userid) && !empty($selected_date)) {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $userkey_id = $dpd['userid'];
        $role = $dpd['role'];
        $mob = new Sales($dpd['customerno'], $dpd['userid']);
        // $result = $mob->get_secondarysales_orderlist_api($userid, $userkey_id, $distid, $selected_date); //get secondary sales orders
        $stdObj = new stdClass();
        $stdObj->userkey_id = isset($userkey_id) ? $userkey_id : 0;
        $stdObj->userId = isset($userid) ? $userid : 0;
        $stdObj->selected_date = isset($selected_date) ? $selected_date : '';
        $stdObj->customerNo = $dpd['customerno'];
        $result = $mob->getAllShopsOrder($stdObj); //get secondary sales orders
        if (empty($result)) {
            echo failure('No secondary sales order');
            exit;
        } else {
            echo success_json($result);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'getShops') {
    // pull all shops against SR ID -- it was Developed as per requirement of Vora Enterprises
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = isset($_REQUEST['srid']) ? $_REQUEST['srid'] : ""; //'User ID not found'
    $searchString = isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : "";
    $todaysDate = date("Y-m-d");
    if ($userid != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $userid);
        $stdObj = new stdClass();
        $stdObj->userkey_id = isset($userkey_id) ? $userkey_id : 0;
        $stdObj->userId = isset($userid) ? $userid : 0;
        $stdObj->dayId = date('N', strtotime($todaysDate)); // get day number
        if (isset($searchString) && $searchString != "") {
            $stdObj->dayId = -1; // -1 means to get all day shops
        }
        $stdObj->searchString = isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : "";
        $stdObj->customerNo = $dpd['customerno'];
        // $todaysDate = '2019-02-13';
        // date('l'); // get current day
        $alldata = $dm1->getShops($stdObj); //get shop lists
        if (empty($alldata)) {
            echo failure('No Shops Found');
            exit;
        } else {
            echo success_json($alldata);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'getDashboard') {
    // pull all shops against SR ID -- it was Developed as per requirement of Vora Enterprises
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = isset($_REQUEST['srid']) ? $_REQUEST['srid'] : ""; //'User ID not found'
    $searchString = isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : "";
    $todaysDate = date("Y-m-d");
    $alldata = array();
    if ($userid != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $userid);
        $stdObj = new stdClass();
        $stdObj->userkey_id = isset($userkey_id) ? $userkey_id : 0;
        $stdObj->userId = isset($userid) ? $userid : 0;
        $stdObj->dayId = date('N', strtotime($todaysDate)); // get day number
        $stdObj->searchString = isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : "";
        $stdObj->customerNo = $dpd['customerno'];
        // $todaysDate = '2019-02-13';
        // date('l'); // get current day
        $alldata = $dm1->getShops($stdObj); //get shop lists
        $totalShops = count($alldata);
        $tempArray = array(
            "totalShops" => $totalShops
        );
        if (empty($tempArray)) {
            echo failure('No Shops Found');
            exit;
        } else {
            echo success_json($tempArray);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == 'getArea') {
    // pull all area by customer no
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $userid = isset($_REQUEST['srid']) ? $_REQUEST['srid'] : ""; //'User ID not found'
    if ($userid != "") {
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); // person data
        if (empty($dpd)) {
            echo failure('No Userdata');
            exit;
        }
        $dm1 = new sales($dpd['customerno'], $userid);
        $alldata = $dm1->get_area(); //get shop lists
        if (empty($alldata)) {
            echo failure('No Area Found');
            exit;
        } else {
            echo success_json($alldata);
            exit;
        }
    } else {
        $arr_p['Status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
?>
