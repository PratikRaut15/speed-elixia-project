<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include ('busrouteFunctions.php');
$maxColumns = 13;
$maxRow = 2000;
$studentColumns = array(
   'A' => 'Code',
   'B' => 'ErnNo',
   'C' => 'Board',
   'D' => 'Grade',
   'E' => 'Division',
   'F' => 'Address',
   'G' => 'Building',
   'H' => 'Street',
   'I' => 'Landmark',
   'J' => 'Area',
   'K' => 'Station',
   'L' => 'City',
   'M' => 'Pincode'
);
$validFile = array('xls', 'xlsx');
$validSize = 2000000;

if (isset($_POST['uploadFile'])) {
   $file_status = file_upload_validation($validFile, $validSize, 'studentFile');
   if ($file_status != null) {
      echo $file_status;
   } else {
      $excel_data = get_excel_data($_FILES['studentFile']['tmp_name'], $maxColumns, $maxRow, $studentColumns);
      if (empty($excel_data)) {
         echo "No Data Found in Excel";
      } else {
         $record_status = uploadStudentData($excel_data);
         echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
         echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Records Added, {$record_status['skipped']} Records Skipped.</span>";
      }
   }
}
?>
<div id="container">
   <h3>Step - 1</h3>
   <hr/>
   <form enctype="multipart/form-data" method="post" class="form-horizontal well " onsubmit='return validate_upload("studentFile");'  style="width:70%;">
   <table class="table table-bordered">
      <thead>
         <tr>
            <th colspan="100%">Import Student Data</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td colspan="100%"><span class="add-on"> Import Data Using Excel Sheet (Format - .xls, .xlsx).<span class="mandatory">*</span></span></td>
         </tr>
         <tr>
            <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
              <span id="loading" style='display:none;'>Loading....</span>
              <span id="Status" ></span>
           </td>
         </tr>
         <tr>
            <td><span class="add-on">Upload File <span class="mandatory">*</span></span></td>
            <td><input type="file" required="" id="studentFile" name="studentFile" size="2"></td>
         </tr>
         <tr>
            <td style="text-align:center;" colspan="100%"><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
         </tr>
         <tr>
            <td colspan="100%"><span class="add-on"> Please Download The Sample Excel Sheet For Import Student Data. <span class="mandatory">*</span>  <a href='student.xls' target="_blank">  Download Excel Sheet</a></span></td>
         </tr>
      </tbody>
   </table>
</form>

<h3>Step - 2</h3>
<hr/>
<?php
if (isset($_POST['generateBusStop'])) {

   createBusStop();
}
?>
<form enctype="multipart/form-data" method="post" class="form-horizontal well " action="<?php $_SERVER['PHP_SELF'];?>" style="width:70%;">
<button type="submit" class='btn btn-success' id="generateBusStop" name="generateBusStop" >Click Here To Generate Bus Stop</button>
<br style='clear:both'/>
</form>
</div>