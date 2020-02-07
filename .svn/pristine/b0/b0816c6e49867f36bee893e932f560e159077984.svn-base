<!--<div class='container'>
    <a href='#mail_pop' style="float:right; margin:5px;" data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
    <a href='javascript:void(0)' style="float:right; margin:5px;" onclick="xls_vehicle_renewal(<?php echo $_SESSION['customerno']; ?>);
         return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
    <a href='javascript:void(0)' style="float:right; margin:5px;" onclick="pdf_vehicle_renewal(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
</div>-->
<?php
$vehicles = getvehicles();
$today = date("Y-m-d");

?>

<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #renewalreport_filter{display: none}
    .dataTables_length{display: none}
    div.dt-buttons{float:right; margin: 0px 20px 0px 0px;}
</style>
<br/>

    <center>
        <h3>Renewal Report</h3>
        <input type='hidden' id='forTable' value='RenewalReport'/>
        <table  style="width:70%;"class='display table table-bordered table-striped table-condensed' id="renewalreport" >
            <thead>
                <tr>
                    <td><input type="text" class='search_init' name='vehicleno'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='seatcapacity' style="width:90%;"   autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='group' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='registrationdate' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='tax_exp_date' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='tax_pending_days' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='insurance_exp_date' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='insurance_exp_days' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='permit_exp_date' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='permit_pending_days' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='fitness_exp_date' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='fitness_pending_days' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='puc_exp_date' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='puc_pending_days' style="width:90%;" autocomplete="off"/></td>
                    
                </tr>
                <tr class='dtblTh'>
                        <th>Vehicle No.</th>
                        <th>Seat <br>Capacity</th>
                        <th>Group</th>
                        <th>Registration Date</th>
                        <th>Tax Expiry Date</th>
                        <th>Tax Pending Days</th>
                        <th>Insurance Expiry Date</th>
                        <th>Insurance Pending Days</th>
                        <th>Permit Expiry Date</th>
                        <th>Permit Pending Days</th>
                        <th>Fitness Expiry Date</th>
                        <th>Fitness Pending Days</th>
                        <th>PUC Expiry Date</th>
                        <th>PUC Pending Days</th>
                </tr>
            </thead>
        </table>
    </center>

<script type='text/javascript'>
    var data = <?php echo json_encode($vehicles); ?>;
    var tableId = 'renewalreport';
    var tableCols = [
        {"mData": "vehicleno"}
        , {"mData": "seatcapacity"}
        , {"mData": "grname"}
        , {"mData": "reg_expiry"}
        , {"mData": "taxtodate"}
        , {"mData": "diff_tax"}
        , {"mData": "insurance_expiry"}
        , {"mData": "diff_insurance_exp"}
        , {"mData": "other1_expiry"}
        , {"mData": "diff_other1_exp"}
        , {"mData": "other3_expiry"}
        , {"mData": "diff_other3_exp"}
        , {"mData": "puc_expiry"}
        , {"mData": "diff_puc_exp"}
    ];
</script>

<?php
$mail_function = "mail_vehicle_renewal(" . $_SESSION['customerno'] . ");";
include_once "mail_pop_up.php";
?>
<script>
    function xls_vehicle_renewal(customerno) {
        var dataString = 'customerno=' + customerno + '&report=vehrenewal';
        window.location = "savexls.php?" + dataString;
    }

    function pdf_vehicle_renewal(customerno) {
        var dataString = 'customerno=' + customerno + '&report=vehrenewalpdf';
        window.open("pdftest.php?" + dataString);
    }

    function mail_vehicle_renewal(customerno) {
        var emailid = jQuery("#sentoEmail").val();
        var body = jQuery("#mailcontent").val();
        var mail_type = jQuery('input[name=emailtype]:checked').val();
        var dataString = 'customerno=' + customerno + '&report=vehrenewalmail' + '&emailid=' + emailid + '&mail_content=' + body +'&mailType=' + mail_type;
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function (result) {
                jQuery("#mailStatus").html(result);
            }
        });
    }
</script> 
    <?php
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/renewal_datatable.js' type='text/javascript'></script>";
    ?>