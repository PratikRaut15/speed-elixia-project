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
    $dg = new objectdatagrid($statementList);
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getBankStatement") {

    } else {
        $dg->SetNoDataMessage("");
    }

?>
<br/>
<div id="container" style="min-width: 910px; min-height:4000px; margin: 0 auto;"></div>



<?php
    include "footer.php";
?>
<script src="../../scripts/highcharts/js/highcharts.js" type='text/javascript'></script>
<script type="text/javascript">

jQuery(function () {
    jQuery('#container').highcharts({
        chart: {
            type: 'line',
            inverted: true
        },
        title: {
            text: 'Bank Statement Analysis'
        },
        xAxis: {
                title: {
                text: 'Month-Year'
                },
                categories: [<?php echo join($cus, ',');?>]
        },
        yAxis: {
            min :0,
            title: {
                text: 'Amount in Rs.'
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
        colors: ['#FF0000','#000000','#008000'],
        series: [{
            name: 'Receivables',
            data: [<?php echo join($pending, ',');?>]
        },{
            name: 'Petty Cash',
            data: [<?php echo join($petty, ',');?>]
        },{
            name: 'Paid Amount',
            data: [<?php echo join($pay, ',');?>]
        }]

    });
});

</script>
