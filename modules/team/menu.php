
<?php
include_once("session.php");
include_once("db.php");
include_once("../../constants/speedConstants.php");
include_once("../../lib/system/DatabaseManager.php");

if (IsLoggedIn()) {
    $db = new DatabaseManager();
    $loggedTeamUserId = GetLoggedInUserId();
    $SQL = sprintf("SELECT name,teamid  FROM ".DB_ELIXIATECH.".team where teamid=".$loggedTeamUserId);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
               $teamid = $row['teamid'];
            }   
    }
}
$IsSalesHead = checkUserType(speedConstants::TEAM_DEPARTMENT_SALES,speedConstants::TEAM_ROLE_HEAD);
$IsManagementHead = checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT,speedConstants::TEAM_ROLE_HEAD);
?>
<div class="bs-docs-example" style="height:41px;">
    <div class="navbar navbar-static" id="navbar-example">
        <div class="navbar-inner">
            <div style="width: auto;" ><!-- class="container" -->
                <a data-position="bottom" data-toggle="dropdown" class="brand dropdown dropdown-toggle" role="button" id="drop2" style="cursor: pointer;">
                    <span style="color: black; font-weight: bold; font-size: initial;">Elixia</span>
                    <span style="color: #0193CC; font-weight: bolder; font-size: initial;">Team</span>
                </a>
                
                <?php
                //print_r($_SESSION);
                if (IsLoggedIn()) {
                    $loggedTeamUserId = GetLoggedInUserId();
                    ?>
                    <div align="center">
                        <div style="width:auto">
                            <ul role="navigation" class="nav">
                                <?php
                                if( (checkUserType(speedConstants::TEAM_DEPARTMENT_SALES)) && (!checkUserType('',speedConstants::TEAM_ROLE_HEAD)) ){
                                ?>
                                 <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Sales <b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Customers</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='customers.php' tabindex='-1' role='menuitem'>Live Customers</a></li>
                                                        <li role='presentation'><a href='customersdemo.php' tabindex='-1' role='menuitem'>Demo Customers</a></li>
                                                        <li role='presentation'><a href='customersclosed.php' tabindex='-1' role='menuitem'>Closed Customers</a></li>
                                                        <li role='presentation'><a href='customerstrace.php' tabindex='-1' role='menuitem'>Trace Customers</a></li>

                                                    </ul></li>
                                                <li role='presentation'><a href='allotrm.php' tabindex='-1' role='menuitem'>Allot CRM</a></li>
                                                <li role='presentation'><a href='crm_orders.php' tabindex='-1' role='menuitem'>CRM Orders</a></li>
                                                <li role='presentation'><a href='orders.php' tabindex='-1' role='menuitem'>Orders</a></li>
                                                <li role='presentation'><a href='knowledge_share.php' tabindex='-1' role='menuitem'>Knowledge Base</a></li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Petty Cash</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='addvoucher.php' tabindex='-1' role='menuitem'>Add Voucher</a></li>
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Masters</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='sales_stage.php' tabindex='-1' role='menuitem'>Stage</a></li>
                                                        <li role='presentation'><a href='sales_source.php' tabindex='-1' role='menuitem'>Source</a></li>
                                                        <li role='presentation'><a href='sales_product.php' tabindex='-1' role='menuitem'>Product</a></li>
                                                        <li role='presentation'><a href='sales_industry.php' tabindex='-1' role='menuitem'>Industry Type</a></li>
                                                        <li role='presentation'><a href='sales_mode.php' tabindex='-1' role='menuitem'>Mode</a></li>
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Pipeline</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='salesPipelineDashboard.php' tabindex='-1' role='menuitem'>Dashboard</a></li>
                                                        <li role='presentation'><a href='sales_pipeline.php' tabindex='-1' role='menuitem'>Add pipeline</a></li>
                                                        <li role='presentation'><a href='activePipelines.php' tabindex='-1' role='menuitem'>Active Pipelines</a></li>
                                                        <li role='presentation'><a href='frozenPipelineList.php' tabindex='-1' role='menuitem'>Frozen & Lost Pipelines</a></li>
       
                                                <li role='presentation'><a href='salesDashboard.php' tabindex='-1' role='menuitem'>Sales Dashboard</a>
                                                </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href='logout.php'>Log Out</a></li>
                                        </ul>
                                        <span style="float:right;"><p class="navbar-text navbar-right">Signed in as <a href="modifyteam.php?sid=<?php echo GetLoggedInUserId(); ?>" class="navbar-link" style="color:#0193CC;"><?php echo $_SESSION['sessionteamusername']; ?></a></p></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                                <?php
                                return;
                                }
                                if (IsDistributor()) {
                                    ?>
                                    <li><a href='purchase_dist.php'>Purchase</a></li>
                                    <li><a href='dealers.php'>Dealers</a></li>
                                    <li><a href='allotment.php'>Allotment</a></li>
                                    <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Customer Details<b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <li role='presentation'><a href='distCustDetails.php' tabindex='-1' role='menuitem'>Add Customer Details</a></li>
                                                <li role='presentation'><a href='view_distCustDetails.php' tabindex='-1' role='menuitem'>View Customer Details</a></li>
                                            </ul>
                                    </li>
                                    <?php
                                }
                                if (IsDealer()) {
                                    ?>

                                    <li><a href='retailers.php'>Customer</a></li>
                                    <?php
                                }
                                if (!IsRepair() && !IsDealer() && !IsDistributor() && !IsData()) {
                                    ?>
                                    <?php
                                    if (IsHead()) {
                                        ?>
                                        <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Procurement&nbsp;<b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <!-- <li role='presentation'><a href='purchase.php' tabindex='-1' role='menuitem'>Purchase</a></li>                       -->
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Purchase</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><li><a href='purchase.php'tabindex='-1' role='menuitem'>Speed Purchase</a></li>
                                                        <li role='presentation'><li><a href='purchase_trace.php' tabindex='-1' role='menuitem'>Trace Purchase</a></li>
                                                    </ul>
                                                </li>

                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Repair</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><li><a href='repair.php'tabindex='-1' role='menuitem'>Unit</a></li>
                                                        <li role='presentation'><li><a href='transmitter_repair.php' tabindex='-1' role='menuitem'>Transmitters</a></li>
                                                    </ul>
                                                </li>
                                                <li role='presentation'><a href='disconnection.php' tabindex='-1' role='menuitem'>Disconnection</a></li>

                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if (IsHead()) {
                                        ?>
                                        <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Inventory&nbsp;<b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <li role='presentation'><li><a href='invtesting.php'tabindex='-1' role='menuitem'>Testing</a></li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Allotment</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><li><a href='invallotment.php'tabindex='-1' role='menuitem'>Unit</a></li>
                                                        <li role='presentation'><li><a href='transallotment.php'tabindex='-1' role='menuitem'>Transmitters</a></li>
                                                    </ul>
                                                </li>
                                                <li role='presentation'><li><a href='invallocation.php'tabindex='-1' role='menuitem'>Allocation</a></li>
                                                <li role='presentation'><li><a href='stock_count.php'tabindex='-1' role='menuitem'>Current Stock</a></li>
                                                <li role='presentation'><li><a href='inventory.php'tabindex='-1' role='menuitem'>Stock Analysis</a></li>
                                                <li role='presentation'><li><a href='migrate.php'tabindex='-1' role='menuitem'>Recover Unit Data</a></li>
                                                <li role='presentation'><li><a href='migrateVehicleChk.php'tabindex='-1' role='menuitem'>Recover Vehicle Checkpoint Data</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if (IsSales() || IsHead()) {/* Sales based menu */
                                        ?>


                                        <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Sales <b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Customers</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='customers.php' tabindex='-1' role='menuitem'>Live Customers</a></li>
                                                        <li role='presentation'><a href='customersdemo.php' tabindex='-1' role='menuitem'>Demo Customers</a></li>
                                                        <li role='presentation'><a href='customersclosed.php' tabindex='-1' role='menuitem'>Closed Customers</a></li>
                                                        <li role='presentation'><a href='customerstrace.php' tabindex='-1' role='menuitem'>Trace Customers</a></li>

                                                    </ul></li>
                                                <li role='presentation'><a href='allotrm.php' tabindex='-1' role='menuitem'>Allot CRM</a></li>
                                                <li role='presentation'><a href='crm_orders.php' tabindex='-1' role='menuitem'>CRM Orders</a></li>
                                                <li role='presentation'><a href='orders.php' tabindex='-1' role='menuitem'>Orders</a></li>
                                                <li role='presentation'><a href='knowledge_share.php' tabindex='-1' role='menuitem'>Knowledge Base</a></li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Petty Cash</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='addvoucher.php' tabindex='-1' role='menuitem'>Add Voucher</a></li>
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Masters</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='sales_stage.php' tabindex='-1' role='menuitem'>Stage</a></li>
                                                        <li role='presentation'><a href='sales_source.php' tabindex='-1' role='menuitem'>Source</a></li>
                                                        <li role='presentation'><a href='sales_product.php' tabindex='-1' role='menuitem'>Product</a></li>
                                                        <li role='presentation'><a href='sales_industry.php' tabindex='-1' role='menuitem'>Industry Type</a></li>
                                                        <li role='presentation'><a href='sales_mode.php' tabindex='-1' role='menuitem'>Mode</a></li>
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Pipeline</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='salesPipelineDashboard.php' tabindex='-1' role='menuitem'>Dashboard</a></li>
                                                        <li role='presentation'><a href='sales_pipeline.php' tabindex='-1' role='menuitem'>Add pipeline</a></li>
                                                        <li role='presentation'><a href='activePipelines.php' tabindex='-1' role='menuitem'>Active Pipelines</a></li>
                                                        <li role='presentation'><a href='frozenPipelineList.php' tabindex='-1' role='menuitem'>Frozen & Lost Pipelines</a></li>
                                                               
                                                <li role='presentation'><a href='salesDashboard.php' tabindex='-1' role='menuitem'>Sales Dashboard</a>
                                                </li>
                                                    </ul>
                                                </li>
                                                <?php if($IsSalesHead || $IsManagementHead){?>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Reports</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='dailySalesReport.php' tabindex='-1' role='menuitem'>Daily Sales Report</a></li>
                                                    </ul>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if (IsService() || IsHead()) {/* Operations or Service based menu */
                                        ?>
                                        <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Operations <b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Customers</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='customers.php' tabindex='-1' role='menuitem'>Live Customers</a></li>
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Inventory</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><li><a href='invtesting.php'tabindex='-1' role='menuitem'>Testing</a></li>
                                                        <li role='presentation'><li><a href='invallotment.php'tabindex='-1' role='menuitem'>Allotment</a></li>
                                                        <li role='presentation'><li><a href='invallocation.php'tabindex='-1' role='menuitem'>Allocation</a></li>
                                                        <li role='presentation'><li><a href='stock_count.php'tabindex='-1' role='menuitem'>Current Stock</a>
                                                    </ul>
                                                </li>
                                                <li role='presentation'><a href='servicecall.php' tabindex='-1' role='menuitem'>Service Call</a></li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Petty Cash</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='addvoucher.php' tabindex='-1' role='menuitem'>Add Voucher</a></li>
                                                    </ul>
                                                </li>
                                                <li role='presentation'><a href='bucketlist.php' tabindex='-1' role='menuitem'>Bucket List</a></li>
                                                <li role='presentation'><a href='compliance.php' tabindex='-1' role='menuitem'>Compliance</a></li>
                                                <li role='presentation'><a href='queryAnalyzer.php' tabindex='-1' role='menuitem'>Query Analyzer</a></li>
                                                <li role='presentation'><a href='queryAnalyzer_new.php' tabindex='-1' role='menuitem'>Query Analyzer New</a></li>
                                                <li role='presentation'><a href='diagnosis.php' tabindex='-1' role='menuitem'>Diagnosis</a></li>
                                                <li role='presentation'><a href='troubleshooting.php' tabindex='-1' role='menuitem'>Troubleshooting</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if (IsCRM() || IsHead()) {/* CRM based menu */
                                        ?>
                                        <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">CRM <b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <li role='presentation'><a href='customers.php' tabindex='-1' role='menuitem'>Live Customers</a></li>
                                                <li><a href='realtime.php'>Data</a></li>
                                                <li><a href='inactive.php'>Inactive</a></li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Orders</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='orders.php' tabindex='-1' role='menuitem'>Place Orders</a></li>
                                                        <li role='presentation'><a href='crm_orders.php' tabindex='-1' role='menuitem'>CRM Orders</a></li>
                                                    </ul>
                                                </li>
                                                <li role='presentation'><a href='allotrm.php' tabindex='-1' role='menuitem'>Allot CRM</a></li>
                                                <li role='presentation'><a href='bucketlist_crm.php' tabindex='-1' role='menuitem'>Bucket List</a></li>

                                                <li role='presentation'><a href='broadcast.php' tabindex='-1' role='menuitem'>Broadcast</a></li>
                                                <li role='presentation'><li><a href='migrate.php'tabindex='-1' role='menuitem'>Recover Unit Data</a></li>
												<li role='presentation'><a href='unmapped_vehicle.php' tabindex='-1' role='menuitem'>Unmapped Vehicle</a></li>
                                                <li role='presentation'><a href='vehicle_migration.php' tabindex='-1' role='menuitem'>Vehicle Migration</a></li>
                                                <li role='presentation'><a href='customer_verification.php' tabindex='-1' role='menuitem'>Customer Sanity</a></li>
                                                <li role='presentation'><a href='budgeting.php' tabindex='-1' role='menuitem'>Budgeting</a></li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Petty Cash</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='addvoucher.php' tabindex='-1' role='menuitem'>Add Voucher</a></li>
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Accounts</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='customerLedger.php' tabindex='-1' role='menuitem'>Customer Ledger</a></li>
                                                        <li role='presentation'><a href='paymentDue.php' tabindex='-1' role='menuitem'>Aging Analysis</a></li>
                                                        <li role='presentation'><a href='acc_customers.php' tabindex='-1' role='menuitem'>Payment Collection</a></li>
                                                    </ul>
                                                    <li role='presentation'><a href='servicecall.php' tabindex='-1' role='menuitem'>Service Call</a></li>
                                                </li>

                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Dockets</a>
                                                <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='dashboard_crm.php' tabindex='-1' role='menuitem'>Dashboard</a></li>
                                                        <li role='presentation'><a href='docket.php' tabindex='-1' role='menuitem'>Add Docket</a></li>
                                                        <li role='presentation'><a href='mydockets.php' tabindex='-1' role='menuitem'>My Dockets</a></li>
                                                    </ul>
                                            </li>
											
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if (IsAdmin() || IsHead() || (checkUserType(speedConstants::TEAM_DEPARTMENT_ACCOUNTS))) {/* Account based menu */
                                        ?>
                                        <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Accounts <b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <li role='presentation'><a href='acc_customers.php' tabindex='-1' role='menuitem'>Customer Recieveables</a></li>
                                                <li role='presentation'><a href='bankstatement.php' tabindex='-1' role='menuitem'>Bank Statement</a></li>
                                                <li role='presentation'><a href='schedule_invoice.php' tabindex='-1' role='menuitem'>Schedule Invoices</a></li>
                                                <li role='presentation'><a href='credit_note.php' tabindex='-1' role='menuitem'>Credit Note</a></li>
                                                <li role='presentation'><a href='cashmemo.php' tabindex='-1' role='menuitem'>Cash Memo</a></li>
                                                <li role='presentation'><a href='invoice_payment.php' tabindex='-1' role='menuitem'>Invoice Payment</a></li>
                                                <li role='presentation'><a href='item_master.php' tabindex='-1' role='menuitem'>Item Master</a></li>
                                                <!--<li role='presentation'><a href='invoice_generate.php' tabindex='-1' role='menuitem'>Generate Invoices</a></li> -->

                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Invoices</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='proforma_inv.php' tabindex='-1' role='menuitem'>Proforma Invoices</a></li>
                                                        <li role='presentation'><a href='invoicedata.php' tabindex='-1' role='menuitem'>Taxed Invoices</a></li>
                                                        <li role='presentation'><a href='otherinvoice.php' tabindex='-1' role='menuitem'>Other Invoices</a></li>
                                                        <li role='presentation'><a href='pending_invoice.php' tabindex='-1' role='menuitem'>Pending Invoices</a></li>
                                                        <li role='presentation'><a href='pending_renewals.php' tabindex='-1' role='menuitem'>Pending Renewals</a></li>
                                                        <li role='presentation'><a href='invoicesearch.php' tabindex='-1' role='menuitem'>Search</a></li>
                                                         <?php
                                                            if((checkUserType(speedConstants::TEAM_DEPARTMENT_ACCOUNTS)) && checkUserType('',speedConstants::TEAM_ROLE_HEAD) || checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT)){
                                                            ?> 
                                                         <li role='presentation'><a href='invoice_links.php' tabindex='-1' role='menuitem'>Invoice Links</a></li>
                                                         <?php
                                                            }
                                                         ?>
                                                    </ul>
                                                </li>
                                                <!--                                                <li role='presentation'><a href='receivables.php' tabindex='-1' role='menuitem'>Receivables</a></li> -->
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Petty Cash</a>
                                                    <ul class="dropdown-menu">
                                                        <li role='presentation'><a href='addvoucher.php' tabindex='-1' role='menuitem'>Add Voucher</a></li>
                                                        <li role='presentation'><a href='receivedcash.php' tabindex='-1' role='menuitem'>Received Cash</a></li>
                                                        <li role='presentation'><a href='payment.php' tabindex='-1' role='menuitem'>Payment</a></li>
                                                        <li role='presentation'><a href='advancepayment.php' tabindex='-1' role='menuitem'>Advance Payment</a></li>
                                                        <li role='presentation'><a href='analysis_elixir.php' tabindex='-1' role='menuitem'>Current Status</a></li>
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Masters</a>
                                                    <ul class="dropdown-menu">
                                                        <li role='presentation'><a href='ledger.php' tabindex='-1' role='menuitem'>Ledger</a></li>
                                                        <li role='presentation'><a href='vendors.php' tabindex='-1' role='menuitem'>Vendors</a></li>
                                                        <li role='presentation'><a href='category.php' tabindex='-1' role='menuitem'>Category</a></li>
                                                        <!--
                                                        <li role='presentation'><a href='bankStatementGenericReport.php' tabindex='-1' role='menuitem'>Bank Statement Generic Report</a></li>
                                                        <li role='presentation'><a href='bankStatementGraphicalAnalysis.php' tabindex='-1' role='menuitem'>Bank Statement Graphical Analysis</a></li>
                                                        -->
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Bank Reconciliation</a>
                                                    <ul class="dropdown-menu">
                                                        <li role='presentation'><a href='deposit.php' tabindex='-1' role='menuitem'>Deposits</a></li>
                                                        <li role='presentation'><a href='withdrawal.php' tabindex='-1' role='menuitem'>Withdrawals</a></li>
                                                        <?php if (GetLoggedInUserId() == 1) { ?>
                                                            <li role='presentation'><a href='bank_recon_stmt.php' tabindex='-1' role='menuitem'>Bank Reconciliation Statement</a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </li>
                                                <li role='presentation'><a href='tallyCompare.php' tabindex='-1' role='menuitem'>Payment Reconciliation</a></li>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <li><a href='devices.php'>Search</a></li> <!--Open to all menu -->
                                    <?php
                                    if (IsHead()) {
                                        ?>
                                        <li><a href='team.php'>Team</a></li>
                                        <li class="dropdown">
                                            <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Analysis<b class="caret"></b></a>
                                            <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                                <li role='presentation'><a href='dashboard_inventory.php' tabindex='-1' role='menuitem'>Unit</a></li>
                                                <li role='presentation'><a href='dashboard_simcard.php' tabindex='-1' role='menuitem'>Simcard</a></li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Login History</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='login_history.php' tabindex='-1' role='menuitem'>By Customer</a></li>
                                                        <li role='presentation'><a href='login_time.php' tabindex='-1' role='menuitem'>By Time</a></li>
                                                    </ul></li>
                                                  <li><a href='crm_analysis.php'>CRM Analysis</a></li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Support</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='searchticket.php' tabindex='-1' role='menuitem'>Search Tickets</a></li>
                                                        <li role='presentation'><a href='ticketanalysis.php' tabindex='-1' role='menuitem'>Tickets Analysis</a></li>
                                                        <li role='presentation'><a href='supportBroadcast.php' tabindex='-1' role='menuitem'>BroadCast</a></li>
                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Accounts</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='analysis_account.php' tabindex='-1' role='menuitem'>Analysis</a></li>
                                                        <li role='presentation'><a href='pettycash_analysis.php' tabindex='-1' role='menuitem'>Petty Cash Analysis</a></li>
                                                        <?php
                                                        if ($loggedTeamUserId == 1) {
                                                            echo "<li role='presentation'><a href='profitLossAnalysis.php' tabindex='-1' role='menuitem'>Profit & Loss Analysis</a></li>";
                                                            echo "<li role='presentation'><a href='profitratio.php' tabindex='-1' role='menuitem'>Profit Ratio</a></li>";
                                                        }
                                                        ?>

                                                    </ul>
                                                </li>
                                                <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Operations</a>
                                                    <ul class='dropdown-menu'>
                                                        <li role='presentation'><a href='analysis.php' tabindex='-1' role='menuitem'>Analysis</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href='scorecard.php'>ScoreCard</a></li>
                                                <li role='presentation'><a href='customerbehavior.php' tabindex='-1' role='menuitem'>Customer Behavior Report</a></li>
                                                <li role='presentation'><a href='expense_behavior.php' tabindex='-1' role='menuitem'>Expense Behavior Report</a></li>
                                                <li role='presentation'><a href='invoice_prediction.php' tabindex='-1' role='menuitem'>Invoice Prediction</a></li>
                                                <li role='presentation'><a href='avg_credit_day.php' tabindex='-1' role='menuitem'>Average Credit Days</a></li>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                    <li class='dropdown' role='presentation'>
                                        <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Support<b class="caret"></b></a>
                                        <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                            <li role='presentation'><a href='ticket.php' tabindex='-1' role='menuitem'>Add Ticket</a></li>
                                            <li role='presentation'><a href='myticket.php' tabindex='-1' role='menuitem'>My Tickets</a></li>
                                            <li role='presentation'><a href='mycticket.php' tabindex='-1' role='menuitem'>My Close Tickets</a></li>
                                            <li role='presentation'><a href='showallticket.php' tabindex='-1' role='menuitem'>All Tickets</a></li>
                                            <li role='presentation'><a href='supportBroadcast.php' tabindex='-1' role='menuitem'>BroadCast</a></li>
                                        </ul>
                                    </li>
                                    <li class='dropdown' role='presentation'>
                                        <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Timesheet<b class="caret"></b></a>
                                        <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                        <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Tasks</a>
                                            <ul class='dropdown-menu'>

                                        <?php
                                        if((checkUserType(speedConstants::TEAM_DEPARTMENT_SOFTWARE)) && checkUserType('',speedConstants::TEAM_ROLE_HEAD) || checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT)){
                                        ?> 
                                                <li role='presentation'><a href='newTask.php' tabindex='-1' role='menuitem'>Add Task</a></li>
                                        <?php } 
                                        if(checkUserType(speedConstants::TEAM_DEPARTMENT_SOFTWARE)){
                                        ?>
                                                <li role='presentation'><a href='taskList.php' tabindex='-1' role='menuitem'>View Tasks</a></li>
                                        <?php } ?>
                                                <li role='presentation'><a href='addTeamRole.php' tabindex='-1' role='menuitem'>Assign member role</a></li>
                                            </ul>
                                         </li>
                                            <li class='dropdown-submenu' role='presentation'><a href='javascript:void(0)' tabindex='-1' role='menuitem'>Timesheets</a>
                                           <ul class='dropdown-menu'>     
                                             <li role='presentation'><a href='importTimesheet.php' tabindex='-1' role='menuitem'>Upload Timesheet</a></li>
                                             <li role='presentation'><a href='updateTimesheet.php' tabindex='-1' role='menuitem'>Add Timesheet</a></li>
                                             <li role='presentation'><a href='lockTimesheet.php' tabindex='-1' role='menuitem'>Lock Timesheet</a></li>
                                             <?php
                                        if((checkUserType(speedConstants::TEAM_DEPARTMENT_SOFTWARE) && checkUserType('',speedConstants::TEAM_ROLE_HEAD)) || checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT)){
                                        ?>
                                        <li role='presentation'><a href='viewTimesheets.php' tabindex='-1' role='menuitem'>View Timesheet</a></li>
                                         </li>
                                         <?php 
                                            }
                                        ?>
                                            </ul>
                                        </ul>
                                    </li>
                                     <?php 
                                     } 

                                     ?>
                                    <?php 
                                 if(checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT)){
                                        ?>
                                 <li class='dropdown' role='presentation'>
                                        <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);">Management<b class="caret"></b></a>
                                        <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                            <li role='presentation'><a href='add_content_newsLetter.php' tabindex='-1' role='menuitem'>Add NewsLetter</a></li>
                                            <li role='presentation'><a href='view_newsLetterContent.php' tabindex='-1' role='menuitem'>Send NewsLetter</a></li>
                                        </ul>
                                    </li>
                               <!--  <li><a href='school.php'>School</a></li> -->
                                <?php 
                                    }
                                ?>
                                <li class='dropdown' role='presentation'>
                                        <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="javascript:void(0);" style="color:#0193CC;"><?php echo $_SESSION['sessionteamloginuser']; ?><b class="caret"></b></a>
                                        <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
                                            <li role='presentation'><a href='logout.php' tabindex='-1' role='menuitem'>Logout</a></li>
                                            <li role='presentation'><a href='team_account_setting.php' tabindex='-1' role='menuitem'>Account Settings</a></li>
                                        </ul>
                                    </li>

                            </ul>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>
