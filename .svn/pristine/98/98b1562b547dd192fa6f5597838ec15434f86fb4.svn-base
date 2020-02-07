<?php
$title = ' Driver Performance Report';
$subTitle = array(
    "Start Date: $STdate",
    "End Date: $EDdate",
    "Unit: Kilometers ",
    "Driver Name: $drivername "
);
echo table_header($title, $subTitle);
$SDate = $STdate;
$STdate = date('d-m-Y', strtotime($STdate));
$t_columns = '';
$colspan = 0;
?>
<div class="container">
<div style='float:right;min-height: 30px;'>
    <span style='padding:3px;'>SA : Sudden Acceleration</span>
    <span style='padding:3px;'>HB : Harsh Break</span>
</div>
 <table id='search_table_2' class="table newTable">
    <thead>
    <tr>
        <th>Serial No</th>
        <th colspan="<?php echo $colspan;?>">Date</th>
        <th>Distance in km.</th>
        <th>OverSpeed Count</th>
        <th>Top Speed in km/hr.</th>
        <th>SA</th>
        <th>HB</th>
    </tr>
    <tr class='tableSub' >
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <?php echo $t_columns;?>
    </thead>
