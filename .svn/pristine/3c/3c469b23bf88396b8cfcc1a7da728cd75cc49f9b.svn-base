<?php
$pg = isset($_GET['pg']) ? $_GET['pg'] : '';
$statusid = isset($_GET['statusid']) ? $_GET['statusid'] : '';
$consid = isset($_GET['consid']) ? $_GET['consid'] : '';  //consinee id
$consrid = isset($_GET['consrid']) ? $_GET['consrid'] : '';  //consineer id
$tid = isset($_GET['tid']) ? $_GET['tid'] : '';  //trip id 
if ($pg == 'tripview' || $pg == 'tripreport' || $pg == 'tripviewdata') {
  $disp = "none";
} else {
  $disp = "block";
}
?>

<ul id="tabnav" style='display:<?php echo $disp; ?>;'>
  <?php
  if (($pg == "statusview") || ($pg == "tripstatus") || ($pg == "tripstatusedit")) {
    ?>
    <li><a class='<?php if ($pg == 'tripstatus') {
    echo "selected";
  } ?>' href='trips.php?pg=tripstatus'>Add Trip Status</a></li>
    <li><a class='<?php if ($pg == 'statusview') {
    echo "selected";
  } ?>' href='trips.php?pg=statusview'>View Trip Status </a></li>
      <?php if ($statusid != "") {
        ?> <li><a class='<?php if ($pg == 'tripstatusedit') {
          echo "selected";
        } ?>' href='trips.php?pg=tripstatusedit&statusid=<?php echo $statusid; ?>'>Edit Trip Status</a></li>
      <?php
      }
    } else if (($pg == "consigneview") || ($pg == "addconsignee") || ($pg == "editconsignee")) {
      ?>
    <li><a class='<?php if ($pg == 'addconsignee') {
      echo "selected";
    } ?>' href='trips.php?pg=addconsignee'>Add Consignee </a></li>
    <li><a class='<?php if ($pg == 'consigneview') {
    echo "selected";
  } ?>' href='trips.php?pg=consigneview'>View Consignee</a></li>
  <?php if ($consid != "") {
    ?> <li><a class='<?php if ($pg == 'editconsignee') {
      echo "selected";
    } ?>' href='trips.php?pg=editconsignee&consid=<?php echo $consid; ?>'>Edit Consignee</a></li>
  <?php
  }
} else if (($pg == "consignerview") || ($pg == "addconsigneer") || ($pg == "editconsigneer")) {
  ?>
    <li><a class='<?php if ($pg == 'addconsigneer') {
    echo "selected";
  } ?>' href='trips.php?pg=addconsigneer'>Add Consignor </a></li>
    <li><a class='<?php if ($pg == 'consignerview') {
    echo "selected";
  } ?>' href='trips.php?pg=consignerview'>View Consignor</a></li>
  <?php if ($consid != "") {
    ?> <li><a class='<?php if ($pg == 'editconsigneer') {
      echo "selected";
    } ?>' href='trips.php?pg=editconsigneer&consrid=<?php echo $consrid; ?>'>Edit Consignor</a></li>
  <?php }
}
?>
</ul>


