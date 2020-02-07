<?php
set_time_limit(180);
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/modules/team/bootstrap.css" type="text/css" />';
?>
<script>
     $(document).ready(function(){
    //$("#divheader").css('display','none');
    $(".page-header").html("<span style='font-size:28px; '>Managment Dashboard</span>");
    $('.container').focus();
});
</script>
<style type="text/css">
    #pageloaddiv12 {
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 1000;
    background: url('../../images/progressbar.gif') no-repeat;
  }
</style>
<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'modules/reports/reports_stoppage_functions.php';
include_once $RELATIVE_PATH_DOTS . 'modules/tms/class/model/Vehicle.php';
include_once $RELATIVE_PATH_DOTS . 'css/dashboard.css';
$tripDashboard = new Trips($customerno, $userid);
$request = new stdClass();
$request->customerno = $_SESSION['customerno'];
$request->currentDate = date('Y-m-d');
$tripDashboardData = $tripDashboard->getDashboardTripDetails($request);
$request->vehicleType = 'emptyR';
//$tripDashboardEmptyReturnData = $tripDashboard->getDashboardVehicleETADetails($request);
$request->vehicleType = 'tripEnd';
//$tripDashboardwaitingForTripData = $tripDashboard->getDashboardVehicleETADetails($request);
$disptachVolumeCount = isset($tripDashboardData['disptachVolumeCount']) ? $tripDashboardData['disptachVolumeCount'] : 0;
$lrDelayCount = isset($tripDashboardData['lrDelayCount']) ? $tripDashboardData['lrDelayCount'] : 0;
$loadingVehiclesCount = isset($tripDashboardData['loadingVehiclesCount']) ? $tripDashboardData['loadingVehiclesCount'] : 0;
$intransitVehiclesCount = isset($tripDashboardData['intransitVehiclesCount']) ? $tripDashboardData['intransitVehiclesCount'] : 0;
$availableVehiclesCount = isset($tripDashboardData['availableVehiclesCount']) ? $tripDashboardData['availableVehiclesCount'] : 0;
$yardDetentionDeviationCount = isset($tripDashboardData['yardDetentionDeviationCount']) ? $tripDashboardData['yardDetentionDeviationCount'] : 0;
$emptyReturnCount = isset($tripDashboardData['emptyReturnCount']) ? $tripDashboardData['emptyReturnCount'] : 0;
$emptyReturnDeviationCount = isset($tripDashboardData['emptyReturnDeviationCount']) ? $tripDashboardData['emptyReturnDeviationCount'] : 0;
$totalWaitingForTripEnd = isset($tripDashboardData['waitingForTripEnd']) ? $tripDashboardData['waitingForTripEnd'] : 0;
?>
<div class="page">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
            <div style="margin-left:-65%;margin-bottom:15px;">Refresh time interval&nbsp;
            <input type="text" id="refreshInterval" value="5" readonly>
                <!-- <select id="refreshInterval" onchange="refreshInterval1(this.value, 'trips')">
                        <option value="">Select Interval</option>
                    <option value="3">3</option>
                    <option value="5" selected="" readonly>5</option>
                    <option value="10">10</option>
                </select> -->
                <span id="time" style="padding-left: 10px;"></span> min
            </div>
        </div>
    </div>
    <div class="row">
        <!-- /.row -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-2  col-md-6 col-lg-offset-1">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div id="disptachVolumeCount" class="huge" style="font-size:65px;font-weight:100"><?php echo $disptachVolumeCount; ?></div>
                                    <div style="font-weight:bold;">Volume Dispatched Today</div>
                                </div>
                            </div>
                        </div>
                        <a href="">
                            <div class="panel-footer">
                                <a href="trips.php?pg=volumeDispatched" target="_blank">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </a>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div id="lrDelayCount" class="huge" style="font-size:65px;font-weight:100"><?php echo $lrDelayCount; ?></div>
                                    <div style="width:120px;font-weight:bold;">LR  Delayed  Till Date</div>
                                </div>
                            </div>
                        </div>
                        <a href="">
                            <div class="panel-footer">
                                <a href="trips.php?pg=lrDelayed" target="_blank">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </a>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="panel panel-greend">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div id="yardDetentionDeviationCount" class="huge" style="font-size:65px;font-weight:100"><?php echo $yardDetentionDeviationCount; ?></div>
                                    <div style="font-weight:bold;"> Yard Detention Deviation</div>
                                </div>
                            </div>
                        </div>
                        <a href="">
                            <div class="panel-footer">
                                <a href="trips.php?pg=yardDetentionDeviation" target="_blank">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </a>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                <a onclick="getStoppageTransitCount()" style="cursor:pointer;float:right;"><i class="fa fa-refresh" style="color:white;"></i></a>
                                    <div id="inTransitStoppageCount" class="huge" id="inTransitStoppageCount" style="font-size:65px;font-weight:100"><div id="pageloaddiv12" style='display:none;'></div></div>
                                    <div style=" font-weight:bold;">In Transit Stoppage Count</div>
                                </div>
                            </div>
                        </div>
                        <a href="">
                            <div class="panel-footer">
                                <a href="trips.php?pg=inTransitStoppage" target="_blank">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </a>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="panel panel-perple">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div id="emptyReturnDeviationCount" class="huge" style="font-size:65px;font-weight:100"><?php echo $emptyReturnDeviationCount; ?></div>
                                    <div style="font-weight:bold;"> Empty Return Deviation</div>
                                </div>
                            </div>
                        </div>
                        <a href="">
                            <div class="panel-footer">
                                <a href="trips.php?pg=emptyReturnDeviation" target="_blank">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </a>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-lg-offset-1">
            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto;margin-left:0px;">
            </div>
        </div>
        <div class="col-lg-6">
        </div>
    </div>
