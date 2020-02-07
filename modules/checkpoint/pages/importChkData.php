<?php
$max_columns = 2;
$max_row = 1000;
$column_names_eta = array(
    'A' =>  $checkpoint_name.'ID',
    'B' => 'ETA (HH:MM)'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;
?>
<div class="entry">
    <center>
        <?php
        if (isset($_POST['uploadFile_eta'])) {
            $file_status = file_upload_validation($valid_file, $valid_size, 'chkEtaFile');
            if ($file_status != null) {
                echo $file_status;
            }
            else {
                $excel_data = get_excel_data($_FILES['chkEtaFile']['tmp_name'], $max_columns, $max_row, $column_names_eta);
                if (empty($excel_data)) {
                    echo "No Data Found in Excel";
                }
                else {
                    $record_status = upload_chkEtaData($excel_data);
                    //print_r($record_status);
                    echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
                    echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Records Added, {$record_status['skipped']} Records Skipped.</span>";
                }
            }
        }
        ?>
        <br/>
        <form enctype="multipart/form-data" method="post" class="form-horizontal well " onsubmit='return validate_upload("locationFile");'  style="width:70%;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="100%">Import ETA Data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="100%"><span class="add-on">Import ETA Data Using Excel Sheet (Format - .xls, .xlsx).<span class="mandatory">*</span></span></td>
                    </tr>
                    <tr>
                        <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
                            <span id="loading" style='display:none;'>Loading....</span>
                            <span id="Status" ></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="add-on">Upload File <span class="mandatory">*</span></span></td>
                        <td><input type="file" required="" id="chkEtaFile" name="chkEtaFile" size="2"></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;" colspan="100%"><button name="uploadFile_eta" class="btn btn-primary" type="submit">Upload</button></td>
                    </tr>
                    <tr>
                        <td colspan="100%"><span class="add-on">Please Download The Sample Excel Sheet For Import <?php echo $checkpoint_name;?> ETA Data. <span class="mandatory">*</span>  <a href='../../modules/checkpoint/pages/upload_files/upload_eta.xlsx' target="_blank">Download Excel Sheet</a></span></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </center>
</div>