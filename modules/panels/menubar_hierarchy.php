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
                    <?php }
                    /*
                    if ($_SESSION['customerno'] == '118') { ?>
                        <ul role="navigation" class="nav">
                            <?php
                            $elixirRoleId = 1;
                            $masterRoleId = 1;
                            $stateRoleId = 2;
                            $zoneRoleId = 3;
                            $regionRoleId = 4;
                            $cityRoleId = 8;
                            $groupRoleId = 0;
                            $accountRoleId = 0;
                            switch ($_SESSION['customerno']) {
                                case 63:
                                    $masterRoleId = 28;
                                    $zoneRoleId = 30;
                                    $regionRoleId = 31;
                                    break;
                                case 64:
                                    $masterRoleId = 33;
                                    $zoneRoleId = 35;
                                    $regionRoleId = 36;
                                    break;
                                case 118:
                                    $masterRoleId = 18;
                                    $stateRoleId = 19;
                                    $zoneRoleId = 20;
                                    $regionRoleId = 21;
                                    $cityRoleId = 23;
                                    $groupRoleId = 22;
                                    $accountRoleId = 42;
                                    break;
                                case 167:
                                    $masterRoleId = 24;
                                    $zoneRoleId = 26;
                                    $regionRoleId = 27;
                                    $accountRoleId = 42;
                                    break;
                                case 258:
                                    $masterRoleId = 44;
                                    $stateRoleId = 45;
                                    $groupRoleId = 46;
                                    break;
                                default:
                                    $masterRoleId = 1;
                                    $zoneRoleId = 3;
                                    $regionRoleId = 4;
                                    break;
                            }
                            if ($_SESSION['role_modal'] != 'Viewer') {
                                $userroleid = array($masterRoleId, $stateRoleId, $zoneRoleId, $regionRoleId, $cityRoleId, $groupRoleId, $accountRoleId, "5", "6", "1");
                                if (in_array($_SESSION["roleid"], $userroleid)) {
                                    if ($_SESSION["roleid"] != $accountRoleId) {
                                        ?>
                                        <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Masters <b class="caret"></b></a>
                                            <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                                                <?php
                                                $usersroleids = array($masterRoleId, $stateRoleId, "5", "6", "1");
                                                if (in_array($_SESSION["roleid"], $usersroleids)) {
                                                    ?>
                                                    <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/account/users.php?id=2' tabindex='-1' role='menuitem'>Users</a></li>
                                                <?php } ?>
                                                <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/vehicle/vehicle.php?id=2' tabindex='-1' role='menuitem'>Vehicles</a></li>
                                                <?php
                                                $usersroleids = array($masterRoleId, $stateRoleId, "5", "6", "1");
                                                if (in_array($_SESSION["roleid"], $usersroleids)) {
                                                    ?>
                                                    <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/dealer/dealer.php?id=2' tabindex='-1' role='menuitem'>Dealer </a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/parts/parts.php?id=2'>Parts</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/task/task.php?id=2'>Tasks</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/accessories/accessories.php?id=2'>Accessories</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/make/make.php?id=2'>Make</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/model/model.php?id=2'>Model</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/insurance_company/insurance.php?id=2'>Insurance</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/battery_srno/battery_srno.php?id=2'>Battery Serial No.</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/tyre_srno/tyre_srno.php?id=2'>Tyre Serial No.</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/insurance_dealer/insurance_dealer.php?id=2'>Insurance Dealer</a></li>
                                                    <?php if ($_SESSION["use_hierarchy"] == '1') { ?>
                                                        <li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'>Hierarchy</a>
                                                            <ul class='dropdown-menu'>
                                            <!--                                                        <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/nation/nation.php' tabindex='-1' role='menuitem'><?php echo $_SESSION['nation'] ?></a></li>
                                                                <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/state/state.php' tabindex='-1' role='menuitem'><?php echo $_SESSION['state'] ?></a></li>
                                                                <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/district/district.php' tabindex='-1' role='menuitem'><?php echo $_SESSION['district'] ?></a></li>
                                                                <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/city/city.php' tabindex='-1' role='menuitem'><?php echo $_SESSION['city'] ?></a></li>-->
                                                                <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/group/group.php' tabindex='-1' role='menuitem'><?php echo $_SESSION['group'] ?></a></li>
                                                                <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/hierarchy/hierarchy.php?id=2' tabindex='-1' role='menuitem'> Hierarchy Settings </a></li>
                                                                <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/hierarchy/conditions.php?id=2' tabindex='-1' role='menuitem'> Maintenance Conditions </a></li>
                                                            </ul>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($_SESSION["use_hierarchy"] == '0') {
                                                        ?>
                                                        <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/group/group.php' tabindex='-1' role='menuitem'><?php echo $_SESSION['group'] ?></a></li>                    <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                        }
                                        ?>
                                    </li>
                                    <?php
                                    $transactionroles = array($masterRoleId, $stateRoleId, $zoneRoleId, $regionRoleId, $cityRoleId, $groupRoleId, $accountRoleId, '5', '6', '1');
                                    if (in_array($_SESSION["roleid"], $transactionroles)) {
                                        echo "<li><a href='" . $_SESSION['subdir'] . "/modules/transactions/transaction.php?id=2' title='Transactions'>Transactions</a></li>";
                                    }
                                }
                                if ($_SESSION["roleid"] != $groupRoleId && $_SESSION["roleid"] != $accountRoleId) {
                                    ?>
                                    <li class="dropdown">
                                        <a data-intro="Manage your Approvals" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Approvals <b class="caret"></b></a>
                                        <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                                            <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/approvals/approvals.php?id=1' title='Vehicle Approvals' tabindex='-1' role='menuitem'>Vehicles</a></li>
                                            <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/approvals/approvals.php?id=2' title='Transaction Approvals' tabindex='-1' role='menuitem'>Transactions</a></li>
                                        </ul>
                                    </li>
                                    <?php
                                }

                                if ($_SESSION["roleid"] != $accountRoleId) {
                                    ?>
                                    <li class="dropdown">
                                        <a data-intro="Analyze your reports" data-position="left" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Reports <b class="caret"></b></a>
                                        <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">
                                            <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/reports/reports.php?id=37' title='Vehicle Approvals' tabindex='-1' role='menuitem'>Fuel History</a></li>
                                            <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/reports/reports.php?id=43' tabindex='-1' role='menuitem'>Renewal Report</a></li>
                                            <li role='presentation'><a href='<?php echo $_SESSION['subdir'] ?>/modules/reports/reports.php?id=49' title='Transaction Details' tabindex='-1' role='menuitem'>Transaction History</a></li>
                                        </ul>
                                    </li>
                                    <?php
                                }
                                ?>

<!--                                <li>
                                    <a href='<?php echo $_SESSION['subdir'] ?>/modules/support/support.php' title='Support'>Support</a>
                                </li>-->
                                <?php if ($_SESSION['customerno'] == 118) { ?>


                                    <li class="dropdown">
                                        <a data-intro="Analyze your reports" data-position="left" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">BusRoute <b class="caret"></b></a>
                                        <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">
                                            <li class = 'dropdown-submenu'><a>Master</a>
                                                <ul class = 'dropdown-menu'>
                                                    <li role = 'presentation'><a href = '<?php echo $_SESSION['subdir'] ?>/modules/busroute/zone.php' role = 'menuitem'>Zone</a></li>
                                                    <li role = 'presentation'><a href = '<?php echo $_SESSION['subdir'] ?>/modules/busroute/busStop.php' role = 'menuitem'>Bus Stop</a></li>
                                                    <li role = 'presentation'><a href = '<?php echo $_SESSION['subdir'] ?>/modules/busroute/busRoute.php' role = 'menuitem'>Bus Route</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/busroute/student.php?id=1'>Students</a></li>
                                                    <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/busroute/czone.php'>Circular Zone</a></li>
                                                </ul>
                                            </li>
                                            <!--
                                            <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/busroute/import.php' title='route'>Import Data</a></li>

                                            <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/busroute/zoneVehicleMapping.php' title='route'>Zone-Vehicle Mapping</a></li>
                                            -->
                                            <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/busroute/busRouteMap.php' title='route'>Student Mapping</a></li>
                                            <!--
                                            <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/busroute/studentMapping.php' title='route'>Bus Stop Mapping</a></li>
                                            -->
                                            <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/busroute/directionMap.php' title='route'>Bus Route</a></li>
                                            <li><a href='<?php echo $_SESSION['subdir'] ?>/modules/busroute/busZone.php' title='route'>Zones</a></li>

                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php
                            }
                            echo"</ul>";
                        } else {
                        */    
                            $elixirRoleId = 1;
                            $masterRoleId = 1;
                            $stateRoleId = 2;
                            $zoneRoleId = 3;
                            $regionRoleId = 4;
                            $cityRoleId = 8;
                            $groupRoleId = 0;
                            $accountRoleId = 0;
                            switch ($_SESSION['customerno']) {
                                case 63:
                                    $masterRoleId = 28;
                                    $zoneRoleId = 30;
                                    $regionRoleId = 31;
                                    break;
                                case 64:
                                    $masterRoleId = 33;
                                    $zoneRoleId = 35;
                                    $regionRoleId = 36;
                                    break;
                                case 118:
                                    $masterRoleId = 18;
                                    $stateRoleId = 19;
                                    $zoneRoleId = 20;
                                    $regionRoleId = 21;
                                    $cityRoleId = 23;
                                    $groupRoleId = 22;
                                    $accountRoleId = 42;
                                    break;
                                case 167:
                                    $masterRoleId = 24;
                                    $zoneRoleId = 26;
                                    $regionRoleId = 27;
                                    $accountRoleId = 42;
                                    break;
                                case 258:
                                    $masterRoleId = 44;
                                    $stateRoleId = 45;
                                    $groupRoleId = 46;
                                    break;
                                default:
                                    $masterRoleId = 1;
                                    $zoneRoleId = 3;
                                    $regionRoleId = 4;
                                    break;
                            }

                            $heirmanager = new HierarchyManager($_SESSION['customerno'], $_SESSION['userid']);
                            $allmenulistuser = $heirmanager->getcustomerdetailmenu($_SESSION['userid']);
                            
                            $allmenulistall = $heirmanager->getmenuslist_new($_SESSION['customerno']);
                            if (empty($allmenulistuser)) {
                                $allmenulist = $allmenulistall;
                            } else {
                                $allmenulist = $allmenulistuser;
                            }
                            //$allmenulist = $allmenulistall;
                            ?> 
                            <ul role="navigation" class="nav">
                            <?php
                            for ($i = 0; $i < count($allmenulist['children']); $i++) {
                                if (empty($allmenulist['children'][$i]['children'])) {
                                    ?>
                                        <li><a href='<?php echo $_SESSION['subdir'] . $allmenulist['children'][$i]['page']; ?>' title='<?php echo $allmenulist['children'][$i]['text']; ?>'><?php echo $allmenulist['children'][$i]['text']; ?></a></li>  
                                    <?php } else { ?>
                                        <li class="dropdown">
                                        <?php $countchild = count($allmenulist['children'][$i]['children']); ?>
                                            <a data-intro="<?php echo $allmenulist['children'][$i]['text']; ?>" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);"><?php echo $allmenulist['children'][$i]['text'] ?> <b class="caret"></b></a>
                                            <?php
                                            $children = $allmenulist['children'][$i]['children'];
                                            if (isset($children) && !empty($children)) {
                                                ?>
                                                <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                                                <?php
                                                $subchild = "";
                                                for ($j = 0; $j < count($children); $j++) {
                                                    $pagech1 = $children[$j]['page'];
                                                    $textch1 = $children[$j]['text'];
                                                    $subchild = $children[$j]['children'];
                                                    //print_r($subchild);
                                                    if (isset($subchild) && !empty($subchild)) {
                                                        ?>
                                                            <li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'><?php echo $textch1; ?></a>
                                                                <ul class='dropdown-menu'>
                                                                    <?php
                                                                    for ($kk = 0; $kk < count($subchild); $kk++) {
                                                                        $textsubchild = $subchild[$kk]['text'];
                                                                        $pagesubchild = $subchild[$kk]['page'];
                                                                   ?>    
                                                                    <li role='presentation'><a href='<?php echo $_SESSION['subdir'].$pagesubchild; ?>' tabindex='-1' role='menuitem'><?php echo $textsubchild ?></a></li>
                                                                  <?php  }
                                                                    ?>
                                                                </ul>       
                                                                <?php }else{
                                                                ?>    
                                                            <li><a href='<?php echo $_SESSION['subdir'] . $pagech1; ?>'><?php echo $textch1; ?></a></li>
                                                            <?php
                                                        }
                                                    }
                                                    echo"</ul>";
                                                }
                                                ?>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <?php
                        //}
                        ?>

                        <?php require_once 'right_panel.php'; ?>
                </div>
            </div>
        </div> <!-- /navbar-example -->
    </div>
<?php } ?>
