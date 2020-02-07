 <?php
include_once("session.php");
include_once("db.php");

include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/bo/SchoolManager.php");

class customers {
    
}
 include("header.php");

//Datagtrid
$db = new DatabaseManager();
$srno = 0;
$totalsimcount = 0;
$totalunits = 0;
$totalpayment = 0;
$customers = Array();

$pdo = $db->CreatePDOConn();
$queryCallSP = "CALL " . speedConstants::SP_VIEW_CUSTOMER;
$result=$pdo->query($queryCallSP); 
  
$schoolmanager  = new SchoolManager($_SESSION['customerno']);
$schoollist     = $schoolmanager->getSchoolList();
?>
     <script src="https://unpkg.com/ag-grid/dist/ag-grid.js"></script>
         <script type="text/javascript">
       $(document).ready(function () {
            $('#dateid').datepicker({
                format: "dd-mm-yyyy",
                language: 'en',
                autoclose: 1
            });
              });
function createData(myArray){
    var rowData = [];
          return $.each(myArray, function (index, value) {
        $.each(value, function (i, v) {    
        [ {i: value[i], model: "Celica", price: 35000}];
    
      /* var rowData = [
        {field: value.field, model: "Celica", price: 35000}
    ];*/
    });
});
}
       function submitForm(){
        var schoolid    = $('#schoolid').val();
        var date        = $('#date').val();
        var standard    = $('#standard').val();
        var division    = $('#division').val();
        var studentArray=[];
        $.ajax({
            type: "POST",
            url: "school_ajax.php",
            cache: false,
            data: {schoolid: schoolid  ,date :date , standard:standard, division: division
                    },
            success: function (data) {
                $('#myGrid').html('');
                dataArray = JSON.parse(data);
                var columnDefs = [
            {headerName: "RollNo", field: "rollno"},
            {headerName: "Name", field: "studentname"},
            {headerName: "School", field: "schoolname"},
            {headerName: "Present", field: "present"},
            {headerName: "Parent Name", field: "parentname"},
            {headerName: "Phone", field: "parentphone"},
            {headerName: "Standard", field: "standard"},
            {headerName: "Division", field: "division"},
            {headerName: "SMSAlert", field: "smsAlert"}
        ];
        var myArray = dataArray;
        var myData = dataArray;
// let the grid know which columns and what data to use
        var gridOptions = {
            columnDefs: columnDefs,
            rowData: myData,
            onGridReady: function() {
                gridOptions.api.sizeColumnsToFit();
            }
        }
        if(data){
            // lookup the container we want the Grid to use
            var eGridDiv = document.querySelector('#myGrid');
            // create the grid passing in the div to use together with the columns & data we want to use
            new agGrid.Grid(eGridDiv, gridOptions);
            }
            else{
                $("#myGrid").html('<h3>No data Available</h3>');
            }
         }
     });
        
 }
    </script>
     <div class="panel">
        <div class="paneltitle" align="center">School Attendance</div>
        <div class="panelcontents">
            <form method="post" action="" name="school" id="school" onsubmit="return ValidateForm();
                        return false;">
                      <?php echo($message); ?>
                <table width="100%">
                    <tr>
                        <?php $today = date('d-m-Y'); ?>
                        <td>Select School Branch <span style="color:red;">*</span></td>
                        <td>
                            <select id='schoolid' name='schoolid'>
                                <option value=''>Select School Branch</option>
                                <?php 
                                foreach($schoollist as $key=>$val){ ?>
                                        <option value='<?php echo $val['schoolid'];?>'><?php echo $val['schoolname'];?></option>
                                <?php } ?>
                            </select>
                            <input id="school" name = "school" value="" type="hidden">
                        </td>
                        <td>Date </td>
                        <td><input id="dateid" name = "date" type="text" placeholder="Select Date"></td></tr> 
                        <tr>   
                        <td>Standard</td>
                        <td>
                            <select id='standard' name='standard'>
                                <option value=''>Select Standard</option>
                                <option value='1'>I</option>
                                <option value='2'>II</option>
                                <option value='3'>III</option>
                                <option value='4'>IV</option>
                                <option value='5'>V</option>
                                <option value='6'>VI</option>
                                <option value='7'>VII</option>
                                <option value='8'>VIII</option>
                                <option value='9'>IX</option>
                                <option value='10'>X</option>
                            </select>
                            <input type="hidden" name="std" id="std"/>
                        </td>
                        <td>Division</td>
                        <td>
                            <select id='division' name='division'>
                                <option value=''>Select Division</option>
                                <option value='A'>A</option>
                                <option value='B'>B</option>
                                <option value='C'>C</option>
                                <option value='D'>D</option>
                            </select>
                            <input type="hidden" name="div" id="div"/>
                        </td>
                        <td>
                            <input type="button" id="submitschool" name="submitschool" style="background-color: #00A5B9; color: white;" value="Submit" onclick='submitForm()'/>
                        </td>
                    </tr>
        </table>
    </form>
  
    </div>
</div>
    <div id="myGrid" style="height: 150px;width:1200px;margin-top:5%;margin-left:10%;" class="ag-fresh"></div>
    </div>
