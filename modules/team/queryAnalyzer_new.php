<?php
include("header.php");
$today = date("d-m-Y");
?>
<style>
    #dataQuery{
        background: url(../../images/xls.gif);
        /*border: 1px solid black;*/
        display: block;
        height: 33px;
        width: 33px;
        background-color: transparent;
    }
    .space{
        width: 10px;
    }
    table tr td {
        vertical-align: middle !important; 
        font-weight: 600;
        font-size:12px;
    }
</style>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
<div class="panel">
     <span style="float:right;">
        <i class="far fa-window-maximize" title="maximize" id="maximize_table" onclick="maximize_table();" style="color:#fff;"></i>
        &nbsp;
        <i class="fas fa-times" title="close" id="minimize_table" onclick="minimize_table();" style="color:#fff;"></i>
        &nbsp;
    </span>
    <div class="paneltitle" align="center">Query Analyzer</div>
    
    <div class="panelcontents">
        <form name="queryAnalyzer" id="queryAnalyzer" method="POST">
           
            <table class="queryAnalyzerTable" id="queryAnalyzerTable">
                <tr>
                    <td>
                        <?php
                        if (isset($error)) {
                            echo '<p><span style="color:red">' . $error . '</span></p>';
                        }
                        ?>
                        <span id="error" style="display: none;font-size: 15px;color: red;"></span>
                        <span id="error1" style="display: none;font-size: 15px;color: red;">Enter Correct Customer Number.</span>
                    </td>
                    <td><span id="error2" style="display: none;font-size: 15px;color: red;">Enter Correct Unit Number.</span>
                    </td>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td><input  type="text" name="customername" id="customername" size="20" value="<?php if (isset($customername)) { echo $customername; } ?>" autocomplete="off" placeholder="Enter Customer Name"  onkeypress="getCustomer();"/>
                        <input type="hidden" id="customerno" name="customerno" value="<?php if (isset($customerno)) { echo $customerno; } ?>"/>
                    </td>
                    <td class="space"></td>
                    <td>Vehicle No</td>
                    <td>
                        <input  type="text" name="vehicleno" id="vehicleno" size="20" value="<?php if (isset($vehicleno)) { echo $vehicleno; } ?>" autocomplete="off" placeholder="Enter Vehicle No" onkeypress="getVehicle();"/></td>
                </tr>
                <tr>
                    <td>Unit No</td>
                    <td><input  type="text" name="unitno" id="unitno" size="20" value="<?php if (isset($unitno)) { echo $unitno; } ?>" autocomplete="off" placeholder="Enter Unit No" onkeypress="getUnit();"/>
                        <input type="hidden" name="unitid" id="unitid" value="<?php if (isset($unitid)) { echo $unitid; } ?>" />
                    </td>
                    <td class="space"></td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td><input  type="text" name="startdate" id="startdate" size="10" value="<?php
                                if (isset($SDate)) {
                                    echo $SDate;
                                } else {
                                    echo $today;
                                }
                                ?>" /></td>
                    <td class="space"></td>
                    <td>End Date</td>
                    <td><input  type="text" name="enddate" id="enddate" size="10" value="<?php
                        if (isset($EDate)) {
                            echo $EDate;
                        } else {
                            echo $today;
                        }
                        ?>" /></td><td></td>
                    <td class="space"></td>
                </tr>
                <tr>
                    <td><input type="button" id="query" name="query" onclick="return validate();" value="SUBMIT"/>
                    </td>
                    <td><div name="dataQuery" id="dataQuery" onclick="excelDownload();"></div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<br>
<div id="myGRID" class="ag-theme-blue" style="height:800px;width:100%;margin:0 auto;">
</div>

<?php
include("footer.php");
?>