</div>
<script src="../../scripts/highcharts/js/highcharts.v6.1.1.js"></script>
<script src="../../scripts/highcharts/js/data.js"></script>
<script src="../../scripts/highstock/modules/exporting.js"></script>
<script type="text/javascript">
var loadingVehiclesCount        = <?php echo $loadingVehiclesCount; ?>;
var intransitVehiclesCount      = <?php echo $intransitVehiclesCount; ?>;
var emptyReturnCount            = <?php echo $emptyReturnCount; ?>;
var totalWaitingForTripEnd      = <?php echo $totalWaitingForTripEnd; ?>;
var availableVehiclesCount      = <?php echo $availableVehiclesCount; ?>;
$(document).ready(function(){
    $('.ag-paging-button').css('color','#000000');
    $('div#inTransitStoppageCount').html('0');
 //   getStoppageTransitCount();
});
function getStoppageTransitCount(){
    jQuery("#pageloaddiv12").show();
    $.ajax({
       url   : "trip_ajax.php",
       type  : "POST",
       data  : {action : 'getStoppgeDetails' },
       cache : false,
       success: function(data){
        result  = JSON.parse(data);
            var vehicleInstransitStoppageCount = 0;
            vehicleInstransitStoppageCount  = result.data;
            $('div#inTransitStoppageCount').html(vehicleInstransitStoppageCount);
            jQuery("#pageloaddiv12").hide();
        }
    });
}
function getVehicleStatusTable(loadingVehiclesCount,intransitVehiclesCount,emptyReturnCount,totalWaitingForTripEnd,availableVehiclesCount){
   Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Vehicle Status'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
           /* point : {
                    events: {
                        click: function() {
                            var type = this.options.type;
                            vehicleListTable(type);
                        }
                    }
                },*/
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y} ',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Vehicle Count',
        colorByPoint: true,
        data: [{
            name: 'Loading',
            y:                             loadingVehiclesCount,
            sliced: true,
            selected: true,
               type: 'Load'
        }, {
            name: 'In Transit',
            y:                             intransitVehiclesCount,
               type: 'Intrans'
        }, {
            name: 'Empty Return',
            y:                             emptyReturnCount,
               type: 'emptyR'
        }, {
            name: 'Waiting for Trip End',
            y:                            totalWaitingForTripEnd,
               type: 'tripEnd'
        }, {
            name: 'Available',
            y:                             availableVehiclesCount,
            type: 'Avail'
        }, {
            name: 'Under Maintenance',
            y: 0,
               type: 'UM'
        }]
    }]
});
}
// Build the chart
$(function () {
    getVehicleStatusTable(loadingVehiclesCount,intransitVehiclesCount,emptyReturnCount,totalWaitingForTripEnd,availableVehiclesCount);
});
function vehicleListTable(type){
        $.ajax({
            url   : "../vehicle/vehicle_functions.php",
            type  : "POST",
            data  : {vehicleType : type },
            cache : false,
            success: function(data){
                if(type == 'Avail'){
                    $("#vehicleType").html('Available');
                }
                else if(type == 'Intrans'){
                    $("#vehicleType").html('In Transit');
                }
                else if(type == 'Load'){
                    $("#vehicleType").html('Loading');
                }
                else if(type== 'emptyR'){
                    $("#vehicleType").html('Empty Return');
                }
                else if(type == 'tripEnd'){
                    $("#vehicleType").html('Waiting for Trip End');
                }
                else if(type == 'UM'){
                    $("#vehicleType").html('Under Maintenance');
                }
                result           = JSON.parse(data);
                customColumns    = generateColumns(result);
                gridOptions.api.sizeColumnsToFit();
                gridOptions.api.setColumnDefs(customColumns);
                gridOptions.api.setRowData(result);
            }
        });
}
</script>
