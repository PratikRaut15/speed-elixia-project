<?php

/**
 * Ajax page of sales-module
 */
require_once 'sales_function.php';

$customerno = exit_issetor($_SESSION['customerno'], 'Please login');
$userid = exit_issetor($_SESSION['userid'], 'Please login');
$action = ri($_REQUEST['action']);

if ($action == 'stypeadd') {
    $stypename = ri($_REQUEST['stypename']);
    if ($stypename == "") {
        failure('Please enter Shop type');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        if ($mob->is_shoptype_exists($stypename)) {
            echo failure('Type already exists');
            exit;
        }
        $mob->add_shoptypedata($stypename);
        echo success('Shop Type added sucessfully');
        exit;
    }
} else if ($action == 'category') {
    $catname = ri($_REQUEST['catname']);
    if ($catname == "") {
        failure('Please enter Category name');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        if ($mob->is_category_exists($catname)) {
            echo failure('Category already exists');
            exit;
        }
        $mob->add_categorydata($catname);
        echo success('Category added sucessfully');
        exit;
    }
} else if ($action == 'editcategory') {
    $catname = ri($_REQUEST['catname']);
    $catid = (int) ri($_REQUEST['catid']);
    if ($catname == "" || $catid == "") {
        failure('Please enter Category name');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
//        if($mob->is_category_exists($catname)){
//            echo failure('Category already exists'); exit;
//        }
        $mob->update_categorydata($catname, $catid);
        echo success('Category Updated sucessfully');
        exit;
    }
} else if ($action == 'editshtype') {
    $stypename = ri($_REQUEST['stypename']);
    $shid = (int) ri($_REQUEST['shid']);
    if ($stypename == "" || $shid == "") {
        failure('Please enter Shop type name');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->update_shtypedata($stypename, $shid);
        echo success('Shop type Updated sucessfully');
        exit;
    }
} else if ($action == 'delcateogry') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_categorydata($id);
    echo success('Category Deleted sucessfully');
    exit;
} else if ($action == 'delorder') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_orderdata($id);
    echo success('Order Deleted sucessfully');
    exit;
} else if ($action == 'delstyle') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_styledata($id);
    echo success('Style Deleted sucessfully');
    exit;
} else if ($action == 'style') {
    $styleno = ri($_REQUEST['styleno']);
    $category = (int) ri($_REQUEST['category']);
    $mrp = ri($_REQUEST['mrp']);
    $distprice = ri($_REQUEST['distprice']);
    $retprice = ri($_REQUEST['retprice']);
    $comselprince = ri($_REQUEST['companysellingprice']);
    $carton = ri($_REQUEST['carton']);
    if ($styleno == "" || $category == "0" || $mrp == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        if ($mob->is_style_number_exists($styleno)) {
            echo failure('Style Number already exists');
            exit;
        }
        $lastid = $mob->insert_styledata1($styleno, $category, $mrp, $distprice, $retprice,$comselprince, $carton);
        //echo success('Style Added sucessfully');
        $result = array('Status' => 'Success', 'Msg' => 'Style Added sucessfully', 'skuid' => $lastid);
        $result = json_encode($result);
        echo $result;
        exit;
    }
} else if ($action == 'styleedit') {
    $styleid = (int) ri($_REQUEST['styleid']);
    $styleno = ri($_REQUEST['styleno']);
    $category = (int) ri($_REQUEST['category']);
    $mrp = ri($_REQUEST['mrp']);
    $distprice = ri($_REQUEST['distprice']);
    $retprice = ri($_REQUEST['retprice']);
    $comselprince = ri($_REQUEST['companysellingprice']);
    $carton = ri($_REQUEST['carton']);

    if ($styleid == "" || $styleno == "" || $category == "0" || $mrp == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->update_styledata($styleid, $styleno, $category, $mrp, $distprice, $retprice, $carton, $comselprince);
        //echo success('Style Updated sucessfully');
        // exit;
        $result = array('Status' => 'Success', 'Msg' => 'Style Updated sucessfully', 'skuid' => $styleid);
        $result = json_encode($result);
        echo $result;
        exit;
    }
} else if ($action == 'state') {
    $statename = $_REQUEST['statename'];
    if ($statename == "") {
        failure('Please enter state name');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        if ($mob->is_state_exists($statename)) {
            echo failure('State already exists');
            exit;
        }
        $mob->add_statedata($statename);
        echo success('State added sucessfully');
        exit;
    }
} else if ($action == 'editstate') {
    $statename = ri($_REQUEST['statename']);
    $stateid = (int) ri($_REQUEST['stateid']);
    if ($statename == "" || $stateid == "") {
        failure('Please enter Category name');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
//        if($mob->is_state_exists($statename)){
//            echo failure('State already exists'); exit;
//        }
        $mob->update_statedata($statename, $stateid);
        echo success('State Updated sucessfully');
        exit;
    }
} else if ($action == 'delstate') {
    $id = ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_statedata($id);
    echo success('State Deleted sucessfully');
    exit;
} else if ($action == 'asm') {
    $stateid = (int) ri($_REQUEST['state']);
    $asmname = ri($_REQUEST['asmname']);

    if ($asmname == "" || $stateid == "0") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        if ($mob->is_asm_exists($asmname)) {
            echo failure('ASM name already exists');
            exit;
        }
        $mob->add_asmdata($stateid, $asmname);
        echo success('ASM Added sucessfully');
        exit;
    }
} else if ($action == 'editasm') {
    $asmid = (int) ri($_REQUEST['asmid']);
    $stateid = (int) ri($_REQUEST['state']);
    $asmname = ri($_REQUEST['asmname']);

    if ($asmid == "" || $asmname == "" || $stateid == "0") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
//        if($mob->is_asm_exists($asmname)){
//            echo failure('ASM name already exists'); exit;
//        }
        $mob->update_asmdata($asmid, $stateid, $asmname);
        echo success('ASM Updated sucessfully');
        exit;
    }
} else if ($action == 'sales') {
    $asmid = (int) ri($_REQUEST['asmid']);
    $srcode = ri($_REQUEST['srcode']);
    $srname = ri($_REQUEST['srname']);
    $srphoneno = ri($_REQUEST['srphoneno']);
    $dob = ri($_REQUEST['cdob']);
    if ($asmid == "0" || $srcode == "" || $srname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        if ($mob->is_srcode_exists($srcode)) {
            echo failure('Sr Code already exists');
            exit;
        }
        $mob->add_salesdata($asmid, $srcode, $srname, $srphoneno, $dob);
        echo success('Sales record Added sucessfully');
        exit;
    }
} else if ($action == 'editsales') {
    $saleid = (int) ri($_REQUEST['saleid']);
    $asmid = (int) ri($_REQUEST['asmid']);
    $srcode = ri($_REQUEST['srcode']);
    $srname = ri($_REQUEST['srname']);
    $srphoneno = ri($_REQUEST['srphoneno']);

    if ($saleid == "" || $asmid == "0" || $srcode == "" || $srname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
//        if($mob->is_srcode_exists($srcode)){
//            echo failure('Sr Code already exists'); exit;
//        }
        $mob->update_salesdata($saleid, $asmid, $srcode, $srname, $srphoneno);
        echo success('Sales record Updated sucessfully');
        exit;
    }
} else if ($action == 'delsales') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_primaryorder($id);
    echo success('Sales record Deleted sucessfully');
    exit;
} else if ($action == 'dist') {
    $saleid = (int) ri($_REQUEST['saleid']);
    $distcode = ri($_REQUEST['distcode']);
    $distname = ri($_REQUEST['distname']);
    $dob = ri($_REQUEST['cdob']);
    $distphone = ri($_REQUEST['distphone']);
    $emailid = ri($_REQUEST['emailid']);
    if ($saleid == "0" || $distcode == "" || $distname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        if ($mob->is_distcode_exists($distcode)) {
            echo failure('Distributor code already exists');
            exit;
        }
        $mob->add_distdata($saleid, $distcode, $distname, $dob, $distphone, $emailid);
        echo success('Distributor record Added sucessfully');
        exit;
    }
} else if ($action == 'editdist') {
    $distid = (int) ri($_REQUEST['distid']);
    $saleid = (int) ri($_REQUEST['saleid']);
    $distcode = ri($_REQUEST['distcode']);
    $distname = ri($_REQUEST['distname']);
    $distphone = ri($_REQUEST['distphone']);
    $emailid = ri($_REQUEST['emailid']);

    $dob = ri($_REQUEST['cdob']);
    if ($distid == "" || $saleid == "0" || $distcode == "" || $distname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->update_distdata($distid, $saleid, $distcode, $distname, $dob, $distphone, $emailid);
        echo success('Distributor record Updated sucessfully');
        exit;
    }
} else if ($action == 'deldist') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_distdata($id);
    echo success('Distributor record Deleted sucessfully');
    exit;
} else if ($action == 'area') {
    $distid = (int) ri($_REQUEST['distid']);
    $areaname = ri($_REQUEST['areaname']);

    if ($distid == "0" || $areaname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        if ($mob->is_areaname_exists($areaname)) {
            echo failure('Area Name already exists');
            exit;
        }
        $mob->add_areadata($distid, $areaname);
        echo success('Area Name Added sucessfully');
        exit;
    }
} else if ($action == 'editarea') {
    $areaid = (int) ri($_REQUEST['areaid']);
    $distid = (int) ri($_REQUEST['distid']);
    $areaname = ri($_REQUEST['areaname']);

    if ($areaid == "" || $distid == "0" || $areaname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
//        if($mob->is_areaname_exists($areaname)){
//            echo failure('Area Name already exists'); exit;
//        }
        $mob->update_areadata($areaid, $distid, $areaname);
        echo success('Area Name Updated sucessfully');
        exit;
    }
} else if ($action == 'delarea') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_areadata($id);
    echo success('Area Name Deleted sucessfully');
    exit;
} else if ($action == 'shop') {
    $prior_shopid = 0;
    $distid = (int) ri($_REQUEST['distid']);
    $saleid = (int) ri($_REQUEST['saleid']);
    $areaid = (int) ri($_REQUEST['areaid']);
    $shopname = ri($_REQUEST['shopname']);
    $sphoneno = ri($_REQUEST['sphoneno']);
    $sphoneno2 = ri($_REQUEST['sphoneno2']);
    $owner = ri($_REQUEST['owner']);
    $prior_shopid = ri($_REQUEST['shopid']);
    $saddress = ri($_REQUEST['saddress']);
    $semail = ri($_REQUEST['semail']);
    $dob = ri($_REQUEST['cdob']);
    $shoptype = ri($_REQUEST['shoptype']);

    if ($distid == "0" || $saleid == "0" || $areaid == '0' || $shopname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
//        if (!filter_var($semail, FILTER_VALIDATE_EMAIL)) {
//            echo failure("Invalid email Id");
//            exit;
//        }
        $mob = new Sales($customerno, $userid);
        if ($mob->is_shopname_exists($shopname)) {
            echo failure('Shop Name already exists');
            exit;
        }
        $mob->add_shopdata($shoptype, $prior_shopid, $distid, $saleid, $areaid, $shopname, $sphoneno, $sphoneno2, $owner, $saddress, $semail, $dob);
        echo success('Shop Added sucessfully');
        exit;
    }
} else if ($action == 'editshop') {
    $sid = (int) ri($_REQUEST['sid']);
    $distid = (int) ri($_REQUEST['distid']);
    $saleid = (int) ri($_REQUEST['saleid']);
    $areaid = (int) ri($_REQUEST['areaid']);
    $shopname = ri($_REQUEST['shopname']);
    $sphoneno = ri($_REQUEST['sphoneno']);
    $sphoneno2 = ri($_REQUEST['sphoneno2']);
    $owner = ri($_REQUEST['owner']);
    $saddress = ri($_REQUEST['saddress']);
    $semail = ri($_REQUEST['semail']);
    $dob = ri($_REQUEST['dob']);
    $shoptype = ri($_REQUEST['shoptype']);
    if ($distid == "0" || $saleid == "0" || $areaid == '0' || $shopname == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
//        if (!filter_var($semail, FILTER_VALIDATE_EMAIL)) {
//            echo failure("Invalid email Id");
//            exit;
//        }
        $mob = new Sales($customerno, $userid);
        $mob->update_shopdata($shoptype, $sid, $distid, $saleid, $areaid, $shopname, $sphoneno, $sphoneno2, $owner, $saddress, $semail, $dob);
        echo success('Shop Update sucessfully');
        exit;
    }
} else if ($action == 'delshop') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_shopdata($id);
    echo success('Shop Delete sucessfully');
    exit;
}else if($action=='delattendance'){
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_attendancerecord($id);
    echo success('Delete record sucessfully');
    exit;
} else if ($action == 'delshtype') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_shoptypedata($id);
    echo success('Shop Type Delete sucessfully');
    exit;
} else if ($action == 'getdistid') {
    $saleid = (int) ri($_REQUEST['srcd']);
    $mob = new Sales($customerno, $userid);
    $result = $mob->getdistid($saleid);
    echo "<option>Select</option>";
    foreach ($result as $row) {
        echo "<option value='" . $row->userid . "'>" . $row->realname . "</option>";
    }
} else if ($action == 'getaid') {
    $distid = (int) ri($_REQUEST['distid']);
    $mob = new Sales($customerno, $userid);
    $result = $mob->getareaid($distid);
    echo "<option>Select</option>";
    foreach ($result as $row) {
        echo "<option value='" . $row->areaid . "'>" . $row->areaname . "</option>";
    }
} else if ($action == 'getshopid') {
    $areaid = (int) ri($_REQUEST['areaid']);
    $mob = new Sales($customerno, $userid);
    $result = $mob->getshopid($areaid);

    foreach ($result as $row) {
        echo "<option value='" . $row->shopid . "'>" . $row->shopname . "</option>";
    }
} else if ($action == 'getshops') {
    $srcode = (int) ri($_REQUEST['srcode']);
    $mob = new Sales($customerno, $userid);
    $result = $mob->getshoplistbysr($srcode);
    echo "<option>Select</option>";
    foreach ($result as $row) {
        echo "<option value='" . $row->shopid . "'>" . $row->shopname . "</option>";
    }
} else if ($action == 'distidbyshopdata') {
    $distid = (int) ri($_REQUEST['distid']);
    $mob = new Sales($customerno, $userid);
    $result = $mob->getdistbyshopid($distid);
    foreach ($result as $row) {
        echo "<option value='" . $row->shopid . "'>" . $row->shopname . "</option>";
    }
} else if ($action == 'getstyleid') {
    $catid = (int) ri($_REQUEST['catid']);
    $mob = new Sales($customerno, $userid);
    $result = $mob->getskuid($catid);
    echo "<option value='0'>Select Sku</option>";
    foreach ($result as $row) {
        echo "<option value='" . $row->skuid . "'>" . $row->styleno . "</option>";
    }
} else if ($action == 'entry') {
//    $distid = (int) ri($_REQUEST['distid']);
//    $areaid = (int) ri($_REQUEST['areaid']);
    $srcode = (int) ri($_REQUEST['srcode']);
    $shopid = (int) ri($_REQUEST['shopid']);
    $entrydate = ri($_REQUEST['STdate']);
    $entrytime = ri($_REQUEST['STime']);
    $remark = ri($_REQUEST['remark']);

    if ($srcode == "0" || $srcode == "" || $shopid == "0" || $shopid == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->add_entrydata($srcode, $shopid, $entrydate, $entrytime, $remark);
        echo success('Entry Added sucessfully');
        exit;
    }
} else if ($action == 'addattendance') {
    $srcode = (int) ri($_REQUEST['srcode']);
    $entrydate = ri($_REQUEST['STdate']);
    $entrytime = ri($_REQUEST['STime']);

    if ($srcode == "0" || $srcode == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->add_attendancedata($srcode, $entrydate, $entrytime,0);
        echo success('Attendance Added sucessfully');
        exit;
    }
}else if($action=='editattendance'){
    $srcode = (int) ri($_REQUEST['srcode']);
    $entrydate = ri($_REQUEST['STdate']);
    $entrytime = ri($_REQUEST['STime']);
    $status = ri($_REQUEST['status']);

    if ($srcode == "0" || $srcode == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->add_attendancedata($srcode, $entrydate, $entrytime,$status);
        echo success('Attendance Added sucessfully');
        exit;
    }
}else if ($action == 'addorder') {
    $distid = (int) ri($_REQUEST['distid']);
    $areaid = (int) ri($_REQUEST['areaid']);
    $srcode = (int) ri($_REQUEST['srcode']);
    $shopid = (int) ri($_REQUEST['shopid']);
    $orderdate = ri($_REQUEST['STdate']);
    $entrytime = ri($_REQUEST['STime']);
    $category = $_REQUEST['category'];
    $sku = $_REQUEST['sku'];
    $qty = $_REQUEST['qty'];
    $skudata = array(
        'category' => $category,
        'sku' => $sku,
        'qty' => $qty
    );
    if ($distid == "0" || $distid == "" || $areaid == "0" || $areaid == "" || $srcode == "0" || $srcode == "" || $shopid == "0" || $shopid == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->add_orderdata($srcode, $shopid, $orderdate, $entrytime, $skudata, $distid);
        echo success('Order Added sucessfully');
        exit;
    }
} else if ($action == 'editorder') {
    $distid = (int) ri($_REQUEST['distid']);
    $areaid = (int) ri($_REQUEST['areaid']);
    $srcode = (int) ri($_REQUEST['srcode']);
    $shopid = (int) ri($_REQUEST['shopid']);
    $orderdate = ri($_REQUEST['STdate']);
    $entrytime = ri($_REQUEST['STime']);
    $orderid = ri($_REQUEST['orderid']);
    $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : 0;
    $sku = isset($_REQUEST['sku']) ? $_REQUEST['sku'] : 0;
    $qty = isset($_REQUEST['qty']) ? $_REQUEST['qty'] : 0;
    $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : -1;
    $data = array(
        'category' => $category,
        'sku' => $sku,
        'qty' => $qty
    );

    $skudata = array();

    if ($srcode == "0" || $srcode == "" || $shopid == "0" || $shopid == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->update_orderdata($orderid, $distid, $areaid, $shopid, $orderdate, $entrytime, $skudata, $orderStatus);
        echo success('Order Updated sucessfully');
        exit;
    }
} else if ($action == 'adddeadstock') {
    $distid = (int) ri($_REQUEST['distid']);
    $areaid = (int) ri($_REQUEST['areaid']);
    $shopid = (int) ri($_REQUEST['shopid']);
    $srcode = (int) ri($_REQUEST['srcode']);
    $reason = ri($_REQUEST['reason']);
    $category = $_REQUEST['category'];
    $sku = $_REQUEST['sku'];
    $qty = $_REQUEST['qty'];
    $skudata = array(
        'category' => $category,
        'sku' => $sku,
        'qty' => $qty
    );


    if ($distid == "0" || $distid == "" || $areaid == "0" || $areaid == "" || $shopid == "0" || $shopid == "" || $srcode == "0" || $srcode == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->add_deadstockdata($reason, $srcode, $distid, $areaid, $shopid, $skudata);
        echo success('Add Deadstock sucessfully');
        exit;
    }
} else if ($action == 'editdeadstock') {
    $distid = (int) ri($_REQUEST['distid']);
    $areaid = (int) ri($_REQUEST['areaid']);
    $shopid = (int) ri($_REQUEST['shopid']);
    $srcode = (int) ri($_REQUEST['srcode']);
    $reason = ri($_REQUEST['reason']);
    $category = $_REQUEST['category'];
    $sku = $_REQUEST['sku'];
    $qty = $_REQUEST['qty'];
    $stockid = $_REQUEST['stockid'];
    $skudata = array(
        'category' => $category,
        'sku' => $sku,
        'qty' => $qty
    );


    if ($distid == "0" || $distid == "" || $areaid == "0" || $areaid == "" || $shopid == "0" || $shopid == "" || $srcode == "0" || $srcode == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->edit_deadstockdata($reason, $srcode, $distid, $areaid, $shopid, $skudata, $stockid);
        echo success('Update Deadstock sucessfully');
        exit;
    }
} else if ($action == 'addprimary') {
    $distid = (int) ri($_REQUEST['distid']);
    $srcode = (int) ri($_REQUEST['srcode']);
    $orderdate = ri($_REQUEST['STdate']);
    $entrytime = ri($_REQUEST['STime']);
    $category = $_REQUEST['category'];
    $sku = $_REQUEST['sku'];
    $qty = $_REQUEST['qty'];
    $skudata = array(
        'category' => $category,
        'sku' => $sku,
        'qty' => $qty
    );

    if ($distid == "0" || $distid == "" || $srcode == "0" || $srcode == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->add_primarysalesdata($srcode, $distid, $orderdate, $entrytime, $skudata);
        echo success('Add order sucessfully');
        exit;
    }
} else if ($action == 'editprimary') {
    $distid = (int) ri($_REQUEST['distid']);
    $srcode = (int) ri($_REQUEST['srcode']);
    $orderdate = ri($_REQUEST['STdate']);
    $entrytime = ri($_REQUEST['STime']);
    $category = $_REQUEST['category'];
    $sku = $_REQUEST['sku'];
    $qty = $_REQUEST['qty'];
    $prid = $_REQUEST['prid'];
    $skudata = array(
        'category' => $category,
        'sku' => $sku,
        'qty' => $qty
    );

    if ($distid == "0" || $distid == "" || $srcode == "0" || $srcode == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->edit_primarysalesdata($prid, $srcode, $distid, $orderdate, $entrytime, $skudata);
        echo success('Update order sucessfully');
        exit;
    }
} else if ($action == 'delstock') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_stockdata($id);
    echo success('Stock Delete sucessfully');
    exit;
} else if ($action == 'delasm') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->delete_asmdata($id);
    echo success('ASM Delete sucessfully');
    exit;
} else if ($action == "approve") {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->approve_stockdata($id);
    echo success('Stock Delete sucessfully');
    exit;
} else if ($action == "reject") {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Sales($customerno, $userid);
    $mob->reject_stockdata($id);
    echo success('Stock Delete sucessfully');
    exit;
} else if ($action == 'addinventory'){
    $sku = '';
    $qty = '';
    $category = '';

    $distid = (int) ri($_REQUEST['distid']);

    $category = isset($_REQUEST['category']) ? $_POST['category'] : '';
    $sku = isset($_REQUEST['sku']) ? $_POST['sku'] : '';
    $qty = isset($_REQUEST['qty']) ? $_POST['qty'] : '';

    $skudata = array(
        'category' => $category,
        'sku' => $sku,
        'qty' => $qty
    );



    if (empty($category) || empty($sku) || empty($qty)) {
        echo failure('Please fill the mandatory fields.');
        exit;
    }

    if ($distid == "0" || $distid == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $mob->add_inventorydata($distid, $skudata);
        echo success('Add inventory sucessfully');
        exit;
    }
} else if ($action == 'editinvqty') {
    $invid = $_REQUEST['invid'];
    $qty = $_REQUEST['qty'];

    if ($qty == "0" || $qty == "") {
        echo failure('Please fill the mandatory fields.');
        exit;
    } else {
        $mob = new Sales($customerno, $userid);
        $result = $mob->edit_inventorydata($invid, $qty);
        echo success('Updated Sucessfully');
        exit;
    }
} else if ($action == 'geteditinventory') {
    $invid = (int) ri($_REQUEST['invid']);
    $mob = new Sales($customerno, $userid);
    $result = $mob->geteditinventorydata($invid);
    echo json_encode($result);
    exit;
} else if ($action == 'getinventorydata') {
    $distid = (int) ri($_REQUEST['distid']);
    $skuid = (int) ri($_REQUEST['skuid']);
    $mob = new Sales($customerno, $userid);
    $result = $mob->getinventorydataqty($distid, $skuid);
    echo json_encode($result);
    exit;
} else {
    echo failure('No action found');
    exit;
}
?>
