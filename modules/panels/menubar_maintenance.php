<?php
$new = '<img src=' . $_SESSION['subdir'] . '/images/new_small.gif>';
if (isset($_SESSION['username'])) {
    $customerno = $_SESSION['customerno'];
    $company = $_SESSION['customercompany'];
    ?>
    <div class="bs-docs-example" style="height:41px;">
        <div class="navbar navbar-static" id="navbar-example">
            <div class="navbar-inner">
                <div style="width: auto;" class="container">
                    <?php
                    include_once '../../lib/bo/UserManager.php';
                    $umgr = new UserManager();
                    $accuser = $umgr->getUserForAccountSwitch($_SESSION['userid']);
                    if ($_SESSION["customerno"] == '116') {
                        ?>
                        <a href="" class="brand"><img src="../../images/116/image001.png"></img></a>
                    <?php } else if ($_SESSION["customerno"] == '9') {
                        ?>
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
                                $umgr = new UserManager();
                                $users = $umgr->getAllElixir($customer->customerno);
                                foreach ($users as $user) {
                                    ?>
                                    <li  role="presentation" onclick="changeaccount(<?php echo $user->userid; ?>)" style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><a><?php echo $customer->customerno; ?> - <?php echo $customer->customercompany ?></a></li>
                                    <?php
                                }
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
                        if ($_SESSION['role_modal'] != 'Viewer') {
                            if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir' || $_SESSION["roleid"] == '1' || $_SESSION["roleid"] == '8' || $_SESSION["roleid"] == '4' || $_SESSION["roleid"] == '10') {
                                ?>
                                <li class="dropdown">
                                    <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Masters <b class="caret"></b></a>
                                    <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                                        <?php
                                        if ($_SESSION["roleid"] != "8" && $_SESSION["roleid"] != "4") {
                                            echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/account/users.php?id=2' tabindex='-1' role='menuitem'>Users</a></li>";
                                        }
                                        echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/vehicle/vehicle.php?id=2' tabindex='-1' role='menuitem'>Vehicles</a></li>";
                                        if ($_SESSION['roleid'] == '1' || $_SESSION["role_modal"] == 'elixir' || $_SESSION['roleid'] == '8' || $_SESSION['roleid'] == '5' || $_SESSION["roleid"] == '10') {
                                            if ($_SESSION['use_maintenance'] == '1') {

                                                if ($_SESSION['roleid'] != '8') {
                                                    echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/dealer/dealer.php' tabindex='-1' role='menuitem'>Dealer </a></li>
                                    <li><a href='" . $_SESSION['subdir'] . "/modules/parts/parts.php?id=2'>Parts</a></li>
                                    <li><a href='" . $_SESSION['subdir'] . "/modules/task/task.php?id=2'>Tasks</a></li>
                                    <li><a href='" . $_SESSION['subdir'] . "/modules/accessories/accessories.php?id=2'>Accessories</a></li>";

                                                    if ($_SESSION["use_hierarchy"] == '1') {
                                                        echo "<li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'>Hierarchy</a>
                            <ul class='dropdown-menu'>
                              <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/nation/nation.php' tabindex='-1' role='menuitem'>" . $_SESSION['nation'] . " </a></li>
                              <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/state/state.php' tabindex='-1' role='menuitem'>" . $_SESSION['state'] . " </a></li>
                              <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/district/district.php' tabindex='-1' role='menuitem'>" . $_SESSION['district'] . "</a></li>
                              <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/city/city.php' tabindex='-1' role='menuitem'>" . $_SESSION['city'] . " </a></li>
                              <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/group/group.php' tabindex='-1' role='menuitem'>" . $_SESSION['group'] . " </a></li>";
                             if($_SESSION['customerno'] == '118'){                           
                                 echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/hierarchy/hierarchy.php?id=2' tabindex='-1' role='menuitem'> Hierarchy Settings </a></li> 
                                                        <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/hierarchy/conditions.php?id=2' tabindex='-1' role='menuitem'> Maintenance Conditions </a></li>";
                                                            }
                            echo "</ul>
                            </li>";
                                                        
                                                    }

                                                    if ($_SESSION["use_hierarchy"] == '0') {
                                                        echo "   <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/group/group.php' tabindex='-1' role='menuitem'>" . $_SESSION['group'] . " </a></li>  ";
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                                if ($_SESSION['roleid'] == '1' || $_SESSION["role_modal"] == 'elixir' || $_SESSION['roleid'] == '8' || $_SESSION['roleid'] == '5' || $_SESSION['roleid'] == '10') {
                                    if ($_SESSION['use_maintenance'] == '1') {
                                        echo "<li><a href='" . $_SESSION['subdir'] . "/modules/transactions/transaction.php?id=2' title='Transactions'>Transactions</a></li>";
                                    }
                                }
                            }

                            if ($_SESSION['use_maintenance'] == '1' && $_SESSION["roleid"] != '8') {
                                ?>
                                <li class="dropdown">
                                    <a data-intro="Manage your Approvals" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Approvals <b class="caret"></b></a>
                                    <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                                        <?php
                                        echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/approvals/approvals.php?id=1' title='Vehicle Approvals' tabindex='-1' role='menuitem'>Vehicles</a></li>";
                                        echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/approvals/approvals.php?id=2' title='Transaction Approvals' tabindex='-1' role='menuitem'>Transactions</a></li>";
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }
                            echo '<li class="dropdown">
                 <a data-intro="Analyze your reports" data-position="left" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Reports <b class="caret"></b></a>
                  <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">';
                            echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=37' title='Vehicle Approvals' tabindex='-1' role='menuitem'>Fuel History</a></li>";
                            echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=43' tabindex='-1' role='menuitem'>Renewal Report</a></li>";
                            echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=49' title='Transaction Details' tabindex='-1' role='menuitem'>Transaction History</a></li>";
                            echo '</ul>
                 </li>';
                            echo "<li><a href='" . $_SESSION['subdir'] . "/modules/support/support.php' title='Support'>Support</a></li>";
                        }
                        ?>
                    </ul>
                    <?php require_once 'right_panel.php'; ?>
                </div>
            </div>
        </div> <!-- /navbar-example -->
    </div>


    <!-- end #menu -->

<?php } ?>