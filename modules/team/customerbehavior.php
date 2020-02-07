<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//include_once("session.php");

include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/model/VODevices.php");
include_once("../user/new_alerts_func.php");

$_scripts[] = "../../scripts/jquery.min.js";
$_scripts[] = "../../scripts/team/modifycustomer.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";



include("header.php");
?>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<style>
    #invoiceGenerateChart{
        height: 300px; 
        width: 90%;
        margin-top: 50px;
        margin-left: 50px;
    }
    #paidChart{
        height: 300px;
        width: 90%;
        margin-top: 50px;
        margin-left: 50px;
    }
    #creditdays{
        margin-top: 50px;
        margin:0 30% 0 30%;
    }

</style>
<div class="panel">
    <div class="paneltitle" align="center">Customer Behaviour</div>   
    <div class="panelcontents">
        <table>
            <tr>
                <td><h5>Customer</h5></td>
                <td>
                    <input  type="text" name="customername" id="customername" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or number"  onkeypress="getCustomer();"/>
                    <input type="hidden" id="customerno" name="customerno" value=""/>
                </td>
            </tr>

            <tr>
                <td><h5>Ledger Name</h5></td>
                <td>
                    <select id="ledger" name="ledger" onchange="getReport(this.value);">
                        <option value="-1">Select ledger</option>
                    </select>
                </td>

            </tr>
        </table>
    </div>
</div>
<div style="text-align: right;margin-top: 5%;"><span class="pending" style="font-size:17px;display: none;margin-right: 200px">Pending Amount : <span class="pending" id="pending_amount"></span></span></div>
<div style="text-align: right;"><span class="pending" style="font-size:17px;display: none;margin-right: 200px">Average Delay Days : <span class="pending" id="avg_days"></span></span></div>
<div id="paidChart"></div>

<hr>
<div id="invoiceGenerateChart"></div>
<hr>
<div style="text-align: center;"><h3>Credit Day Report</h3></div>
<div id="creditdays"></div><br><br><br>
<?php
include("footer.php");
?>
<script>
    function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                var data = "getLedgerOfCust=" + ui.item.cid;
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);


                jQuery('#ledger')
                        .empty()
                        .append('<option selected="selected" value="-1">Select ledger</option>')
                        ;


                jQuery.ajax({
                    type: "POST",
                    url: "route_ajax.php",
                    cache: false,
                    data: data,
                    dataType: 'JSON',
                    success: function (msg) {
                        jQuery.each(msg, function (i, item) {
                            jQuery('#ledger')
                                    .append(jQuery("<option></option>")
                                            .attr("value", item.id)
                                            .text(item.name));
                        });
                    }
                });
            }
        });
    }

    function getReport(id) {
        var customerno = jQuery("#customerno").val();
        var data = "getLedgerPayment=" + id;
        var data1 = "getledgerpending=" + id + "&customerno=" + customerno;

        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            cache: false,
            data: data,
            success: function (msg) {
                var cdata = jQuery.parseJSON(msg);
                console.log(cdata);
                var showData = [];
                if (cdata.length > 0) {
                    jQuery.each(cdata, function (i, item) {
                        var value = {x: item.x
                            , y: parseInt(item.y)
                            , no: item.invoiceno
                            , i: item.inv_date
                            , e: item.inv_expiry
                            , p: item.paymentdate};
                        showData.push(value);
                    });
                    var chart = new CanvasJS.Chart("paidChart",
                            {
                                title: {
                                    text: "Invoice Generation Chart",
                                    fontSize: 15
                                },
                                axisX: {
                                    title: "Timeline",
                                    gridThickness: 2
                                },
                                axisY: {
                                    title: "Amount (Rs.)"
                                },
                                data: [{
                                        type: "line",
                                        xValueType: "dateTime",
                                        dataPoints: showData,
                                        toolTipContent: "{no}</br>{y}</br>Generation Date:{i}"
                                    }]
                            });

                    chart.render();
                }

                var cdata1 = jQuery.parseJSON(msg);
                var showData1 = [];
                if (cdata1.length > 0) {
                    var x = 1;
                    var total_diff = 0;
                    var creditdays = '<table border="1" width="100%" style="text-align:center;font-size:15px;"><tr><th>Sr No</th><th>Invoice No</th><th>Invoice Date</th><th>Expiry Date</th><th>Payment Date</th><th>Credit Days</th><th>Actual Days</th><th>Delay</th></tr>';
                    jQuery.each(cdata1, function (i, item) {
                        var diff = (item.diff) - 30;
                        total_diff = total_diff + item.diff;
                        if (diff < 1) {
                            diff = 'Within time';
                        }
                        creditdays += '<tr><td style="padding:3px;">' + x + '</td><td style="padding:3px;">' + item.invoiceno + '</td><td style="padding:3px;">' + item.inv_date + '</td><td style="padding:3px;">' + item.inv_expiry + '</td><td style="padding:3px;">' + item.paymentdate + '</td><td>30</td><td>' + item.diff + '</td><td>' + diff + '</td></tr>';
                        x++;
                        var value1 = {x: item.x1
                            , y: parseInt(item.y)
                            , no: item.invoiceno
                            , i: item.inv_date
                            , e: item.inv_expiry
                            , p: item.paymentdate};
                        showData1.push(value1);
                    });

                    creditdays += '</table><br>';
                    jQuery("#avg_days").html((Math.round(total_diff / cdata1.length)));
                    jQuery("#creditdays").html(creditdays);
                    var chart1 = new CanvasJS.Chart("invoiceGenerateChart",
                            {
                                title: {
                                    text: "Customer Payment Behaviour",
                                    fontSize: 15
                                },
                                axisX: {
                                    title: "Timeline",
                                    gridThickness: 2
                                },
                                axisY: {
                                    title: "Payment (Rs.)"
                                },
                                data: [
                                    {
                                        type: "line",
                                        xValueType: "dateTime",
                                        dataPoints: showData1,
                                        toolTipContent: "{no}</br>{y}</br>Payment Date:{p}"
                                    }
                                ]
                            });

                    chart1.render();
                }
            }
        });

        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            cache: false,
            data: data1,
            success: function (msg) {
                jQuery("#pending_amount").html(msg + ' /-');
                jQuery(".pending").show();
            }
        });
    }
</script>