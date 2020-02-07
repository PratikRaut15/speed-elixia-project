<?php
//include_once '../../config.inc.php';
include_once 'tms_function.php';

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
$sql_details = array(
    'user' => "root",
    'pass' => '',
    'db' => 'TMS',
    'host' => 'localhost'
);
$customerno = exit_issetor($_SESSION['customerno']);
$of = retval_issetor($_GET['of']);
/**
  $of = retval_issetor($_GET['of']);
  $join = '';
  $groupby = '';
  $customWhere = '';

  if($of=='depotMaster'){
  $table = 'depot as a';
  $primaryKey = 'depotid';
  $columns = array(
  array('db' => 'a.depotid', 'dt'=> 0),
  array('db' => 'a.depotcode','dt'=> 1),
  array('db' => 'a.depotname','dt'=> 2),
  array('db' => "a.zoneid",'dt'=> 3,),
  array('db' => 'a.locationid','dt'=> 4,
  'formatter' => function($a){
  $view = $a;
  return $view;
  }),
  array('db' => 'a.customerno', 'dt'=> 5,
  'formatter' => function($d){
  $view = "<a href='salesengage.php?pg=edit-client&id=$d'>Edit</a> <a href='javascript:void(0);' onclick='deleteclient($d);'>Delete</a>";
  return $view;
  }),
  );
  }


  $customWhere .= "a.customerno=$customerno and a.isdeleted=0 ";


  require( '../routing/ssp.class.php' );

  echo json_encode(
  SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join, $groupby)
  );

  /
 * 
 * 
 */

    $objDepot = new Depot();
    $objDepot->customerno = $_SESSION['customerno'];
    $objDepot->locationid = '';
    $objDepot->zoneid = '';
    $depots = get_depots($objDepot);
    $result = array("data" => $depots);

    //echo json_encode($depots); //die();

?>
<script type='text/javascript'>
    var oTable1 = $('#example1').dataTable({
        "aaData": <?php echo json_encode($depots);?>,
        "aoColumns": [{"mData": "depotid"}]
    });
</script>