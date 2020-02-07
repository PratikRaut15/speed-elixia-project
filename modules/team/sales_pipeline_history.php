<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Sanitise.php");
$_scripts[] = "../../scripts/trash/prototype.js";
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
                            ,t.name as responsible 
                            ,pr.product_name
                            ,it.industry_type                            
                            ,st.stage_name
                    FROM    " . DB_PARENT . ".sales_pipeline_history as sp 
                    INNER JOIN team as t ON sp.teamid = t.teamid
                    INNER JOIN sales_stage as st ON st.stageid = sp.stageid
                    INNER JOIN sales_product as pr ON pr.productid = sp.productid                    
                    INNER JOIN sales_industry_type as it ON it.industryid = sp.industryid                                        
                    WHERE   sp.isdeleted = 0 %s ORDER BY sp.timestamp DESC", Sanitise::String($queryadd));
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
            $userdetails->product = $row["product_name"];            
            $userdetails->industry = $row["industry_type"];                        
            $userdetails->timestamp = date("d-m-Y",strtotime($row["timestamp"]));
            $userdetails->pipelineid = $row["pipelineid"];
            $userdetails->responsible = $row["responsible"];
            $userdetails->stage = $row["stage_name"];            
            $userdetails->assignto = get_teamname($row["teamid"]);
            $teamid = $_SESSION['sessionteamid'];
            $delete_url = "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" . $pipelineid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
            $userdetails->delete_url = $delete_url;
            $details[] = $userdetails;
            $x++;
        }
    }
    $dg = new objectdatagrid($details);
    $dg->AddAction("View/Edit", "../../images/edit.png", "modify_pipeline.php?pipelineid=%d");
    $dg->AddColumn("Sr. No.", "srno");    
    $dg->AddColumn("Pipeline ID", "pipelineidx");
    $dg->AddColumn("Prospect Identified On", "pipeline_date");
    $dg->AddColumn("Company Name", "company_name");
    $dg->AddColumn("Industry", "industry");        
   $dg->AddColumn("Product", "product");    
    $dg->AddColumn("Stage", "stage");    
    $dg->AddColumn("Responsible", "responsible");        
    $dg->AddColumn("Updated On", "timestamp");
    $dg->AddColumn("Delete", "delete_url");
    $dg->SetNoDataMessage("No Pipeline Created");
    $dg->AddIdColumn("pipelineid");
    include("header.php");
    ?>
  
    <br/>
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center">My History</div>
        <div class="panelcontents">
            <?php $dg->Render(); ?>
        </div>
    </div>
    <br/>
    <script type="text/javascript">

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

        function ValidateForm() {
            var pipelinedate = $("#pipelinedate").val();
            var contactperson = $("#contactperson").val();
            var emailaddress = $("#emailaddress").val();
            var contactno = $("#contactno").val();
            if (pipelinedate == "") {
                alert("Please select pipelinedate");
                return false;
            } else if (contactperson == "") {
                alert("Please enter contact person name");
                return false;
            } else if (emailaddress == "") {
                alert("Please enter emailaddress");
                return false;
            } else if (contactno == "") {
                alert("Please enter contact no");
                return false;
            } else {
                $("#pipelineform").submit();
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

        function deletepipeline(pipelineid) {
            jQuery.ajax({
                type: "POST",
                url: "user_ajax.php",
                cache: false,
                data: {
                    pipelineid: pipelineid
                    , action: 'deletepipeline'
                },
                success: function (res) {
                    if (res == 'ok') {
                        window.location.reload();
                    }
                }
            });
        }
    </script>
    <?php
    include("footer.php");
}
?>