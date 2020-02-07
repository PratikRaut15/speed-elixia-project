<?php
/**
 * Start page of Sales-module
 */
date_default_timezone_set("Asia/Calcutta");
require_once '../panels/header.php';

if(!isset($_SESSION['userid'])){
    header("Location: ".$_SESSION['subdir']);
}
if($_SESSION['use_secondary_sales']==0){
    exit('No access to this module');
}

echo "<div id='pageloaddiv' style='display:none;'></div>";

$orderid="";
if(isset($_GET['orderid'])){
    $orderid=$_GET['orderid'];
}

$stypeid="";
if(isset($_GET['stypeid'])){
    $stypeid=$_GET['stypeid'];
}
$catid="";
if(isset($_GET['catid'])){
    $catid=$_GET['catid'];
}

$stateid="";
if(isset($_GET['stateid'])){
    $stateid=$_GET['stateid'];
}

$styleid="";
if(isset($_GET['styleid'])){
    $styleid=$_GET['styleid'];
}
$areaid="";
if(isset($_GET['areaid'])){
    $areaid=$_GET['areaid'];
}

$distid="";
if(isset($_GET['distid'])){
    $distid=$_GET['distid'];
}

$saleid="";
if(isset($_GET['saleid'])){
    $saleid=$_GET['saleid'];
}

$sid="";
if(isset($_GET['sid'])){
    $sid=$_GET['sid'];
}

$stockid="";
if(isset($_GET['stockid'])){
    $stockid=$_GET['stockid'];
}

$prid = "";
if(isset($_GET['prid'])){
    $prid = $_GET['prid'];
}
$invid='';
if(isset($_GET['invid'])){
    $invid = $_GET['invid'];
}

$atid='';
if(isset($_GET['atid'])){
    $atid = $_GET['atid'];
}
$pg = isset($_GET['pg']) ? $_GET['pg'] : 'def';

require_once 'pages/sales_menu.php';

if($pg=='def'){

}
elseif($pg=='category'){
    require_once 'sales_function.php';
    require_once 'pages/category.php';
}elseif($pg=='catview'){
    require_once 'sales_function.php';
    require_once 'pages/catview.php';
}elseif($pg=='catedit' && $catid!=""){
    require_once 'sales_function.php';
    require_once 'pages/catedit.php';
}elseif($pg=='state'){
    require_once 'sales_function.php';
    require_once 'pages/state_master.php';
}elseif($pg=='stateview'){
    require_once 'sales_function.php';
    require_once 'pages/stateview.php';
}elseif($pg=='stateedit'&& $stateid!=""){
    require_once 'sales_function.php';
    require_once 'pages/state_edit.php';
}elseif($pg=='style'){
    require_once 'sales_function.php';
    require_once 'pages/style.php';
}elseif($pg=='styleview'){
    require_once 'sales_function.php';
    require_once 'pages/style_view.php';
}elseif($pg=='styleedit'&& $styleid!=""){
    require_once 'sales_function.php';
    require_once 'pages/style_edit.php';
}elseif($pg=='area'){
    require_once 'sales_function.php';
    require_once 'pages/area.php';
}elseif($pg=='areaview'){
    require_once 'sales_function.php';
    require_once 'pages/area_view.php';
}elseif($pg=='areaedit'&& $areaid!=""){
    require_once 'sales_function.php';
    require_once 'pages/area_edit.php';
}elseif($pg=='dist'){
    require_once 'sales_function.php';
    require_once 'pages/distributor.php';
}elseif($pg=='distview'){
    require_once 'sales_function.php';
    require_once 'pages/dist_view.php';
}elseif($pg=='distedit'&& $distid!=""){
    require_once 'sales_function.php';
    require_once 'pages/dist_edit.php';
}elseif($pg=='sales'){
    require_once 'sales_function.php';
    require_once 'pages/sales.php';
}elseif($pg=='shop'){
    require_once 'sales_function.php';
    require_once 'pages/shop.php';
}elseif($pg=='shopview'){
    require_once 'sales_function.php';
    require_once 'pages/shop_view.php';
}elseif($pg=='shopedit'&& $sid!=""){
    require_once 'sales_function.php';
    require_once 'pages/shop_edit.php';
}elseif($pg=='entry'){
    require_once 'sales_function.php';
    require_once 'pages/entry.php';
}elseif($pg=="entryview"){
    require_once 'sales_function.php';
    require_once 'pages/entry_view.php';
}elseif($pg=="inventoryadd"){
    require_once 'sales_function.php';
    require_once 'pages/inventory_add.php';
}elseif($pg=="inventoryview"){
    require_once 'sales_function.php';
    require_once 'pages/inventory_view.php';
}elseif($pg=='order'){
    require_once 'sales_function.php';
    require_once 'pages/order.php';
}elseif($pg=='orderedit'){
    require_once 'sales_function.php';
    require_once 'pages/orderedit.php';
}elseif($pg=="orderview"){
    require_once 'sales_function.php';
    require_once 'pages/order_view.php';
}elseif($pg=="allorder"){
    require_once 'sales_function.php';
    require_once 'pages/order_view.php';
}elseif($pg=='stypeadd'){
    require_once 'sales_function.php';
    require_once 'pages/shoptypeadd.php';
}elseif($pg=='stypeedit'){
    require_once 'sales_function.php';
    require_once 'pages/stypeedit.php';
}elseif($pg=="stypeview"){
    require_once 'sales_function.php';
    require_once 'pages/stypeview.php';
}elseif($pg=='stock'){
    require_once 'sales_function.php';
    require_once 'pages/stock.php';
}elseif($pg=='stockedit'){
    require_once 'sales_function.php';
    require_once 'pages/editstock.php';
}elseif($pg=='stockview'){
    require_once 'sales_function.php';
    require_once 'pages/stock_view.php';
}elseif($pg=='prisales'){
    require_once 'sales_function.php';
    require_once 'pages/primarysales.php';
}elseif($pg=='editprisales'){
    require_once 'sales_function.php';
    require_once 'pages/edit_primarysales.php';
}elseif($pg=='prisalesview'){
    require_once 'sales_function.php';
    require_once 'pages/primarysales_view.php';
}elseif($pg=='allprimarysales'){
    require_once 'sales_function.php';
    require_once 'pages/primarysales_view.php';
}elseif($pg=="dashboard"){
    require_once 'sales_function.php';
    require_once 'pages/dashboard.php';
}elseif($pg=="attendanceview"){
    require_once 'sales_function.php';
    require_once 'pages/attendanceview.php';
}elseif($pg=="attendanceedit" && $atid!=""){
    require_once 'sales_function.php';
    require_once 'pages/attendanceedit.php';
}elseif($pg=="attendanceadd"){
    require_once 'sales_function.php';
    require_once 'pages/attendanceadd.php';
}elseif($pg=="orderonhold"){
    require_once 'sales_function.php';
    require_once 'pages/order_onhold.php';
}else{
    header("Location: "."sales.php?pg=catview");
}

require_once '../panels/footer.php';
?>
