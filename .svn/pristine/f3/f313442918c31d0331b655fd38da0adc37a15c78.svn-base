<?php
    include_once "session.php";
    include_once "cashflow_functions.php";

    include "header.php";
    $objCategory = new stdClass();
    $objCategory->categoryid = 0;
    $arrResultCategory = getCategory($objCategory);
    $categoryList = json_decode(json_encode($arrResultCategory));

    if (isset($categoryList)) {
        $categoryString = '';
        foreach ($categoryList as $category) {
            $categoryString .= "<option value='" . $category->categoryid . "'>" . $category->category . "</option>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getBankStatementGraphicalAnalysis") {
        $objStatement = new stdClass();
        $objStatement->statementid = 0;
        $objStatement->transaction_datetime_from = isset($_POST['fromdate']) ? date(speedConstants::DATE_Ymd, strtotime($_POST['fromdate'])) : '';
        $objStatement->transaction_datetime_to = isset($_POST['todate']) ?date(speedConstants::DATE_Ymd, strtotime($_POST['todate'])) : '';
        $objStatement->transaction_type = isset($_POST['transaction_type'])?$_POST['transaction_type'] :0;
        $objStatement->categoryid = isset($_POST['category']) ? $_POST['category'] : 0;
        $objStatement->teamid = GetLoggedInUserId();
        //print_r($objStatement);
        $arrFilterBankStatementResult = getBankStatement($objStatement);
        $statementList = json_decode(json_encode($arrFilterBankStatementResult));
        //print_r($statementList);

        $cus = array("Jan","Feb","March");
        $pending = array('21454','45644','65465564');
    }

?>
<br/>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Bank Statement</div>
    <div class="panelcontents">
        <form method="post" action="bankStatementGraphicalAnalysis.php?action=getBankStatementGraphicalAnalysis" >
            <table>

                <tr>
                    <td>Category <span style="color:red;">*</span></td>
                        <td>
                            <select name="category" id="category">
                                <option value="0">Select Category</option>
                                <?php echo $categoryString; ?>
                            </select>
                        </td>
                    <td>
                        <input type="submit" name="search" value="Search" />
                    </td>
                </tr>
            </table>

        </form>
    </div>
</div>
<br/>
<div id="container" style="min-width: 910px; min-height:400px; margin: 0 auto;"></div>
<?php
$pending = array(100,200,300);
$petty = array(1000,2000,3000);
$pay = array(1500,2500,3500);
?>

<script src="../../scripts/highcharts/js/highcharts.js" type='text/javascript'></script>
<script type="text/javascript">

jQuery(function () {
    jQuery('#container').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Account Analysis'
        },
        xAxis: {
            title: {
                text: 'Months'
            },
categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar',]            //categories: [<?php //echo "1, 2, 3, 4, 5, 6";?>], //"90, 67, 5, 7, 45, 34"//
        },
        yAxis: {
                title: {
                text: 'Amount'
                },
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 62,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        colors: ['#FF0000'],
        series: [{
            name: 'Amount',
            data: [<?php echo join($pending, ',');?>]
        }]

    });
});

</script>
