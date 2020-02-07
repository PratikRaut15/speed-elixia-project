<?php
$new = '<img src=' . $_SESSION['subdir'] . '/images/new_small.gif>';
if (isset($_SESSION['username'])) {
  $customerno = $_SESSION['customerno'];
  $company = $_SESSION['customercompany'];
  include_once 'generatemenu.php';
  include_once '../../lib/bo/UserManager.php';
  $umgr = new UserManager();
  $accuser = $umgr->getUserForAccountSwitch($_SESSION['userid']);
  ?>
  <div class="bs-docs-example" style="height:41px;">
    <div class="navbar navbar-static" id="navbar-example">
      <div class="navbar-inner">
        <div style="width: auto;" class="container">
          <?php if ($_SESSION["customerno"] == '116') { ?>
            <a href="" class="brand"><img src="../../images/116/image001.png"></img></a>
          <?php } else if ($_SESSION["customerno"] == '9') { ?>
            <a href="" class="brand"><img src="../../images/9/ttllogo.png"></img></a>
            <?php
          } else if ($_SESSION["role_modal"] == 'elixir') {
            include_once '../../lib/bo/CustomerManager.php';
            $cm = new CustomerManager();
            $cms = $cm->getcustomerdetail();
            ?>
            <a data-position="bottom" data-toggle="dropdown" class="brand dropdown dropdown-toggle" role="button" id="drop2" style="cursor: pointer;">
              <span style="color: black; font-weight: bold; font-size: initial;">elixia</span>
              <span style="color: #0193CC; font-weight: bolder; font-size: initial;">speed</span>
              <span style="color: black; font-weight: bold; font-size:small;">for</span>
              <span style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><?php echo $company; ?> <b class="caret"></b></span>
            </a>
            <ul aria-labelledby="drop2" role="menu" class="dropdown-menu" style="margin-top: -9px; left: 10%; overflow-y: auto; height: 500px; ">
              <?php
              foreach ($cms as $customer) {
                $users = $umgr->getAllElixir($customer->customerno);
                ?>
                <li  role="presentation" onclick="changeaccount(<?php echo $users->userid; ?>)" style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><a><?php echo $customer->customerno; ?> - <?php echo $customer->customercompany ?></a></li>
                <?php
              }
              ?>
            </ul>
          <?php } else if (!empty($accuser)) {
            ?>
            <a data-position="bottom" data-toggle="dropdown" class="brand dropdown dropdown-toggle" role="button" id="drop2" style="cursor: pointer;">
              <span style="color: black; font-weight: bold; font-size: initial;">elixia</span>
              <span style="color: #0193CC; font-weight: bolder; font-size: initial;">speed</span>
              <span style="color: black; font-weight: bold; font-size:small;">for</span>
              <span style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><?php echo $company; ?> <b class="caret"></b></span>
            </a>
            <ul aria-labelledby="drop2" role="menu" class="dropdown-menu" style="margin-top: -9px; left: 10%;">
              <?php
              foreach ($accuser as $user) {
                ?>
                <li  role="presentation" onclick="changeaccount(<?php echo $user->childid; ?>)" style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><a><?php echo $user->customercompany ?></a></li>
                <?php
              }
              ?>
            </ul>
          <?php } else { ?>
            <a href="javascript:void(0);" class="brand">
              <span style="color: black; font-weight: bold; font-size: initial;">elixia</span>
              <span style="color: #0193CC; font-weight: bolder; font-size: initial;">speed</span>
              <span style="color: black; font-weight: bold; font-size:small;">
                <?php if ($_SESSION["customerno"] != '9') { ?>
                  for </span>
                <span style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;">
                  <?php
                  echo $company;
                }
                ?>
              </span>
            </a>
            <?php
          }
          if ($_SESSION["use_tms"]) {
            require_once "../tms/tms_function.php";
            ?>
            <ul role="navigation" class="nav">
              <?php
              if ($_SESSION['roleid'] == '5' || $_SESSION['roleid'] == '6') {
                ?>
                <li class="dropdown">
                  <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Masters <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('tms/tms.php?pg=view-zone', 'Zone Master', 'Zone');
                    current_page_new('tms/tms.php?pg=view-location', 'Location Master', 'Location');
                    current_page_new('tms/tms.php?pg=view-depot', 'Depot Master', 'Depot');
                    current_page_new('tms/tms.php?pg=view-plant', 'Factory / Plant Master', 'Factory / Plant');
                    current_page_new('tms/tms.php?pg=view-vehicle-type', 'Vehicle Type Master', 'Vehicle Type');
                    current_page_new('tms/tms.php?pg=view-transporter', 'Transporter Master', 'Transporter');
                    current_page_new('tms/tms.php?pg=view-share', 'Share Master', 'Transporter Share');
                    current_page_new('tms/tms.php?pg=view-route', 'Route Master', 'Route Master');
                    current_page_new('tms/tms.php?pg=view-routemanager', 'RouteManager Master', 'Route Checkpoints');
                    current_page_new('tms/tms.php?pg=view-sku', 'SKU Master', 'SKU');
                    current_page_new('tms/tms.php?pg=view-users', 'Users', 'Users');
                    current_page_new('tms/tms.php?pg=import-data', 'Import Data', 'Import Data');
                    ?>
                  </ul>
                </li>
                <li class="dropdown">
                  <a data-intro="Manage your Indents" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Factory <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('tms/tms.php?pg=view-factory-delivery', 'Factory Delivery Master', 'Factory Delivery');
                    ?>
                  </ul>
                </li>
                <li class="dropdown">
                  <a data-intro="Manage your Indents" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Indents <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('tms/tms.php?pg=view-indents', 'Master Master', 'Auto Load Planner');
                    current_page_new('tms/tms.php?pg=view-proposed-transporter', 'Poposed Transporter Indent Master', 'Proposed Transporter Indent');
                    current_page_new('tms/tms.php?pg=left-over-sku', 'Left Over Sku', 'Left Over Skus');
                    ?>
                  </ul>
                </li>
                <?php
                current_page_new('tms/tms.php?pg=view-proposed-indent', 'Placement Tracker', 'Placement Tracker');
                current_page_new('tms/tms.php?pg=view-indent', 'Actual Vehicle Placed', 'Actual Vehicle Placed');
                current_page_new('tms/tms.php?pg=view-summary', 'Placement Summary', 'Placement Summary');
                //current_page_new('tms/tms.php?pg=view-bills', 'bills', 'Bills');
                ?>
                <li class="dropdown">
                  <a data-intro="Manage your Indents" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Vendor Payable <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('tms/tms.php?pg=view-bills', 'Drafts', 'Drafts');
                    current_page_new('tms/tms.php?pg=view-billtracker', 'Bill Tracker', 'Bill Tracker');
                    ?>
                  </ul>
                </li>

                <?php
              } else if ($_SESSION['roleid'] == '15') {
                current_page_new('tms/tms.php?pg=view-proposed-transporter', 'Poposed Indent', 'Proposed Indent');
                current_page_new('tms/tms.php?pg=view-proposed-indent', 'Placement Tracker', 'Placement Tracker');
                current_page_new('tms/tms.php?pg=view-summary', 'Placement Summary', 'Placement Summary');
              } else if ($_SESSION['roleid'] == '16') {
                current_page_new('tms/tms.php?pg=view-factory-delivery', 'Factory Delivery Master', 'Factory Delivery');
                ?>
                <li class="dropdown">
                  <a data-intro="Manage your Indents" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Indents <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('tms/tms.php?pg=view-indents', 'Master Master', 'Auto Load Planner');
                    current_page_new('tms/tms.php?pg=left-over-sku', 'Left Over Sku', 'Left Over Skus');
                    ?>
                  </ul>
                </li>
                <?php
                current_page_new('tms/tms.php?pg=view-proposed-indent', 'Placement Tracker', 'Placement Tracker');
                current_page_new('tms/tms.php?pg=view-indent', 'Actual Vehicle Placed', 'Actual Vehicle Placed');
                current_page_new('tms/tms.php?pg=view-summary', 'Placement Summary', 'Placement Summary');
                ?>
                <li class="dropdown">
                  <a data-intro="Manage your Indents" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Vendor Payable <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('tms/tms.php?pg=view-bills', 'Drafts', 'Drafts');
                    current_page_new('tms/tms.php?pg=view-billtracker', 'Bill Tracker', 'Bill Tracker');
                    ?>
                  </ul>
                </li>
              <?php
              } else if ($_SESSION['roleid'] == '17') {
                current_page_new('tms/tms.php?pg=view-factory-delivery', 'Factory Delivery Master', 'Factory Delivery');
                current_page_new('tms/tms.php?pg=view-indent', 'Generate Indent', 'Indents');
                current_page_new('tms/tms.php?pg=view-summary', 'Placement Summary', 'Placement Summary');
              }
              ?>
              <!-- End Of Menu -->
            </ul>
  <?php } require_once 'right_panel.php'; ?>
        </div>
      </div>
    </div> <!-- /navbar-example -->
  </div>
  <!-- end #menu -->
  <?php
}
?>