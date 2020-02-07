<div class="container">
<table class='table newTable' >
    <thead>
        <tr>
            <th colspan="100%" style='font-weight:bold;font-size:14px;'><?php echo $report_name; ?></th>
        </tr>
        <tr>
            <th colspan="100%">
                <div class="newTableSubHeader">
                    <div class="newTableSubHeaderLeft">
                    Start Date: <?php echo $sdate; ?><br/>
                    End Date: <?php echo $edate; ?><br/>
                    </div>
                </div>
                <div class="newTableSubHeader">
                    <div class="newTableSubHeaderRight">
                    Generated by: <?php echo $_SESSION['username']; ?><br/>
                    Generated on: <?php echo date('d-m-Y'); ?>
                    </div>
                </div>
            </th>
        </tr>
    </thead>
</table>
</div>
<br/><br/><br/>

<div class="container">
<div id="graph_container" style="min-width: 910px; height: <?php echo $graph_height; ?>px; margin: 0 auto;"></div>
</div>
<div class="clearfix"></div>

<br/><br/>
<?php
if(count($totaldays) <=15){
    echo '<div class="container"><table class="table newTable" >';
}
else{
    echo '<div class="container-fluid" style="overflow-x: scroll;"><table class="newTable" >';
}
?>

    <thead>
        <tr>
            <th>Vehicle No</th>
            <th colspan='<?php echo count($totaldays); ?>'><?php echo $table_header; ?></th>
            <th>Total</th>
        </tr>
    </thead>    
        