<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/bo/pipelineManager.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
include("header.php");
// See if we need to save a new one.
if (IsHead() || IsSales()) {
    $teamid = $_SESSION['sessionteamid'];
    $today = date("Y-m-d H:i:s");
    $pipelineid = $_REQUEST['pipelineid'];
    if ($pipelineid != "") {
        $db = new DatabaseManager();
        $sqlpipeline = sprintf("Select   sp.pipeline_date
                                        ,sp.company_name
                                        ,sp.sourceid
                                        ,sp.productid
                                        ,sp.industryid
                                        ,sp.modeid
                                        ,sp.teamid
                                        ,sp.location
                                        ,sp.remarks
                                        ,sp.stageid
                                        ,sp.teamid_creator
                                        ,sp.device_cost
                                        ,sp.subscription_cost
                                        ,sp.loss_reason
                                        ,sp.tepidity
                                        ,sp.quantity
                                        ,sp.quotationDetails
                                        ,sp.revive_date
                                        ,t.name as teamname 
                                FROM    " . DB_PARENT . ".`sales_pipeline` as sp  
                                left join " . DB_PARENT . ".team as t on t.teamid = sp.teamid 
                                WHERE   sp.pipelineid = %d AND sp.isdeleted=0", Sanitise::Long($pipelineid));
        $db->executeQuery($sqlpipeline);
        $pipelinedata = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $pipelinedata[] = array(
                    'pipelinedate' => $row['pipeline_date'],
                    'companyname' => $row['company_name'],
                    'sourceid' => $row['sourceid'],
                    'productid' => $row['productid'],
                    'industryid' => $row['industryid'],
                    'modeid' => $row['modeid'],
                    'teamid' => $row['teamid'],
                    'teamname' => $row['teamname'],
                    'location' => $row['location'],
                    'remarks' => $row['remarks'],
                    'stageid' => $row['stageid'],
                    'teamid_creator' => $row['teamid_creator'],
                    'devicecost' => $row['device_cost'],
                    'subscriptioncost' => $row['subscription_cost'],
                    'tepidity' => $row['tepidity'],
                    'quantity' => $row['quantity'],
                    'quotationDetails' => $row['quotationDetails'],
                    'loss_reason' => $row['loss_reason'],
                    'revive_date' => $row['revive_date']
                );
            }
        }
        $productList = explode(",",$pipelinedata[0]['productid']);
        $message = "";
        $pipelinedate = "";
        $companyname = "";
        $location = "";
        $designation = "";
        $contactperson = "";
        $emailaddress = "";
        $contactno = "";
        $remarks = "";
        $db = new DatabaseManager();
        $sqlstage = sprintf("Select * from " . DB_PARENT . ".`sales_stage` where isdeleted=0");
        $db->executeQuery($sqlstage);
        $stagedata = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $team = new stdClass();
                $team->stageid = $row["stageid"];
                $team->stage_name = $row["stage_name"];
                $stagedata[] = $team;
            }
        }

        ///get all source
        $db = new DatabaseManager();
        $sqlstage = sprintf("Select * from " . DB_PARENT . ".`sales_source` where isdeleted=0");
        $db->executeQuery($sqlstage);
        $sourcedata = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $src = new stdClass();
                $src->sourceid = $row["sourceid"];
                $src->source_name = $row["source_name"];
                $sourcedata[] = $src;
            }
        }

        ///get all product
        $db = new DatabaseManager();
        $sqlproduct = sprintf("Select * from " . DB_PARENT . ".`sales_product` where isdeleted=0");
        $db->executeQuery($sqlproduct);
        $productdata = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $prod = new stdClass();
                $prod->productid = $row["productid"];
                $prod->product_name = $row["product_name"];
                $productdata[] = $prod;
            }
        }
        ///get all industry type
        $db = new DatabaseManager();
        $sqlproduct = sprintf("Select * from " . DB_PARENT . ".`sales_industry_type` where isdeleted=0");
        $db->executeQuery($sqlproduct);
        $industrydata = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $ind = new stdClass();
                $ind->industryid = $row["industryid"];
                $ind->industry_type = $row["industry_type"];
                $industrydata[] = $ind;
            }
        }

        ///get all Mode
        $db = new DatabaseManager();
        $sqlproduct = sprintf("Select * from " . DB_PARENT . ".`sales_mode` where isdeleted=0");
        $db->executeQuery($sqlproduct);
        $modedata = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $ind = new stdClass();
                $ind->modeid = $row["modeid"];
                $ind->mode = $row["mode"];
                $modedata[] = $ind;
            }
        }

        ///get all Team allot to
        $db = new DatabaseManager();
        $sqlteam = sprintf("Select * from " . DB_PARENT . ".`team`");
        $db->executeQuery($sqlteam);
        $teamdata = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $team = new stdClass();
                $team->teamid = $row["teamid"];
                $team->name = $row["name"];
                $teamdata[] = $team;
            }
        }

        $db = new DatabaseManager();
        $sqlproduct = sprintf("Select * from " . DB_ELIXIATECH . ".`sales_tepidity` where isdeleted=0");
        $db->executeQuery($sqlproduct);
        $tepidityData = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $tep = new stdClass();
                $tep->tepidityId = $row["tepidityId"];
                $tep->tepidityName = $row["tepidityName"];
                $tepidityData[] = $tep;
            }
        }
        $stageid = 1;

        //users list
        $db = new DatabaseManager();
        $pipelineid = $_GET['pipelineid'];
        $SQLUser = sprintf("SELECT sc.contactid,sc.pipelineid,sc.designation,sc.name as username,sc.phone,sc.email,sc.timestamp,t.name FROM " . DB_PARENT . ".sales_contact as sc LEFT join team as t ON sc.teamid_creator = t.teamid where sc.pipelineid =" . $pipelineid . " AND sc.isdeleted=0");
        $db->executeQuery($SQLUser);

        $details1 = array();
        if ($db->get_rowCount() > 0) {
            $x = 1;
            $delete_url = "";
            while ($row = $db->get_nextRow()) {
                $userdetails = new stdClass();
                $contactid = $row["contactid"];
                $userdetails->srno = $x;
                $userdetails->designation = $row["designation"];
                $userdetails->username = $row["username"];
                $userdetails->name = $row["name"];
                $userdetails->phone = $row["phone"];
                $userdetails->email = $row["email"];
                $userdetails->timestamp = date('d-m-Y H:i:s', strtotime($row["timestamp"]));
                $userdetails->contactid = $row["contactid"];
                $userdetails->pipelineid = $row["pipelineid"];
                $teamid = $_SESSION['sessionteamid'];
                $delete_url = "<a target='_blank' href='javascript:void(0);' alt='Delete User' title='Mode' onclick='deleteUser(" . $contactid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
                $userdetails->delete_url = $delete_url;
                $details1[] = $userdetails;
                $x++;
            }
        }
        $dg1 = new objectdatagrid($details1);
        $dg1->AddAction("View/Edit", "../../images/edit.png", "modify_pipelineuser.php?contactid=%d");
        $dg1->AddColumn("Pipeline id", "pipelineid");
        $dg1->AddColumn("Name", "username");
        $dg1->AddColumn("Designation", "designation");
        $dg1->AddColumn("phone", "phone");
        $dg1->AddColumn("email", "email");
        $dg1->AddColumn("Created By", "name");
        $dg1->AddColumn("Created Time", "timestamp");
        $dg1->AddColumn("Delete", "delete_url");
        $dg1->SetNoDataMessage("No User Added");
        $dg1->AddIdColumn("contactid");

        //userreminder
        $db = new DatabaseManager();
        $SQLreminder = sprintf("SELECT sr.reminderid,sr.reminder_datetime,sr.content,sr.pipelineid,sr.contactid,sr.timestamp,t.name as cronset FROM " . DB_PARENT . ".sales_reminder as sr left join team as t ON sr.contactid = t.teamid where sr.pipelineid=" . $pipelineid . " AND sr.isdeleted=0");
        $db->executeQuery($SQLreminder);

        $details2 = array();
        if ($db->get_rowCount() > 0) {
            $x = 1;
            $delete_url = "";
            while ($row = $db->get_nextRow()) {
                $reminderdatetime = date('d-m-Y H:i:s', strtotime($row["reminder_datetime"]));
                $userdetails = new stdClass();
                $reminderid = $row["reminderid"];
                $userdetails->srno = $x;
                $userdetails->reminder_datetime = date('d-m-Y H:i:s', strtotime($reminderdatetime));
                $userdetails->content = $row["content"];
                $userdetails->pipelineid = $row["pipelineid"];
                $userdetails->reminder_send_to_name = $row["cronset"];
                $userdetails->timestamp = date('d-m-Y H:i:s', strtotime($row["timestamp"]));
                $userdetails->contactid = $row["contactid"];
                $userdetails->pipelineid = $row["pipelineid"];
                $userdetails->reminderid = $row["reminderid"];
                $teamid = $_SESSION['sessionteamid'];
                $delete_url = "<a target='_blank' href='javascript:void(0);' alt='Delete Reminder' title='Mode' onclick='deleteReminder(" . $reminderid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
                $userdetails->delete_url = $delete_url;
                $details2[] = $userdetails;
                $x++;
            }
        }


        //pipeline history
        //userreminder
        $db = new DatabaseManager();
        $SQLHistory = sprintf("SELECT ss.source_name,sp.product_name,si.industry_type,sm.mode,st.stage_name, sph.pipeline_date,sph.pipelineid,sph.loss_reason,"
                . "  sph.company_name,sph.remarks,sph.timestamp"
                . "  FROM " . DB_PARENT . ".sales_pipeline_history as sph "
                . " left join " . DB_PARENT . ".sales_source as ss ON sph.sourceid = ss.sourceid "
                . " left join " . DB_PARENT . ".sales_product as sp ON sph.productid = sp.productid  "
                . " left join " . DB_PARENT . ".sales_industry_type as si ON sph.industryid = si.industryid  "
                . " left join " . DB_PARENT . ".sales_mode as sm ON sph.modeid = sm.modeid  "
                . " left join " . DB_PARENT . ".sales_stage as st ON sph.stageid = st.stageid  "
                . "  where sph.pipelineid=" . $pipelineid . " AND sph.isdeleted=0 order by sph.pipeline_history_id desc");
        //echo $SQLHistory; die();
        $db->executeQuery($SQLHistory);


        $details3 = array();
        if ($db->get_rowCount() > 0) {
            $x = 1;
            $delete_url = "";
            while ($row = $db->get_nextRow()) {
                $reminderdatetime = date('d-m-Y H:i:s', strtotime($row["reminder_datetime"]));
                $userdetails = new stdClass();
                $reminderid = $row["pipeline_history_id"];
                $userdetails->srno = $x;
                $userdetails->pipelineid = $row["pipelineid"];
                $userdetails->source_name = $row["source_name"];
                $userdetails->product_name = $row["product_name"];
                $userdetails->stage_name = $row["stage_name"];
                $userdetails->pipeline_date = date('d-m-Y', strtotime($row["pipeline_date"]));
                $userdetails->pipeline_history_id = $row["pipeline_history_id"];
                $userdetails->remarks = $row["remarks"];
                $userdetails->loss_reason = $row["loss_reason"];
                $userdetails->timestamp = $row["timestamp"];
                $teamid = $_SESSION['sessionteamid'];
                $details3[] = $userdetails;
                $x++;
            }
        }

        if($pipelinedata[0]['stageid']==8){
            $db = new DatabaseManager();
            $sqlCommercial = sprintf("SELECT sc.contactid,c.commercial_details,c.customerno FROM customer c
                                     INNER JOIN sales_pipeline sp on sp.customerno = c.customerno
                                     INNER JOIN sales_contact sc on sc.pipelineid = sp.pipelineid AND isUserCreated = 1
                                        WHERE sp.pipelineid =".$pipelineid);
            $db->executeQuery($sqlCommercial);

            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $details = new stdClass();
                    $details->customerno   = $row['customerno'];
                    $details->contact_id   = $row['contactid']; 
                    $details->comm_details = $row["commercial_details"];
                }
            }
        }

        $pM = new pipelineManager();
        $files = $pM->fetchPipelineFiles($pipelineid);
        ?>
    <style>
        table tr td {
            vertical-align: middle !important;
        }
    </style>
            <link rel="stylesheet" href="../../css/sales_pipeline.css">
        <script>$.noConflict();</script>
        <div class="panel">
            <div class="paneltitle" align="center">Update Pipeline</div>
            <div class="panelcontents">
                <form method="post" enctype="multipart/form-data" name="updatepipelineform" id="updatepipelineform">
                          <?php echo($message); ?>
                    <table style="margin-right: auto;" class='column'>
                        <tr>
                            <?php $todaydate = date('d-m-Y'); ?>
                            <td>Prospect Identified On <span style="color:red;">*</span></td><td><input id="pipelinedate" name = "pipelinedate" value="<?php echo $todaydate; ?>" type="text"></td>
                        <tr><td>Company Name </td><td><input id="companyname" name = "companyname" type="text" placeholder="Company Name" value="<?php echo $pipelinedata[0]['companyname']; ?>"></td></tr>
                        <tr><td>Stage</td><td>
                                <select name="stage" id="stage" onchange="getstage();">
                                    <option value="0">Select Stage</option>
                                    <?php
                                    foreach ($stagedata as $row) {
                                        $selected = "";
                                        if ($pipelinedata[0]['stageid'] == $row->stageid) {
                                            $selected = "selected";
                                        }
                                        ?>
                                        <option <?php echo $selected; ?> value="<?php echo $row->stageid; ?>"><?php echo $row->stage_name; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            <?php
                                if ($pipelinedata[0]['stageid'] == '8') {
                                    ?>
                                    <tr class="purchasedetails" style='<?php echo $style; ?>' ><td>Purchase Order No</td><td><input type="text" name="porderno" id="porderno" ></td></tr>
                            <?php } else {
                            ?>
                                    <tr class="purchasedetails"><td>Purchase Order No</td><td><input type="text" name="porderno" id="porderno" ></td>

                                    </tr>
                            <?php }
                            ?>
                            <tr id='revival'><td>Revive Date</td> <td><input type='text' name='reviveDate' id='reviveDate' value=<?php echo date_format(date_create($pipelinedata[0]['revive_date']),"m/d/Y");?>> </td>
                            <?php
                                if ($pipelinedata[0]['stageid'] == '6') {
                                    $style = 'display:table-row;';
                                    ?>
                                    <tr class="lossreason" style='<?php echo $style; ?>' ><td>Loss Reason</td><td><textarea name="loss_reason" id="loss_reason" placeholder="Enter loss reason"></textarea></td></tr>
                            <?php } else {
                                ?>
                                    <tr class="lossreason"><td>Loss Reason</td><td><input type='text' name="loss_reason" id="loss_reason" placeholder="Enter loss reason" value='<?php echo $pipelinedata[0]['loss_reason'];?>'></td></tr>
                            <?php }
                            ?>
                            </tr>
                        <?php
                        $devicecost = isset($pipelinedata[0]['devicecost']) ? $pipelinedata[0]['devicecost'] : "";
                        $subscriptioncost = isset($pipelinedata[0]['subscriptioncost']) ? $pipelinedata[0]['subscriptioncost'] : "0";
                        if ($pipelinedata[0]['stageid'] == '4') {
                            $style = 'display:table-row;';
                            ?>
                            <tr class="qtndetails" style='<?php echo $style; ?>' ><td>Device Cost</td><td><input type="text" name="devicecost" id="devicecost"value="<?php echo isset($devicecost)?$devicecost : 0; ?>"></td></tr>
                            <tr class="qtndetails" style='<?php echo $style; ?>' ><td>Subscription Cost</td><td><input type="text" name="subscriptioncost" id="subscriptioncost"value="<?php echo $subscriptioncost; ?>"></td>
                                </tr>
                        <?php } else { ?>
                            <tr class="qtndetails"><td>Device Cost</td><td><input type="text" name="devicecost" id="devicecost"value="<?php echo $devicecost; ?>"></td></tr>
                            <tr class="qtndetails"><td>Subscription Cost</td><td><input type="text" name="subscriptioncost" id="subscriptioncost"value="<?php echo $subscriptioncost; ?>"></td></tr>
                        <?php } ?>
                        
                        
                        <tr><td>Source </td>
                            <td>
                                <select name="source" id="source">
                                    <option value="0">Select Source</option>
                                    <?php
                                    $selectedsource = "";
                                    foreach ($sourcedata as $row) {
                                        $sourceid = $pipelinedata[0]['sourceid'];
                                        $selectedsource = '';
                                        if ($sourceid == $row->sourceid) {
                                            $selectedsource = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $row->sourceid; ?>" <?php echo $selectedsource; ?>><?php echo $row->source_name; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <div class="ProductDropdown" id="ProductDropdown">
                                    <dl class="dropdown1"> 
                                    <dt>
                                        <a href="#" style="color:#000;">
                                         Product <span style="color:red;">*</span> <i id="arrowdown" class='material-icons'>
                                        keyboard_arrow_down
                                        </i><i id="arrowup" class='material-icons'>
                                        keyboard_arrow_up
                                        </i> 

                                        <p class="multiSel"></p>  
                                        </a>
                                    </dt>
                                    <dd>
                                        <div class="mutliSelect">
                                        <ul name="productId" id="productId" class='testing'>
                                        </ul>
                                        </div>
                                    </dd>
                                    </dl>
                                </div>
                            </td>
                        </tr>
                        </tr>
                            <input type="hidden" name="productSelected" id="productSelected" value="<?php echo $pipelinedata[0]['productid']?>">
                        <tr>
                            <td>Industry Type </td>
                            <td>
                                <select name="industry" id="industry">
                                    <option value="0">Select Industry Type</option>
                                    <?php
                                    $selectedindustry = "";
                                    foreach ($industrydata as $row2) {
                                        $industryid = $pipelinedata[0]['industryid'];
                                        $selectedindustry = '';
                                        if ($industryid == $row2->industryid) {
                                            $selectedindustry = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $row2->industryid; ?>" <?php echo $selectedindustry; ?>><?php echo $row2->industry_type; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Mode </td>
                            <td>
                                <select name="mode" id="mode">
                                    <option value="0">Select Mode</option>
                                    <?php
                                    $selectedmode = "";
                                    foreach ($modedata as $row3) {
                                        $modeid = $pipelinedata[0]['modeid'];
                                        $selectedmode = '';
                                        if ($modeid == $row3->modeid) {
                                            $selectedmode = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $row3->modeid; ?>" <?php echo $selectedmode; ?>><?php echo $row3->mode; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td>
                                <?php
                                $location = isset($pipelinedata[0]['location']) ? $pipelinedata[0]['location'] : "";
                                ?>
                                <textarea name="location" id="location"><?php echo $location; ?></textarea>
                            </td>
                            <td>
                            </td>
                            <td>
                                <img src="../../images/loader_elixia.gif" style="display: none;margin:0 45%;" id="loader_show">
                            </td>
                        </tr>
                        <tr>
                            <td>Remarks</td>
                            <td>
                                <?php
                                $remarks = isset($pipelinedata[0]['remarks']) ? $pipelinedata[0]['remarks'] : "";
                                ?>
                                <textarea name="remarks" id="remarks"><?php echo $remarks; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Tepidity</td>
                            <td>
                                <select id='tepidity' name='tepidity'>
                                <?php foreach ($tepidityData as $tepidity){?>      
                                    <option value="<?php echo $tepidity->tepidityId; ?>" <?php if($pipelinedata[0]['tepidity']==$tepidity->tepidityId){echo "selected";}?>><?php echo $tepidity->tepidityName; ?></option>
                                <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Quantity</td>
                            <td>
                                <input type='text' id='quantity' name='quantity' value='<?php echo $pipelinedata[0]['quantity'];?>'>
                            </td>
                        </tr>
                        <?php
                        if (IsHead()) {
                            $alloted_id = isset($pipelinedata[0]['teamid']) ? $pipelinedata[0]['teamid'] : "0";
                            $teamname = isset($pipelinedata[0]['teamname']) ? $pipelinedata[0]['teamname'] : "";
                            ?>
                            <tr>
                                <td>Assign To </td>
                                <td>
                                    <input  type="text" name="teamalloted" id="teamalloted" size="20" value="<?php echo $teamname; ?>" autocomplete="off" placeholder="Enter Team member" onkeyup="getTeam()" />
                                    <input type="hidden" id="teamid" name="teamid" value="<?php echo $alloted_id; ?>">
                                </td>
                            </tr>
                        <?php } else { ?>
                            <input type="hidden" id="teamid" name="teamid" value="<?php echo $teamid; ?>">
                        <?php } ?>
                        <tr id="commDetails">
                               <input type="hidden" name="pipeline_customer" id="pipeline_customer" value="<?php echo $details->customerno ?>">
                                <input type="hidden" name="contactID" id="contactID" value="<?php echo $details->contact_id ?>"> 
                               <td>Commercial Details</td><td><textarea maxlength="500" name="commercial_details" id="commercial_details" required placeholder="Enter Commercial Details" <?php if($details->comm_details!=''){echo "readonly";}?>
                               ><?php echo $details->comm_details ?></textarea>
                              </td>
                               <td><div id="USERLIST" name="USERLIST"></div></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="button" id="submitpipeline" onclick = "updatePipeline();" name="submitpipeline" class="btn btn-primary" style="background: #00A5B9;" value="Update"/>
                            </td>
                        </tr>

                    </table>
                    <div class='row'   >
                        <div class='column' id='quotationDiv' name='quotationDiv' style='padding-left:20px;'>
                            <strong id='qLabel'>Quotation</strong><br>
                            <input type='file' id='quotation' name='quotation'>
                            <br><strong><label for='qDetails'>Quotation details</label></strong><br>
                            <textarea  id='qDetails' name='qDetails' value=""><?php echo $pipelinedata[0]['quotationDetails'];?></textarea>
                        </div>
                        <div class='column' id='brdDiv' name='brdDiv' style='padding-left:20px;'>
                            <strong id='bLabel'>BRD</strong><br>
                            <input type='file' id='brd' name='brd'>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
        <br/>
        <div class="panel">
            <div class="paneltitle" align="center">Credentials</div>
            <div class="panelcontents">
                <form name="addpipeusers" id="addpipeusers" method="post" action="modify_pipeline.php?pipelineid=<?php echo $_REQUEST['pipelineid']; ?>" onsubmit="return ValidateFormUser();
                                return false;">
                          <?php if ($usermessage != "") { ?>
                        <span> User Already exists.</span>
                    <?php } ?>
                    <table>
                        <tr><td>Name </td><td><input type="text" name="username" id="username"></td></tr>
                        <tr><td>Designation </td><td><input type="text" name="userdesignation" id="userdesignation"></td></tr>
                        <tr><td>Phone </td><td><input type="text" name="userphone" id="userphone" onkeyup="onlyNos();"></td></tr>
                        <tr><td>Email </td><td><input type="text" name="useremail" id="useremail" onblur="checkEmail();"></td></tr>
                        <tr>
                            <td><input type="hidden" id="userpipelineid" name="userpipelineid" value="<?php echo $_REQUEST['pipelineid']; ?>"></td>
                            <td><input type="button" value="Add User" id="userdetails" class="btn btn-primary" style="background: #00A5B9;" name="userdetails" onclick='ValidateFormUser();'></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="panel">
            <div class="paneltitle" align="center">User List</div>
            <div class="panelcontents">
                <div id='usersDiv' class="ag-theme-balham" style="height:300px;width:100%;margin:0 auto;">
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="paneltitle" align="center">Reminders</div>
            <div class="panelcontents">
                <form name="reminderform" id="reminderform" method="post" action="modify_pipeline.php?pipelineid=<?php echo $_REQUEST['pipelineid']; ?>" >
                    <table>
                        <tr><td>Reminder Date & Time</td><td><input type="text" name="reminderdatetime" id="reminderdatetime">
                                <input id="STime" name="STime" type="text" class="input-mini" value="00:00"/>
                            </td></tr>
                        <tr><td>Content </td><td><input type='text' name="content" id="content"></td></tr>
                        <tr><td>Contact </td>
                            <td>
                                <input  type="text" name="contact" id="contact" size="20" autocomplete="off" placeholder="Enter Team member" onkeyup="getTeam1()" />
                                <input type="hidden" id="teamidsales" name="teamidsales">
                            </td>
                        </tr>
                        <tr>
                            <td><input type="hidden" id="userpipelineid" name="userpipelineid" value="<?php echo $_REQUEST['pipelineid']; ?>"></td>
                            <td><input type="button" value="Add Reminder" id="reminderdetails" class="btn btn-primary" style="background: #00A5B9;" name="reminderdetails" onclick='ValidateFormReminder();'></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="panel">
            <div class="paneltitle" align="center">Reminder List</div>
            <div class="panelcontents">
                <div id='remindersDiv' class="ag-theme-balham" style="height:400px;width:100%;margin:0 auto;">
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="paneltitle" align="center">Pipeline History</div>
            <div class="panelcontents">
                <div id='historyDiv' class="ag-theme-balham" style="height:400px;width:100%;margin:0 auto;">
                </div>
            </div>
        </div>



        <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
        <script type="text/javascript">

            jQuery(document).ready(function () {
                getstage();

               
                jQuery('#pipelinedate').datepicker({
                    format: "yyyy-mm-dd",
                    language: 'en',
                    autoclose: 1
                });
                jQuery('#reviveDate').datepicker({
                    format: "dd-mm-yyyy",
                    language: 'en',
                    autoclose: 1
                });       
                jQuery('#reminderdatetime').datepicker({
                    format: "dd-mm-yyyy",
                    language: 'en',
                    autoclose: 1
                });    

                jQuery('#STime').timepicker({
                    'timeFormat': 'H:i'
                    });
                  var files = <?php echo json_encode($files)?>;
                    
                if(typeof(files.Quotation)!='undefined'){
                    var quotation = files.Quotation;
                    var str='<table border="1px" cellpadding="7">';
                    $.each(quotation,function(i,doc){
                        console.log(doc);
                        str +='<tr>';
                        str += '<td><a target="_blank" href="'+doc.downloadPath+'"">'+doc.fileName+'</a></td>';
                        str += '<td> '+doc.uploadedTime+'</td>';
                        str += '<td>Uploaded By : '+doc.name+'</td>';
                        str += '</tr>';
                    });
                    str+='</table>';
                    $('#quotation').after(str);
                }
                if(typeof(files.BRD)!='undefined'){
                    var brd = files.BRD;
                    var str='<table border="1px" cellpadding="7">';
                    $.each(brd,function(i,doc){
                        console.log(doc);
                        str +='<tr>';
                        str += '<td><a target="_blank" href="'+doc.downloadPath+'"">'+doc.fileName+'</a></td>';
                        str += '<td> '+doc.uploadedTime+'</td>';
                        str += '<td>Uploaded By : '+doc.name+'</td>';
                        str += '</tr>';
                    });
                    str+='</table>';
                    $('#brd').after(str);
                }

                var userRows = <?php echo json_encode($details1);?>;


                //console.log(userRows);
                var reminderRows = <?php echo json_encode($details2);?>;
                //console.log(reminderRows);
                var historyRows = <?php echo json_encode($details3);?>;
                //console.log(historyRows);
                var userColumns = [
                    {headerName:'Edit', field:'contactid', cellRenderer:'editUserRenderer', width:70},
                    {headerName:'Delete', field:'delete_url', cellRenderer:'deleteUrlRenderer', width:80},
                    {headerName:'Sr. no.',field: 'srno', width:80},
                    {headerName:'Pipeline ID',field: 'pipelineid'},
                    {headerName:'Name',field: 'username'},
                    {headerName:'Designation',field: 'designation'},
                    {headerName:'Phone',field: 'phone'},
                    {headerName:'Email',field: 'email'},
                    {headerName:'Created By', field:'name'},
                ];
                //$dg2->AddAction("View/Edit", "../../images/edit.png", "modify_reminder.php?reminderid=%d");
                var reminderColumns = [                    
                    {headerName:'Edit', field:'reminderid',cellRenderer:'editReminderRenderer', width:70},
                    {headerName:'Delete',field: 'delete_url', cellRenderer:'deleteUrlRenderer', width:80},
                    {headerName:'Sr. no.',field: 'srno', width:80},
                    {headerName:'Content',field:'content'},
                    {headerName:'Reminder Date',field: 'reminder_datetime'},
                    {headerName:'Reminder Set For',field: 'reminder_send_to_name'},
                    {headerName:'Created Time',field: 'timestamp'},
                ];
                
                var historyColumns = [
                    {headerName:'Sr. no.',field: 'srno', width:80},
                    {headerName:'Pipeline Date',field: 'pipeline_date'},
                    {headerName:'Source',field: 'source_name'},
                    {headerName:'Product',field: 'product_name'},
                    {headerName:'Stage',field: 'stage_name'},
                    {headerName:'Loss reason',field: 'loss_reason'},
                    {headerName:'Remarks',field: 'remarks'},
                ];
                var userGridOptions = {
                    enableFilter:true,
                    rowData:userRows,
                    
                    animateRows: true,
                    columnDefs: userColumns,
                    components: {deleteUrlRenderer : deleteUserRenderer,editUserRenderer : editUserRenderer
                    }
                };
                var reminderGridOptions = {
                    enableFilter:true,
                    rowData:reminderRows,
                    
                    animateRows: true,
                    columnDefs: reminderColumns,
                    components: {deleteUrlRenderer : deleteReminderRenderer,editReminderRenderer : editReminderRenderer
                    }
                };
                var historyGridOptions = {
                    enableFilter:true,
                    rowData:historyRows,
                    
                    animateRows: true,
                    columnDefs: historyColumns,
                };
                agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
                var gridDiv = document.querySelector('#usersDiv');
                new agGrid.Grid(gridDiv, userGridOptions);
                gridDiv = document.querySelector('#remindersDiv');
                new agGrid.Grid(gridDiv, reminderGridOptions);
                gridDiv = document.querySelector('#historyDiv');
                new agGrid.Grid(gridDiv, historyGridOptions); 
            });

            function updatePipeline(){
                if( (jQuery("#stage").val()==10) && (jQuery("#loss_reason").val()=='') ){
                    alert("Please enter the loss reason");
                    return;
                }
                if((jQuery("#stage").val()==8)){
                    
                    if((jQuery("#commercial_details").val()=='')){
                        alert("Please enter commercial details");
                        jQuery("#commercial_details").focus();
                        return false;
                    }

                    if(jQuery("#contactid").val()==0 || jQuery("#contactid").val()==''){
                        alert("Please Select One User as Owner.");
                        return false;
                    }
                    
                }
                var pipelineId = <?php echo $pipelineid?>;
                var pipelinedate = jQuery("#pipelinedate").val();
                        
                if (pipelinedate == "") {
                    alert("Please select pipelinedate");
                    return false;
                }else {
                    files = new FormData(jQuery('#updatepipelineform')[0]);
                    files.append('pipelineId',pipelineId);
                    files.append('updatePipeline',1);
                    jQuery.ajax({
                        url: 'pipeline_functions.php',
                        type: 'POST',
                        data:files,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (res) {
                            jQuery("#loader_show").show();
                            if (res == 'Data Updated Sucessfully') {
                                if(jQuery("#stage").val()==8 && jQuery("#pipeline_customer").val()==0){
                                    files.append('insert_customer',1);
                                    jQuery.ajax({
                                        type: "POST",
                                        url: "route_team.php",
                                        data: files,
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        success: function(result){
                                            var response = JSON.parse(result);
                                            if(response>0){
                                                alert("Customer Added Successfully.");
                                                //window.location.href = 'sales_pipeline.php';
                                            }
                                            else{
                                                alert("Customer Not Created.Please Try Again.");
                                                return false;
                                            }
                                            jQuery("#loader_show").hide();
                                        }
                                    });
                                }
                                alert("Pipeline Updated Successfully.");
                            }
                            else{
                                alert("Please Update the Pipeline Again.");
                            }
                            
                        }

                    });
                    
                }
            }
                
            function getstage() {
                jQuery("#revival").hide();
                jQuery(".purchasedetails").hide();
                jQuery(".lossreason").hide();
                jQuery("#commDetails").hide();
                var stagetxt = jQuery("#stage option:selected").val();

                    
                if (stagetxt == 4) {
                    jQuery(".qtndetails").show();
                } else if (stagetxt == 8) {
                    
                    jQuery("#commDetails").show();

                    var user_list = <?php echo json_encode($details1);?>;
                    var customerCreated = jQuery("#pipeline_customer").val();
                    if(customerCreated==0 || customerCreated==''){
                        if(user_list.length!=0){
                            var str1='';
                            str1="<table class='userList' id ='userList' border='1' align='center'><tr><th>Sr no.</th><th>Names</th><th>OWNER</th></tr>";
                            var j = 1;
                            var user_contact_Id = jQuery("#contactID").val();
                            var disabledString = '';
                            

                            if(user_contact_Id!=0){
                                disabledString = 'disabled=true';
                            }
                            jQuery.each(user_list,function(i,text){
                           
                            str1+="<tr>";
                            str1+="<td>"+j+"</td>";
                            str1+="<td style='width:auto;'>"+text.username+"</td>";
                                    
                            var checkedString = '';
                            if(user_contact_Id==text.contactid || user_list.length==1){
                                checkedString = 'checked';
                            }
                            str1+="<td><input type='radio' class='myRadio' name='contactid' value="+text.contactid+" "+ checkedString+"  " + disabledString +"></td>";

                            str1+="</tr>";
                            j++;
                            });
                            str1+="</table>";

     
                            
                            jQuery("#USERLIST").after(str1);
                        }
                    }

                    jQuery(".purchasedetails").show();
                } else if (stagetxt == 10) {
                    jQuery(".lossreason").show();
                } else if (stagetxt == 9){
                    jQuery("#revival").show();
                }
            }
                    
            function getTeam() {
                jQuery("#teamalloted").autocomplete({
                    source: "route_ajax.php?action=getteamdata",
                    select: function (event, ui) {
                        jQuery(this).val(ui.item.value);
                        jQuery('#teamid').val(ui.item.teamid);
                        return false;
                    }
                });
            }

            function getTeam1() {
                jQuery("#contact").autocomplete({
                    source: "route_ajax.php?action=getteamdata",
                    select: function (event, ui) {
                        jQuery(this).val(ui.item.value);
                        jQuery('#teamidsales').val(ui.item.teamid);
                        return false;
                    }
                });
            }
                    
            
                    
            function deleteUserRenderer(params){
                return "<a target='_blank' href='javascript:void(0);' alt='Delete User' title='Mode' onclick='deleteUser("+params.data.contactid+");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete_icon_black.png'/></a>";
            }

            function deleteReminderRenderer(params){
                return "<a target='_blank' href='javascript:void(0);' alt='Delete Reminder' title='Mode' onclick='deleteReminder("+params.data.reminderid+");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete_icon_black.png'/></a>";
            }

            function editUserRenderer(params){
                return "<a target='_blank' href='modify_pipelineuser.php?contactid="+params.data.contactid+"'><img src='../../images/edit_icon_black.png' style='height:20px;width:20px;'></img></a>"
            }

            function editReminderRenderer(params){
                return "<a target='_blank' href='modify_reminder.php?reminderid="+params.data.reminderid+"'><img src='../../images/edit_icon_black.png' style='height:20px;width:20px;'></img></a>"
            }

            function ValidateFormReminder() {
                var reminderdatetime = $("#reminderdatetime").val();
                var content = jQuery("#content").val();
                var contact = jQuery("#contact").val();
                if (reminderdatetime != "" && content != "" && contact != "") {
                    var data = $("#reminderform").serialize();
                    data += "&action=addPipelineReminder";
                    jQuery.ajax({
                        type: "POST",
                        url: "user_ajax.php",
                        cache: false,
                        data: data,
                        success: function (res) {
                            if (res == 'ok') {
                                window.location.reload();
                            }
                        }
                    });
                } else {
                    alert("Please check all fields");
                    return false;
                }
            }
                    
            function ValidateFormUser() {
                var username = jQuery("#username").val();
                var userdesignation = jQuery("#userdesignation").val();
                var userphone = jQuery("#userphone").val();
                var useremail = jQuery("#useremail").val();
                if (username == "") {
                    alert("Please enter username");
                    return false;
                } else if (userphone == "") {
                    alert("Please enter phoneno");
                    return false;
                } else if (useremail == "") {
                    alert("Please enter emailaddress");
                    return false;
                } else {
                    var data = jQuery("#addpipeusers").serialize();
                    data += "&action=addPipelineUser";
                    jQuery.ajax({
                        type: "POST",
                        url: "user_ajax.php",
                        cache: false,
                        data: data,
                        success: function (res) {
                            if (res == 'ok') {
                                window.location.reload();
                            }
                        }
                    });
                }
            }
                    
            function checkEmail() {
                var email = jQuery("#useremail").val();
                var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
                if (pattern.test(email)) {
                    return true;
                } else {
                    alert("Enter valid email id");
                    return false;
                }
            }
                    
            function onlyNos(e, t) {
                try {
                    if (window.event) {
                        var charCode = window.event.keyCode;
                    }
                    else if (e) {
                        var charCode = e.which;
                    }
                    else {
                        return true;
                    }
                    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                        return false;
                    }
                    return true;
                }
                catch (err) {
                    alert(err.Description);
                }
            }
                    
            function deleteReminder(reminderid) {
                jQuery.ajax({
                    type: "POST",
                    url: "user_ajax.php",
                    cache: false,
                    data: {
                        reminderid: reminderid
                        , action: 'deletereminder'
                    },
                    success: function (res) {
                        if (res == 'ok') {
                            window.location.reload();
                        }
                    }
                });
            }
                    
            function deleteUser(contactid) {
                jQuery.ajax({
                    type: "POST",
                    url: "user_ajax.php",
                    cache: false,
                    data: {
                        contactid: contactid
                        , action: 'deleteuser'
                    },
                    success: function (res) {
                        if (res == 'ok') {
                            window.location.reload();
                        }
                    }
                });
            }
        </script>

        <script>
            jQuery.ajax({
                type: "POST",
                url: "Docket_functions.php",
                data: "getProducts=1",
                success: function(data){
                    var data=JSON.parse(data);
                    jQuery('#productId').html("");
                    //<-------- add this line
                    jQuery.each(data ,function(i,text){
                    jQuery('#productId').append("<li ><label><input type='checkbox' class='checkbox1' id='checkbox_"+text.prodName+"' name='checkbox_"+text.prodName+"' value='"+text.prodId+"'>"+text.prodName+"<label></li>");
                    });
                    var array = <?php echo json_encode($productList);?>;
                    jQuery.each(array,function(i,product){
                        var options = jQuery("#productId").find('input').filter("[value="+product+"]");
                        //console.log(options);
                        options.prop('checked','true');
                        // options.prop('disabled','true');
                    });
                }
            });

            var flag=0;
            jQuery(".dropdown1 dt a").on('click', function() {
                jQuery(".dropdown1 dd ul").slideToggle('fast'); 
                if(flag==0){
                    jQuery("#arrowdown").hide();
                    jQuery("#arrowup").show();
                    flag=1;
                }
                else if(flag==1){
                    jQuery("#arrowdown").show();
                    jQuery("#arrowup").hide();
                    flag=0;
                }
            });

            jQuery('#ProductDropdown').on('click', function() {
                var chkd = jQuery('input:checkbox:checked');
                products='';
                jQuery.each(chkd,function(i,checkbox){
                  if(products==''){
                    if(!(products.includes(checkbox.value))){
                      products = checkbox.value;
                    }
                  }else{
                    products += ","+checkbox.value;
                  }
                });

                jQuery("#productSelected").val(products);
                console.log(products);
            });
        </script>

        <?php
        include("footer.php");
    } 
    else {
        header("location:sales_pipeline.php");
    }
}
?>
