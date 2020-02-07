<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/bo/TeamManager.php");

include_once("header.php");
?>
<style>
</style>
<link rel="stylesheet" href="../../css/invoicePayment.css">
<div class="panel">
    <div class="paneltitle" align="center">Prospective Customer</div>
        <div class="panelcontents">
            <form method="post" name="prospCust" id="prospCust" enctype="multipart/form-data">
                <table width="100%" align="center">
                    <tr>
                        <td>Real Name</td><td><input name = "realname" id="realname" type="text" placeholder="Enter Name"></td>
                        <td>Company <span style="color:red;">*</span></td><td><input name="company_name" id="company_name" required type="text" placeholder="Enter Company Name"></td>
                        <td>Address</td><td><textarea maxlength="255" placeholder="Enter Address" name="company_add" id="company_add"></textarea></td>
                        
                    </tr>
                    <tr>
                        <td>Contact Number</td><td><input name = "phone" id="phone" type="text" placeholder="Enter Number"></td>
                        <td>Email Address <span style="color:red;">*</span></td><td><input id="emailaddress" name="emailaddress" type="text" placeholder="Enter Email" required/></td> 
                        <td>Remarks</td><td><textarea maxlength="255" name="remarks" id="remarks" placeholder="Enter Remarks"></textarea></td>
                    </tr>
                </table>
                <input type="button" value="Submit" onclick="submitProspect();">
            </form>
        </div>
</div>
<br>
<div id="prospCustomerGrid" class="ag-theme-blue" style="height:400px;width:1240px;margin:0 auto;"></div>
<script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>
<script>
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
       
                          columnDefs = [
                            {headerName:'Name',field: 'customername',width:150,filter:'agTextColumnFilter'},
                            {headerName:'Company',field: 'customercompany',width:200,filter:'agTextColumnFilter'},
                            {headerName:'Address',field: 'address',width:200,filter:'agTextColumnFilter'},
                            {headerName:'Phone',field: 'phone',width:150,filter:'agTextColumnFilter'},
                            {headerName:'Email',field: 'email',width:200,filter:'agTextColumnFilter'},
                            {headerName:'Remarks',field: 'remarks',width:180,filter:'agTextColumnFilter'},
                            {headerName:'Edit',cellRenderer:'editCellRenderer',width: 70,suppressFilter:true},
                            {headerName:'Delete',cellRenderer:'deleteCellRenderer',width: 80,suppressFilter:true}
                        ];
              
                        
            var gridOptions_prospCust;
                gridOptions_prospCust = {
                enableFilter:true,
                enableSorting: true,
                floatingFilter:true,
                animateRows:true,
                columnDefs: columnDefs,
                components: {deleteCellRenderer : deleteCellRenderer,editCellRenderer : editCellRenderer},
                masterDetail: true,                                
                onGridReady: function(params) {
                gridOptions_prospCust.columnApi.autoSizeColumns();
            }


            };

            function deleteCellRenderer(params){
            return "<a href='javascript:void(0);' alt='Delete' title='Delete' target='_blank' onclick='deleteProsCust("+params.data.prospectId+");'><img style='height:12px;' src='../../images/delete.png'/></a>"
            }

            function editCellRenderer(params){
            return "<a href='updateProspCust.php?prosId="+params.data.prospectId+"' target='_blank' alt='Edit' title='Edit' ><img style='height:12x;width:20px;' src='../../images/edit.png'/></a>"
            }
                             //gridOptions.rowHeight = 20;
                             var gridDiv = document.getElementById('prospCustomerGrid');
                             new agGrid.Grid(gridDiv,gridOptions_prospCust);                    
</script>
<?php
include_once("footer.php");
?>
<script src='../../scripts/team/prospectiveCustomer.js'></script>