<div  style="float:none; padding-left:30%;">
<table id="floatingpanel">
    <thead>
        <tr>
            <th>Sr.No</th>
            <th>Report</th>
            <th>Download Link</th>
        </tr>
    </thead>
    <tr>
        <td>1</td>
        <td>Installation Device Report</td>
        <td><a href='javascript:void(0)' onclick="export_InstallDeviceReport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>,1,'<?php echo speedConstants::REPORT_XLS; ?>' );"><img src="../../images/xls.gif" alt="Export to XLS" class='exportIcons' title="Export to XLS" /></a></td>
    </tr>
    <tr>
        <td>2</td>
        <td> Capex Payment Report</td>
        <td><a href='javascript:void(0)' onclick="export_InstallDeviceReport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>,2,'<?php echo speedConstants::REPORT_XLS; ?>' );"><img src="../../images/xls.gif" alt="Export to XLS" class='exportIcons' title="Export to XLS" /></a></td>
    </tr>
    <tr>
        <td>3</td>
        <td>  Inventory Report</td>
        <td><a href='javascript:void(0)' onclick="export_InstallDeviceReport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>,3,'<?php echo speedConstants::REPORT_XLS; ?>' );"><img src="../../images/xls.gif" alt="Export to XLS" class='exportIcons' title="Export to XLS" /></a></td>
    </tr>
    <tr>
        <td>4</td>
        <td>  Inactive Vehicle Report</td>
        <td><a href='javascript:void(0)' onclick="export_InstallDeviceReport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>,4,'<?php echo speedConstants::REPORT_XLS; ?>' );"><img src="../../images/xls.gif" alt="Export to XLS" class='exportIcons' title="Export to XLS" /></a></td>
    </tr>
    <tr>
        <td>5</td>
        <td> Device In / Out  Warranty</td>
        <td><a href='javascript:void(0)' onclick="export_InstallDeviceReport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>,5,'<?php echo speedConstants::REPORT_XLS; ?>' );"><img src="../../images/xls.gif" alt="Export to XLS" class='exportIcons' title="Export to XLS" /></a></td>
    </tr>
    <tr>
        <td>6</td>
        <td> Monthly Device activity Report</td>
        <td><a href='javascript:void(0)' onclick="export_InstallDeviceReport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>,6,'<?php echo speedConstants::REPORT_XLS; ?>' );"><img src="../../images/xls.gif" alt="Export to XLS" class='exportIcons' title="Export to XLS" /></a></td>
    </tr>
    <tr>
        <td>7</td>
        <td> Base User for each Location Report</td>
        <td></td>
    </tr>
    <tr>
        <td>8</td>
        <td>Advisory Report</td>
        <td></td>
    </tr>
    <tr>
        <td>9</td>
        <td> Monthly Billing MIS for Due and Balance</td>
        <td></td>
    </tr>
    <tr>
        <td>10</td>
        <td>  Monthly Billing Consolidated</td>
        <td></td>
    </tr>
</table>
</div>
