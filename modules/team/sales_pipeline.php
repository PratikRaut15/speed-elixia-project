<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
// See if we need to save a new one.
if (IsHead() || IsSales()) {


    function get_teamname($id) {
        $db = new DatabaseManager();
        $SQL = sprintf("select name from " . DB_PARENT . ".team where teamid=" . $id);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $teamname = $row["name"];
        }
        return $teamname;
    }

    $teamid = $_SESSION['sessionteamid'];
    $today = date("Y-m-d H:i:s");
    $message = "";
    $pipelinedate = "";
    $companyname = "";
    $location = "";
    $designation = "";
    $contactperson = "";
    $emailaddress = "";
    $contactno = "";
    $remarks = "";
    if (isset($_POST["submitpipeline"])) {
        $db = new DatabaseManager();
        $pipelinedate = GetSafeValueString($_POST["pipelinedate"], "string");
        $companyname = GetSafeValueString($_POST["companyname"], "string");
        $source = GetSafeValueString($_POST["source"], "string");
        $product = GetSafeValueString($_POST["product"], "string");
        $industry = GetSafeValueString($_POST["industry"], "string");
        $mode = GetSafeValueString($_POST["mode"], "string");
        $location = GetSafeValueString($_POST["location"], "string");
        $designation = GetSafeValueString($_POST["designation"], "string");
        $contactperson = GetSafeValueString($_POST["contactperson"], "string");
        $emailaddress = GetSafeValueString($_POST["emailaddress"], "string");
        $contactno = GetSafeValueString($_POST["contactno"], "string");
        $remarks = GetSafeValueString($_POST["remarks"], "string");
        $assignto = GetSafeValueString($_POST["teamid"], "string");
        $assignto = isset($assignto) ? $assignto : 0;
        $stageid = 1;
        if ($pipelinedate != "") {
            $pipelinedate = date('Y-m-d', strtotime($pipelinedate));
        } else {
            $pipelinedate = date('Y-m-d');
        }

        $sqlQuery = sprintf("INSERT INTO " . DB_PARENT . ".`sales_pipeline`(
            `pipeline_date`,
            `company_name`,
            `sourceid`,
            `productid`,
            `industryid`,
            `modeid`,
            `teamid`,
            `location`,
            `remarks`,
            `stageid`,
            `timestamp`,
            `teamid_creator`
            )
            VALUES (
             '%s','%s',%d,%d,%d,%d,%d,'%s','%s',%d,'%s',%d);
             ", $pipelinedate, $companyname, $source, $product, $industry, $mode, $assignto, $location, $remarks, $stageid, $today, $teamid);

        $db->executeQuery($sqlQuery);

        $mainid = $db->get_insertedId();

        $sqlQuery = sprintf("INSERT INTO " . DB_PARENT . ".`sales_pipeline_history`(
            `pipelineid`,
            `pipeline_date`,
            `company_name`,
            `sourceid`,
            `productid`,
            `industryid`,
            `modeid`,
            `teamid`,
            `location`,
            `remarks`,
            `stageid`,
            `timestamp`,
            `teamid_creator`
            )
            VALUES (
             %d,'%s','%s',%d,%d,%d,%d,%d,'%s','%s',%d,'%s',%d);
             ", $mainid, $pipelinedate, $companyname, $source, $product, $industry, $mode, $assignto
                , $location, $remarks, $stageid, $today, $teamid);
        $db->executeQuery($sqlQuery);


        $sqlContactQuery = sprintf("INSERT INTO " . DB_PARENT . ".`sales_contact` (
        `pipelineid` ,
        `designation` ,
        `name` ,
        `phone` ,
        `email` ,
        `timestamp` ,
        `teamid_creator`
        )
        VALUES (
            %d,'%s','%s','%s','%s','%s',%d
        );", $mainid, $designation, $contactperson, $contactno, $emailaddress, $today, $teamid);
        $db->executeQuery($sqlContactQuery);

        function getsourcename($source) {
            $sourcename = "";
            $db = new DatabaseManager();
            $SQL = sprintf("select source_name from " . DB_PARENT . ".sales_source where sourceid=" . $source);
            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow()) {
                $sourcename = $row["source_name"];
            }
            return $sourcename;
        }

        function getproductname($product) {
            $db = new DatabaseManager();
            $SQL = sprintf("select product_name from " . DB_PARENT . ".sales_product where productid=" . $product);
            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow()) {
                $product_name = $row["product_name"];
            }
            return $product_name;
        }

        function getindustry($industry) {
            $db = new DatabaseManager();
            $SQL = sprintf("select industry_type from " . DB_PARENT . ".sales_industry_type where industryid=" . $industry);
            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow()) {
                $industry_type = $row["industry_type"];
            }
            return $industry_type;
        }

        function getmode($mode) {
            $db = new DatabaseManager();
            $SQL = sprintf("select mode from " . DB_PARENT . ".sales_mode where modeid=" . $mode);
            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow()) {
                $mode = $row["mode"];
            }
            return $mode;
        }

        function getteam($teamid) {
            $db = new DatabaseManager();
            $SQL = sprintf("select name from " . DB_PARENT . ".team where teamid=" . $teamid);
            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow()) {
                $name = $row["name"];
            }
            return $name;
        }

        function getstage($stageid) {
            $db = new DatabaseManager();
            $SQL = sprintf("select stage_name from " . DB_PARENT . ".sales_stage where stageid=" . $stageid);
            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow()) {
                $stage_name = $row["stage_name"];
            }
            return $stage_name;
        }

        function send_mail($data) {
            $pipelineid = $data['pipelineid'];
            $to = array('sanketsheth@elixiatech.com');
            $subject = "New sales order -(Orderid:" . $pipelineid . ") ";
            $strCCMailIds = "";
            $strBCCMailIds = "sanketsheth1@gmail.com";
            $attachmentFilePath = "";
            $attachmentFileName = "";
            $message = "";
            $message = "<table border='1'>";
            $message .= "<tr><td>Pipelineid</td><td>" . $data['pipelineid'] . "</td></tr>";
            $message .= "<tr><td>Pipeline Date</td><td>" . $data['pipelinedate'] . "</td></tr>";
            $message .= "<tr><td>Company Name</td><td>" . $data['companyname'] . "</td></tr>";
            $message .= "<tr><td>Source </td><td>" . $data['source'] . "</td></tr>";
            $message .= "<tr><td>Product</td><td>" . $data['product'] . "</td></tr>";
            $message .= "<tr><td>industry</td><td>" . $data['industry'] . "</td></tr>";
            $message .= "<tr><td>Mode</td><td>" . $data['mode'] . "</td></tr>";
            $message .= "<tr><td>Team</td><td>" . $data['team'] . "</td></tr>";
            $message .= "<tr><td>Location</td><td>" . $data['location'] . "</td></tr>";
            $message .= "<tr><td>Remarks</td><td>" . $data['remarks'] . "</td></tr>";
            $message .= "<tr><td>Stage </td><td>" . $data['stage'] . "</td></tr>";
            $message .= "<tr><td>Designation </td><td>" . $data['designation'] . "</td></tr>";
            $message .= "<tr><td>Contact Person </td><td>" . $data['contactperson'] . "</td></tr>";
            $message .= "<tr><td>Contact No </td><td>" . $data['contactno'] . "</td></tr>";
            $message .= "<tr><td>Contact Email id </td><td>" . $data['emailid'] . "</td></tr>";
            $message .= "</table>";
            sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        }

        if ($assignto == 0 || $assignto == "") {
            $assignto = $teamid;
        }


        $maildata = array(
            'pipelineid' => $mainid,
            'pipelinedate' => $pipelinedate,
            'companyname' => $companyname,
            'source' => getsourcename($source),
            'product' => getproductname($product),
            'industry' => getindustry($industry),
            'mode' => getmode($mode),
            'team' => getteam($assignto),
            'location' => $location,
            'remarks' => $remarks,
            'stage' => getstage($stageid),
            'date' => $today,
            'designation' => $designation,
            'contactperson' => $contactperson,
            'contactno' => $contactno,
            'emailid' => $emailaddress
        );
        send_mail($maildata);
    }


    ///get all stages 
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
    $stageid = 1;

    //view pipe line list 
    $queryadd = "";
    if (!IsHead()) {
        $queryadd = " AND sp.teamid =" . $_SESSION['sessionteamid'];
    }


    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  sp.pipelineid
                            ,sp.pipeline_date
                            ,sp.company_name
                            ,sp.sourceid
                            ,sp.productid
                            ,sp.industryid
                            ,sp.modeid
                            ,sp.teamid
                            ,sp.location
                            ,sp.remarks
                            ,sp.stageid
                            ,sp.timestamp
                            ,t.name as createdby 
                    FROM    " . DB_PARENT . ".sales_pipeline as sp 
                    INNER JOIN team as t ON sp.teamid_creator = t.teamid   
                    WHERE   sp.isdeleted = 0 %s", Sanitise::String($queryadd));
    $db->executeQuery($SQL);
    $details = array();
    if ($db->get_rowCount() > 0) {
        $x = 1;
        $delete_url = "";
        while ($row = $db->get_nextRow()) {
            $userdetails = new stdClass();
            $userdetails->pipelineidx = "P00" . $row["pipelineid"];
            $userdetails->srno = $x;
            $userdetails->pipeline_date = date('d-m-Y', strtotime($row["pipeline_date"]));
            $userdetails->company_name = $row["company_name"];
            $userdetails->location = $row["location"];
            $userdetails->remarks = $row["remarks"];
            $userdetails->timestamp = $row["timestamp"];
            $userdetails->pipelineid = $row["pipelineid"];
            $userdetails->createdby = $row["createdby"];
            $userdetails->assignto = get_teamname($row["teamid"]);
            $teamid = $_SESSION['sessionteamid'];
            $delete_url = "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" . $pipelineid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
            $userdetails->delete_url = $delete_url;
            $details[] = $userdetails;
            $x++;
        }
    }
    include("header.php");
    ?>
        <style>

        </style>
        <link rel="stylesheet" href="../../css/sales_pipeline.css">
    <div class="panel">
        <div class="paneltitle" align="center">Sales Pipeline</div>
        <div class="panelcontents">
            <form method="post"  name="pipelineform" id="pipelineform" enctype="multipart/form-data">
                      <?php echo($message); ?>
                <table width="100%">
                    <tr>
                        <?php $today = date('d-m-Y'); ?>
                        <td>Prospect Identified On <span style="color:red;">*</span></td><td><input id="pipelinedate" name = "pipelinedate" value="<?php echo $today; ?>" type="text" required></td>
                        <td>Company Name </td><td><input id="companyname" name = "companyname" type="text" placeholder="Company Name" ></td></tr>    
                    <tr><td>Contact Person <span style="color:red;">*</span></td>
                        <td>
                            <input type="text" name="contactperson" id="contactperson" required placeholder="Person Name"/>
                        </td>
                        <td>Designation</td>
                        <td>
                            <input type="text" name="designation" id="designation" placeholder="Eg.Sales Officer"/>
                        </td>
                    </tr>
                    <tr><td>Email Address <span style="color:red;">*</span></td>
                        <td>
                            <input type="text" name="emailaddress" id="emailaddress" required onblur="checkEmail();" placeholder="abc@gmail.com"/>
                        </td>
                        <td>Contact No. <span style="color:red;">*</span></td>
                        <td>
                            <input type="text" name="contactno" id="contactno" onkeyup="onlyNos();" placeholder="1234567890"/>
                        </td>
                    </tr>                    
                    <tr><td>Source </td>
                        <td>
                            <select name="source" id="source">
                                <?php
                                foreach ($sourcedata as $row) {
                                    ?>
                                    <option value="<?php echo $row->sourceid; ?>"><?php echo $row->source_name; ?></option>
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
                                     Product  <span style="color:red;">*</span><i id="arrowdown" class='material-icons'>
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
                            <input type="hidden" name="productSelected" id="productSelected">
                        </td>
                    </tr>
                    <tr>
                        <td>Industry Type </td>
                        <td>
                            <select name="industry" id="industry">
                                <?php
                                foreach ($industrydata as $row2) {
                                    ?>
                                    <option value="<?php echo $row2->industryid; ?>"><?php echo $row2->industry_type; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>Mode </td>
                        <td>
                            <select name="mode" id="mode">
                                <?php
                                foreach ($modedata as $row3) {
                                    ?>
                                    <option value="<?php echo $row3->modeid; ?>"><?php echo $row3->mode; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td>
                            <input type="text" name="location" id="location" placeholder="Eg. Ghatkopar, Mumbai"/>
                        </td>
                        <td>Tepidity</td>
                        <td>
                            <select id='tepidity' name='tepidity'>
                            <?php foreach ($tepidityData as $tepidity){?>      
                                <option value="<?php echo $tepidity->tepidityId; ?>"><?php echo $tepidity->tepidityName; ?></option>
                            <?php } ?>
                            </select>
                        </td>

                    </tr>                    
                    <tr>
                        <td>
                           Quantity 
                       </td><td><input type='text' id='quantity' name='quantity' value=0>
                        </td>
                        <td>Remarks
                        </td>
                        <td>
                            <textarea name="remarks" id="remarks" placeholder="Enter Remarks"></textarea>
                        </td>
                        <?php if (IsHead()) { ?>
                            <td>Assign To </td>
                            <td><input  type="text" name="teamalloted" id="teamalloted" size="20" value="" autocomplete="off" placeholder="Enter Team member" onkeyup="getTeam()" />
                                <input type="hidden" id="teamid" name="teamid">
                            </td>
                        <?php } else { ?>
                        <input type="hidden" id="teamid" name="teamid" value="<?php echo $teamid; ?>">
                    <?php } ?>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="stage" id="stage" value="<?php echo $stageid; ?>"/>
                    </td>
                    <td></td>
                   
                </tr>
                <tr>
                    <td >
                        <label for='quotation'>Quotation</label>
                    </td>
                    <td>
                        <input type='file' id='quotation' name='quotation'>
                    </td>
                </tr>
                <tr>
                    <td >
                        <label for='brd'>BRD</label>
                    </td>
                    <td>
                        <input type='file' id='brd' name='brd'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for='qDetails'>Quotation Details</label>
                    </td>
                    <td>
                        <textarea id='qDetails' name='qDetails' placeholder="Enter Quotation Details"></textarea>
                    </td>
                     <td>
                        <input type="button" id="submitpipeline" name="submitpipeline" style="background-color: #00A5B9; color: white;padding:10px;" value="Submit" onclick='return subimtPipeline();'/>
                    </td>
                </tr>
        </table>
    </form>
    <form id='filesForm' name='filesForm'>
    </form>
    </div>
    </div>
    <br/>
    <br/>

        <?php 
            include('pipelineList.php');
        ?>

    <br/>
   <!--  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> -->

    <script type="text/javascript">
        var details;
        var columnDefs;
        var gridOptions;
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

        $(document).ready(function () {
            $('#pipelinedate').datepicker({
                format: "dd-mm-yyyy",
                language: 'en',
                autoclose: 1
            });
        });

        function subimtPipeline(){
            var pipelinedate = $("#pipelinedate").val();
            var contactperson = $("#contactperson").val();
            var emailaddress = $("#emailaddress").val();
            var contactno = $("#contactno").val();
            var product_selected = $("#productSelected").val();
            if (pipelinedate == "") {
                alert("Please select pipelinedate");
                return false;
            } else if (contactperson == "") {
                alert("Please enter contact person name");
                return false;
                
            }
            else if (emailaddress == "") {
                alert("Please enter Email Id");
                return false;
            }
            
            else if (contactno == "") {
                alert("Please enter contact no");
                return false;
            } 
            else if(product_selected == ""){
                alert("Please Select One Product");
                return false;
            }
            else {
                files = new FormData($('#pipelineform')[0]);
                files.append('submitPipeline',1);
                $.ajax({
                    url: 'pipeline_functions.php',
                    type: 'POST',
                    data:files,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        if($.isNumeric(response)){
                            window.location.reload();
                        }
                    }
                });
            }
        }
        function checkAndUploadFiles(){
            if(($('#quotation').val()!='')||($('#brd').val()!='')){
                var form=$('#filesForm')[0];
                //console.log(form);
                var files = new FormData(form);
            }
        }
        function checkEmail() {
            var email = $("#emailaddress").val();
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


    var flag=0;
    $(".dropdown1 dt a").on('click', function() {
        $(".dropdown1 dd ul").slideToggle('fast'); 
        if(flag==0){
            $("#arrowdown").hide();
            $("#arrowup").show();
            flag=1;
        }
        else if(flag==1){
            $("#arrowdown").show();
            $("#arrowup").hide();
            flag=0;
        }
    });


    jQuery.ajax({
            type: "POST",
            url: "Docket_functions.php",
            data: "getProducts=1",
           success: function(data){
            var data=JSON.parse(data);
            //console.log(data);
                $('#productId').html("");
                $.each(data ,function(i,text){
                        $('#productId').append("<li ><label><input type='checkbox' class='checkbox1' id='checkbox"+i+"' name='checkbox"+i+"' value='"+text.prodId+"'>"+text.prodName+"<label></li>");
                });
            }
          
    });


    $('#ProductDropdown').on('click', function() {
        var chkd = $('input:checkbox:checked');
        products='';
        $.each(chkd,function(i,checkbox){
          if(products==''){
            if(!(products.includes(checkbox.value))){
              products = checkbox.value;
            }
          }else{
            products += ","+checkbox.value;
          }
        });

        $("#productSelected").val(products);
        console.log(products);
    });
    </script>
    <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>

    <?php
    include("footer.php");
}
?>