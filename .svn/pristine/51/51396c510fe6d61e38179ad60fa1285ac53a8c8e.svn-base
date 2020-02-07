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
                 <?php } ?>
                 <ul role="navigation" class="nav">
                     <?php
                     if ($_SESSION["use_secondary_sales"]) {
                      ?>
                      <li class="dropdown">
                          <?php if($_SESSION['role_modal'] != "Distributor"){?>
                          <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" href="javascript:void(0);">Masters <b class="caret"></b></a>
                          <?php }?>
                          <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                              <?php
                              if ($_SESSION['role_modal'] != "sales_representative" && $_SESSION['role_modal'] != "Supervisor" && $_SESSION['role_modal'] != "ASM"&& $_SESSION['role_modal'] != "Distributor") {
                               echo "<li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'>Styles</a>
                                        <ul class='dropdown-menu'>
                                        <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/sales/sales.php?pg=catview' tabindex='-1' role='menuitem'>Category</a></li>
                                        <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/sales/sales.php?pg=styleview' tabindex='-1' role='menuitem'>SKU</a></li>";
                               echo"</ul></li>";
                               ?>
                               <?php
                               echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/sales/sales.php?pg=stateview' tabindex='-1' role='menuitem'>State</a></li>";
                              }
                              ?>
                              <?php echo "<li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'>Shops</a>
                                        <ul class='dropdown-menu'>"; ?>
                              <!--                                        <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/sales/sales.php?pg=saleview' tabindex='-1' role='menuitem'>Sales</a></li>-->
                              <?php
                              //echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/sales/sales.php?pg=distview' tabindex='-1' role='menuitem'>Distributor</a></li>
                                       echo " <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/sales/sales.php?pg=areaview' tabindex='-1' role='menuitem'>Area</a></li>
                                        <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/sales/sales.php?pg=shopview' tabindex='-1' role='menuitem'>Shop</a></li>
                                        <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/sales/sales.php?pg=stypeview' tabindex='-1' role='menuitem'>Shop Type</a></li>
                                        </ul>
                                    </li>";
                              if ($_SESSION['role_modal'] == "sales_representative" || $_SESSION['role_modal'] == "ASM"|| $_SESSION['role_modal'] == "Supervisor") {
                               //Do  nothing
                              } else {
                               echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/account/users.php?id=2' tabindex='-1' role='menuitem'>Users</a></li>";
                              }
                              ?>
                          </ul>
                      </li>
                      <?php if ($_SESSION['role_modal'] == 'Administrator' || $_SESSION['role_modal'] == 'elixir') { ?>
                       <li class="dropdown">
                           <?php current_page_new('sales/sales.php?pg=dashboard', 'Dashboard', 'Dashboard'); ?>
                       </li>
                       <li class="dropdown">
                           <?php current_page_new('sales/sales.php?pg=attendanceview', 'Attendance', 'Attendance'); ?>
                       </li>
                      <?php }  ?>
                      <?php if ($_SESSION['role_modal'] != 'Distributor') { ?>
                       <li class="dropdown">
                          <?php current_page_new('sales/sales.php?pg=prisalesview', 'View Primary Sales', 'Primary Sales'); ?>
                      </li>
                      <?php  } ?>
                      <?php if ($_SESSION['role_modal'] == 'ASM') { ?>
                       <li class="dropdown">
                           <?php current_page_new('sales/sales.php?pg=allorder', 'View Secondary Sales', 'Secondary Sales'); ?>
                       </li>
                      <?php } else { 
                         if ($_SESSION['role_modal'] != 'Distributor') {   
                          ?>
                       <?php current_page_new('sales/sales.php?pg=orderview', 'View Secondary Sales', 'Secondary Sales'); ?>
                         <?php }
                         } ?>
                      <?php
                      // }
//                            if($_SESSION['role_modal']!='sales_representative'){
//
                      ?>
                      <!--                            <li class="dropdown">
                      <?php //current_page_new('sales/sales.php?pg=allorder', 'View Secondary Sales', 'Secondary Sales');   ?>
                                                  </li>-->
                      <?php //}   ?>
                      <?php    if ($_SESSION['role_modal'] != 'Distributor') {   ?>
                      <li class="dropdown">
                          <?php current_page_new('sales/sales.php?pg=stockview', 'Dead Stock', 'Dead Stock'); ?>
                      </li>
                      <?php } ?>
                      <?php    if ($_SESSION['role_modal'] != 'Distributor') {   ?>
                      <li class="dropdown">
                          <?php current_page_new('sales/sales.php?pg=entryview', 'Entry', 'Entry'); ?>
                      </li>
                      <?php } ?>
                      <?php    if ($_SESSION['role_modal'] == 'Distributor') {   ?>
                      <li class="dropdown">
                          <?php current_page_new('sales/sales.php?pg=inventoryview', 'Inventory', 'Inventory'); ?>
                      </li>
                      <?php } ?>
                      
                      <!--                            <li class="dropdown">
                                                      <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" href="javascript:void(0);">Transaction <b class="caret"></b></a>
                                                      <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                      <?php
                      //current_page_new('sales/sales.php?pg=entry', 'Entries', 'Entries');
                      //current_page_new('sales/sales.php?pg=order', 'Orders', 'Orders');
                      ?>
                                                      </ul>
                                                  </li>-->
                                              <li class="dropdown">
                                                <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" href="javascript:void(0);">Reports <b class="caret"></b></a>
                                                <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                      <?php
                        current_page_new('reports/reports.php?id=55', 'First Call & Last Call Report', 'First / Last Call Report');
                        current_page_new('reports/reports.php?id=56', 'SKU Wise Orders Report', 'SKU Wise Orders Report');
                        current_page_new('reports/reports.php?id=57', 'Sales Summary Report', 'Sales Summary Report');
                        current_page_new('reports/reports.php?id=60', 'Call Adition Summary Report', 'Call Adition Summary Report');
                      ?>
                                                </ul>
                                              </li>
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