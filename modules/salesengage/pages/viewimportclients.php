<?php
/**
 * Import Client data
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno, $userid);



$max_columns = 5;
$max_row = 2000;
$column_names = array(
    'A' => 'name',
    'B' => 'address',
    'C' => 'email',
    'D' => 'mobileno',
    'E' => 'dob'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;

?>
<br/>
<div class='container'>
    <center>
        <?php
        if(isset($_POST['clientsubmit'])){
            $file_status = file_upload_validation_salesengage($valid_file, $valid_size);
            if($file_status!=null){
                echo $file_status;
            }
            else{
                include_once $Mpath . '../../lib/comman_function/PHPExcel.php';
                $excel_data = get_excel_data_salesengage($_FILES['clientfile']['tmp_name'], $max_columns, $max_row, $column_names);
                if(empty($excel_data)){
                    echo "No Data Found in Excel";
                }
                else{
                   
                        $record_status = upload_checkpoint_salesengage($excel_data);
                        echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
                        echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Orders added, {$record_status['skipped']} Orders skipped.</span>";
                    
                }
            }
        }
        ?>
        
        <form enctype="multipart/form-data" method="post" name="clientfileupload" id="clientfileupload">
            <table class='table table-condensed'>
                <thead>
                    <tr>
                        <td colspan="100%" class="tdnone">
                            <div>
                                <a href="salesengage.php?pg=view-client" class="backtextstyle" title="Back to Client View">Back To Client View</a>
                            </div>
                        </td>
                    </tr>
                    <tr><th colspan="100%">Import Client Details </th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr>
                        <td class='frmlblTd'>Import Vendor Using Excel Sheet( Format xls , xlsx)<span class="mandatory">*</span></td>
                        <td>  <input type="file" name="clientfile" id="clientfile"></td>
                    </tr>
                    <tr>
                        <td colspan="100%" class='frmlblTd'>
                            <input type="submit" name="clientsubmit" id="clientsubmit" value="Upload" class='btn btn-primary'>
                        </td>
                    </tr>
                    <tr><td colspan="100%">Download The Sample Excel Sheet For Import Vendors <span class="mandatory">*</span><a target="_blank" href="clients.xls"> Download Excel Sheet</a></td></tr>
                    <tr><td colspan="100%"><b>Note <span class="mandatory">*</span>  : </b><span>In Excel date format should be in this format : 21-07-1989</span></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>
