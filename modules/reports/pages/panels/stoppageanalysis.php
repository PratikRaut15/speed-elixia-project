<table>
    <thead>
    <tr>
        <th id="formheader" colspan="100%"><?php echo $title; ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="100%">
            <span id="error" name="error" style="display: none;">Data Not Available</span>
            <span id="error1" name="error1" style="display: none;">Please Check The Date</span>
            <span id="error2" name="error2" style="display: none;">Please Select Dates With Difference Of Not More Than 30 Days</span>
            <span id="error6" name="error6" style="display: none;">Please Select Date Between  <?php echo date('Y-m-d', strtotime( $_SESSION['startdate']));?> AND <?php echo date('Y-m-d', strtotime($_SESSION['enddate']));?></span>
        </td>
    </tr>