<script>
    
    function validate() {
        var customerno = $('#customernoname').val();
        var unitno = $('#unitno').val();
        if (customerno == '' || customerno == '0') {
            $('#error1').fadeIn(2000);
            $('#error1').fadeOut(2000);
            return false;
        } else if (unitno == '' || unitno == '') {
            $('#error2').fadeIn(2000);
            $('#error2').fadeOut(2000);
            return false;
        } else {
            var final_data = $("#queryAnalyzer").serialize();

            jQuery.ajax({
                type: "POST",
                url: "query_analyzer_functions.php",
                data: "query=1&"+final_data,
                success: function(data){
                 result = JSON.parse(data);
                console.log(result);
                gridOptions.api.setRowData(result);
                    
                }
            });
        }
    }
    function getUnit() {
        var data = $('#customerno').val();

        jQuery("#unitno").autocomplete({
            source: "route_ajax.php?unitno=" + data,
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery("#unitid").val(ui.item.uid);
                jQuery("#vehicleno").val(ui.item.vehicleno);
            }
        });
    }
    function getVehicle() {
        var data = $('#customerno').val();
        jQuery("#vehicleno").autocomplete({
            source: "route_ajax.php?vehicleno=" + data,
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#unitno').val(ui.item.uid);
                jQuery('#unitid').val(ui.item.unitid);             
            }
        });
    }
    function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
                jQuery("#unitno").val("");
                jQuery("#vehicleno").val("");
            }
        });
    }
    $("#startdate").datepicker({dateFormat: 'dd-mm-yy'});
    $("#enddate").datepicker({dateFormat: 'dd-mm-yy'});
</script>


<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
    var result=[];
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
        
        
       
                          columnDefs = [

                            {headerName:'Lattitude',field: 'devicelat',width:120,filter: 'agTextColumnFilter'},
                            {headerName:'Longitude',field: 'devicelong',width:120,filter: 'agTextColumnFilter'},
                            {headerName:'Last Updated',field: 'lastupdated',width:180,filter: 'agTextColumnFilter'},
                            {headerName:'Int-Batt(V)',field: 'inbatt',width:100,filter: 'agTextColumnFilter',hide:true},
                            {headerName:'Status',field: 'status',width:80,filter: 'agTextColumnFilter'},
                            {headerName:'Ignition',field: 'ignition',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'Power Cut',field: 'powercut',width:110,filter: 'agTextColumnFilter'},
                            {headerName:'Tamper',field: 'tamper',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'Gps fixed',field: 'gpsfixed',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'On/Off',field: 'onoff',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'Gsm Strength',field: 'gsmstrength',width:150,filter: 'agTextColumnFilter'},
                            {headerName:'Gsm Register',field: 'gsmregister',width:150,filter: 'agTextColumnFilter'},
                            {headerName:'Gprs Register',field: 'gprsregister',width:150,filter: 'agTextColumnFilter'},
                            {headerName:'S/W Version',field: 'swv',width:120,filter: 'agTextColumnFilter'},
                            {headerName:'H/W Version',field: 'hwv',width:120,filter: 'agTextColumnFilter'},
                            {headerName:'analog 1',field: 'analog1',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'analog 2',field: 'analog2',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'analog 3',field: 'analog3',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'analog 4',field: 'analog4',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'Digital I/O',field: 'digitalio',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'Command Key',field: 'commandkey',width:150,filter: 'agTextColumnFilter'},
                            {headerName:'Command Key Value',field: 'commandkeyval',width:200,filter: 'agTextColumnFilter'},
                            {headerName:'Ext Batt',field: 'extbatt',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'Odometer',field: 'odometer',width:100,filter: 'agTextColumnFilter'},
                            {headerName:'Speed',field: 'curspeed',width:100,filter: 'agTextColumnFilter'}
                        ];
                        
                            var gridOptions;
                            gridOptions = {
                                enableFilter:true,
                                enableSorting: true,
                                animateRows:true,
                                floatingFilter:true, 
                                rowData:result,
                                columnDefs: columnDefs,
                                masterDetail: true
                            };
                             
                             //gridOptions.rowHeight = 20;
                             var gridDiv = document.getElementById('myGRID');
                             new agGrid.Grid(gridDiv,gridOptions); 


    function excelDownload(){
        gridOptions.api.exportDataAsExcel();
    }


    function minimize_table(){
      $("#queryAnalyzerTable").hide(300);
    }
    function maximize_table(){
      $("#queryAnalyzerTable").show(300);
    }
</script>



