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
                 if ($_SESSION["use_sales"]) {
                  require_once "../salesengage/salesengage_function.php";
                  ?>
                  <ul role="navigation" class="nav">
                      <?php
                      //current_page_new('salesengage/salesengage.php','Dashboard','Front');
                      current_page_new('salesengage/salesengage.php?pg=view-order', 'View Orders', 'Orders');
//                      current_page_new('salesengage/salesengage.php?pg=view-activities','View Activities','Activities');
                      ?>
                      <?php if ($_SESSION["Session_UserRole"] != "sales_manager") { ?>
                       <li class="dropdown">
                           <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Masters <b class="caret"></b></a>
                           <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                               <?php
                               current_page_new('salesengage/salesengage.php?pg=view-client', 'Client', 'Client');
                               current_page_new('salesengage/salesengage.php?pg=view-product', 'Product', 'Product');
                               current_page_new('salesengage/salesengage.php?pg=view-stage', 'Stage ', 'Stages');
                               current_page_new('salesengage/salesengage.php?pg=view-lost', 'Lost Reasons ', 'Lost Reasons');
                               current_page_new('salesengage/salesengage.php?pg=view-reminder', 'Reminder', 'Reminder');
                               current_page_new('salesengage/salesengage.php?pg=view-sourceorder', 'Source Of Order', 'Source Of Order');
                               current_page_new('salesengage/salesengage.php?pg=view-template', 'Templates Master', 'Templates');
                               echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/account/users.php?id=2' tabindex='-1' role='menuitem'>Users</a></li>";
                               ?>
                           </ul>
                       </li>
                      <?php } ?>
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