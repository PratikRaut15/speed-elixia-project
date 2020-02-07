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
          <?php if ($_SESSION["role_modal"] == 'elixir') {
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
          <?php }
            //require_once "../tms/tms_function.php";
            ?>
            <ul role="navigation" class="nav">
                <li class="dropdown">
                  <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Masters <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('secondarytms/sectms.php?pg=view-vehtype', 'Vehicle Type', 'Vehicle Type');
                    current_page_new('secondarytms/sectms.php?pg=view-vehicle', 'Vehicle', 'Vehicle');
                    current_page_new('secondarytms/sectms.php?pg=view-route', 'Route', 'Route');
                    current_page_new('secondarytms/sectms.php?pg=view-routematrix', 'Route Matrix', 'Route Matrix');
                    current_page_new('secondarytms/sectms.php?pg=view-products', 'Products', 'Products');
                    current_page_new('secondarytms/sectms.php?pg=view-customer', 'Customer', 'Customer');
                    ?>
                  </ul>
                </li>
            <li class="dropdown">
                  <a data-intro="Manage your Indents" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Planning <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('secondarytms/sectms.php?pg=view-shipment', 'Shipment', 'Shipment');
                    current_page_new('secondarytms/sectms.php?pg=view-occupancy', 'Occupancy', 'Occupancy');
                    current_page_new('secondarytms/sectms.php?pg=view-trips', 'Trips', 'Trips');
                    current_page_new('secondarytms/sectms.php?pg=view-fuel', 'Fuel Request', 'Fuel Request');
                    ?>
                  </ul>
                </li>
                <li class="dropdown">
                  <a data-intro="Manage your Indents" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Accounts <b class="caret"></b></a>
                  <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                    <?php
                    current_page_new('secondarytms/sectms.php?pg=view-tripclose', 'Trip Closure', 'Trip Closure');
                    current_page_new('secondarytms/sectms.php?pg=view-billing', 'Billing', 'Billing');
                    current_page_new('secondarytms/sectms.php?pg=view-fuelrequest', 'Fuel Request Approval', 'Fuel Request Approval');
                    ?>
                  </ul>
                </li>

              <!-- End Of Menu -->
            </ul>
  <?php  require_once 'right_panel.php'; ?>
        </div>
      </div>
    </div> <!-- /navbar-example -->
  </div>
  <!-- end #menu -->
  <?php
}
?>
