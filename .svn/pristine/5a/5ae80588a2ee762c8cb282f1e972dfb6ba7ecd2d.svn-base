<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//include_once("session.php");

include_once("../../lib/system/utilities.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");

$_scripts[] = "../../scripts/jquery.min.js";
$_scripts[] = "../../scripts/team/modifycustomer.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";

$db = new DatabaseManager();
$SQL = sprintf("SELECT  categoryid
                        ,category
                FROM    `category`
                ORDER BY category ASC");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    $category = array();
    while ($row1 = $db->get_nextRow()) {
        $cat['id'] = $row1['categoryid'];
        $cat['name'] = $row1['category'];
        $category[] = $cat;
    }
}

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
    <div class="paneltitle" align="center">Expense Behavior</div>   
    <div class="panelcontents">
        <table>
            <tr>
                <td><h5>Category</h5></td>
                <td>
                    <select id="category" name="category" onchange="getReport();">
                        <option value="-1">SELECT CATEGORY</option>
                        <?php
                        foreach ($category as $data) {
                            echo '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>

        </table>
    </div>
</div>
<div style="text-align: right;margin-top: 5%;"><span class="pending" style="font-size:17px;display: none;margin-right: 200px">Pending Amount : <span class="pending" id="pending_amount"></span></span></div>
<div style="text-align: right;"><span class="pending" style="font-size:17px;display: none;margin-right: 200px">Average Delay Days : <span class="pending" id="avg_days"></span></span></div>
<div id="expenseChart"></div>
<hr>
<?php
include("footer.php");
?>
<script>

    function getReport() {
        var category = jQuery("#category").val();

        if (category > 0) {
            var data = "getExpenseReport=" + category;

            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                cache: false,
                data: data,
                success: function (msg) {
                    var cdata = jQuery.parseJSON(msg);
                    var showData = [];
                    if (cdata.length > 0) {
                        jQuery.each(cdata, function (i, item) {
                            var value = {x: item.x
                                , y: parseInt(item.y)};
                            showData.push(value);
                        });
                        var chart = new CanvasJS.Chart("expenseChart",
                                {
                                    title: {
                                        text: "Expense Chart",
                                        fontSize: 15
                                    },
                                    axisX: {
                                        title: "Timeline",
                                        gridThickness: 2
                                    },
                                    axisY: {
                                        title: "Expense (Rs.)"
                                    },
                                    data: [{
                                            type: "line",
                                            xValueType: "dateTime",
                                            dataPoints: showData,
                                            toolTipContent: ""
                                        }]
                                });

                        chart.render();
                    }

                }
            });

        }
    }
</script>