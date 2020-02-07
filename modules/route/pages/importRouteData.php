<?php
$max_columns = 6;
$max_row = 1000;
$column_names_routemapping = array(
    'A' => 'routeName',
    'B' => 'station',
    'C' => 'vehicleNo',
    'D' => 'storeName',
    'E' => 'date',
    'F' => 'etaTime'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 200000;
?>
<div class="entry">
<center>
<?php
if (isset($_POST['uploadFile_routemapping'])) {
    echo $file_status = file_upload_validation($valid_file, $valid_size, 'vehRouteMappingFile');
    if ($file_status != null) {
        echo $file_status;
    } else {
        $excel_data = get_excel_data($_FILES['vehRouteMappingFile']['tmp_name'], $max_columns, $max_row, $column_names_routemapping);
        if (empty($excel_data)) {
            echo "No Data Found in Excel";
        } else {
            //print_r($excel_data);
            $record_status = uploadRouteVehicleDetails($excel_data);
            //print_r($record_status);
            echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
            echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Records Added, {$record_status['skipped']} Records Skipped.</span><br/>";
            echo "<span style='font-weight:bold;color:blue;'>Errors - {$record_status['errors']}</span>";
        }
    }
}
?>
        <br/>
        <form enctype="multipart/form-data" method="post" class="form-horizontal well " onsubmit='return validate_upload("chkRouteMappingFile");'  style="width:70%;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="100%">Import Route Mapping Data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="100%"><span class="add-on">Import Route Mapping Data Using Excel Sheet (Format - .xls, .xlsx).<span class="mandatory">*</span></span></td>
                    </tr>
                    <tr>
                        <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
                            <span id="loading" style='display:none;'>Loading....</span>
                            <span id="Status"></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="add-on">Upload File <span class="mandatory">*</span></span></td>
                        <td><input type="file" required="" id="vehRouteMappingFile" name="vehRouteMappingFile" size="2"></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;" colspan="100%"><button name="uploadFile_routemapping" class="btn btn-primary" type="submit">Upload</button></td>
                    </tr>
                    <tr>
                        <td colspan="100%">
                            <span class="add-on"> Please Download The Sample Excel Sheet For Import Route Mapping Data.
                                <span class="mandatory">*</span>
                                <a href='../../modules/route/pages/upload_files/chkpt.xls' target="_blank">Download Excel Sheet</a>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </center>
</div>