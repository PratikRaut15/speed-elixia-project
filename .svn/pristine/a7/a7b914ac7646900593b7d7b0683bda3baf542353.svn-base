<?php
$new = '<img src=' . $_SESSION['subdir'] . '/images/new_small.gif>';
if (isset($_SESSION['username'])) {
 $customerno = $_SESSION['customerno'];
 $company = $_SESSION['customercompany'];
 $foldername = 'pickup';
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
         <?php } ?>
         <ul role="navigation" class="nav">
           <?php
           if ($_SESSION["use_pickup"]) {
            //current_page_new('routing/pick.php?id=2','View Vehicle Directions','Direction Map');
            if ($_SESSION['customerno'] == 151) {
             //current_page_new('routing/pick.php?id=11','View Users in heat map','Heat Map');
            }
            //current_page_new('routing/pick.php?id=5','Run Route Algo','Order Mapping');
            //current_page_new('routing/pick.php','Map Vehicle to Zone/Slot','Vehicle Mapping');
            ?>
            <li class="dropdown">
              <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Masters <b class="caret"></b></a>
              <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                <?php
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/" . $foldername . "/customer.php?id=2' tabindex='-1' role='menuitem'>Customers</a></li>";
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/" . $foldername . "/vendor.php?id=2' tabindex='-1' role='menuitem'>Vendor</a></li>";
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/" . $foldername . "/pickup.php?id=2' tabindex='-1' role='menuitem'>Field Marshal</a></li>";
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/" . $foldername . "/shiper.php?id=2' tabindex='-1' role='menuitem'>Shippers</a></li>";
                current_page_new($foldername . '/pick.php?id=6', 'All Zones', 'Zone');
                // current_page_new('pickup/pick.php?id=7','All Area','Area');
                current_page_new($foldername . '/pick.php?id=17', 'All Slots', 'Slot');
                ?>
              </ul>
            </li>
            <!--
            <li class="dropdown">
              <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Import <b class="caret"></b></a>
              <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
            -->
            <?php
            //echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/" . $foldername . "/import.php' tabindex='-1' role='menuitem'>Import Orders</a></li>";
            //echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/" . $foldername . "/import_vendor.php' tabindex='-1' role='menuitem'>Import Vendors</a></li>";
            ?>
            <!--
              </ul>
            </li>
            -->
            <?php
            /* if($_SESSION['customerno']==151){
              current_page_new('routing/pick.php?id=11','View Users in heat map','Heat Map');
              } */
            //current_page_new($foldername.'/pick.php?id=5', 'Run Route Algo', 'Order Mapping');
            //current_page_new($foldername.'/pick.php?id=2', 'View Vehicle Directions', 'Direction Map');
            //current_page_new('pickup/pick.php','Map Vehicle to Zone/Slot','Pickupboy Mapping');
            ?>
            <li class="dropdown">

              <!--
           <a data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" href="javascript:void(0);">Orders <b class="caret"></b></a>
           <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
              -->
              <?php
              current_page_new($foldername . '/pick.php?id=3', 'All Orders', 'Pickups');
              current_page_new($foldername . '/pick.php?id=21', 'Hyperlocal', 'Hyperlocal');
              //current_page_new('pickup/pick.php?id=3','View Orders','View');
              //current_page_new('pickup/pick.php?id=8','Add Orders','Add');
              //current_page_new($foldername . '/pick.php?id=12', 'View order-mapping sequence', 'Sequence');
              ?>
          </ul>
          </li>
          <?php
          /*
            echo "<li role='presentation'><a href='".$_SESSION['subdir']."/modules/pickup/orders.php' tabindex='-1' role='menuitem'>Orders</a></li>";
           *
            echo "<li role='presentation'><a href='".$_SESSION['subdir']."/modules/pickup/picklist.php?id=2' tabindex='-1' role='menuitem'>Dashboard</a></li>";
           * */
          ?>
          <?php
         }
         ?>
         <!-- End Of Menu -->
         </ul>
         <?php require_once 'right_panel.php'; ?>
       </div>
     </div>
   </div> <!-- /navbar-example -->
 </div>
 <!-- end #menu -->
 <?php
}
?>