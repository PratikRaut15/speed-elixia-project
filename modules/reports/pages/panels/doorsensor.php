<style>
    #search tbody tr td{
        cursor:default;
    }
</style>	
<table id="search">
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
            <span id="error3" name="error3" style="display: none;">Please Select <?php echo $_SESSION['warehouse'];?></span>
        <span id="error6" name="error6" style="display: none;">Please Select Date Between  <?php echo date('Y-m-d', strtotime( $_SESSION['startdate']));?> AND <?php echo date('Y-m-d', strtotime($_SESSION['enddate']));?></span>
        <span id="error7" name="error7" style="display: none;">Unauthorised  to view this report</span>
        </td>
    </tr>
