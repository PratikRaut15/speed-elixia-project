
<div class="panel" style="margin-top:100px;width:100%;">
    <div class="paneltitle" align="center" style="width:100%;">Scheduled Invoices</div>
    <div id="myGrid" class="ag-theme-material" style="height:500px;width:100%;margin:0 auto;">
    </div>
</div>
<?php
if((!checkUserType(speedConstants::TEAM_DEPARTMENT_SALES,speedConstants::TEAM_ROLE_HEAD))&&(!checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT,speedConstants::TEAM_ROLE_HEAD))){
    //$queryadd = "AND sp.teamid =".GetLoggedInUserId()."";
}
?>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
    var rowData;
    $(document).ready(function(){
    jQuery.ajax({
        type: "POST",
        url: "invoice_functions.php",
        data: "fetchInvoiceReminders=1&invId=0",
        success: function(data) {
            var data = JSON.parse(data);
            rowData=data;
            $.each(data,function(i,record){                
            });
            $("#period").append(str);
            },
        });
    });
    function invoiceDownloadLink(params){
        //console.log(params.data.invoiceid);
        //console.log( "<a href='../modules/download/erp_invoice.php?invoiceid="+params.data.invoiceid+">Download</a>");
        if(params.value==1){
            return "<a href='../download/erp_invoice.php?invoiceid="+params.data.invoiceid+"'>Download</a>";
        }else{
            return "No";
        }
    }
    function generateInvoiceLink(params){
        if(params.data.invoice_generated==0){
            return "<a href='approveInvoice.php?invoiceId="+params.data.inv_rem_id+"'>Generate</a>";
        }
    }
    function deleteCellRenderer(params){
        return "<a href='javascript:void(0);' alt='Delete' title='Delete' target='_blank' onclick='deleteInvoice(" +params.data.inv_rem_id+ ");'><img style='height:25px;padding:7px;' src='../../images/delete_icon_black.png'/></a>"
    }

    function editCellRenderer(params){
        return "<a href='editScheduledInvoice.php?invoiceId="+params.data.inv_rem_id+"' target='_blank' alt='Edit' title='Edit' ><img style='height:25px;padding:7px;' src='../../images/edit_icon_black.png'/></a>"
    }

    function deleteInvoice(invoiceid){
        jQuery.ajax({
            type: "POST",
            url: "invoice_functions.php",
            data: "deleteInvoiceReminder=1&invId="+invoiceid,
            success: function(data) {
                if(data == "ok"){
                    window.location="schedule_invoice.php";

                }
            },
        });
    }

    $( document ).ajaxStop(function() {
        columnDefs = [
            {cellRenderer:'editCellRenderer',width: 70  },
            {cellRenderer:'deleteCellRenderer',width: 80},
            {headerName:'Invoice ID',field: 'inv_rem_id'},
            {headerName:'Customer', field:'customercompany',},
            {headerName:'Cycle duration', field:'cycle_name'},
            {headerName:'Ledger', field:'ledgername'},
            {headerName:'Expected Invoice Date', field:'expectedInvDate'},
            {headerName:'Invoice Type', field:'inv_type_name'},
            {headerName:'Invoice Amount', field:'invoiceAmount'},
            {headerName:'One Time Amount', field:'amount'},
            {headerName:'AMC', field:'amc_amount'},
            {headerName:'Invoice Description', field:'inv_desc'},
            {headerName:'Next Reminder Date', field:'reminder_date'},
            {headerName:'Generate invoice', cellRenderer:'generateInvoiceLink', pinned:'right'},
            {headerName:'Invoice Generated?', field:'invoice_generated', cellRenderer:'invoiceDownloadLink',pinned:'right'}
        ];
        gridOptions = {
        enableFilter:true,
        enableSorting: true,
        rowData:rowData,
        animateRows:true,
        suppressColumnVirtualisation:true,
        columnDefs: columnDefs,
        components:{invoiceDownloadLink:invoiceDownloadLink,generateInvoiceLink:generateInvoiceLink,editCellRenderer:editCellRenderer,deleteCellRenderer:deleteCellRenderer},
        onGridReady: function(params) { 
            var allColumnIds = [];
            gridOptions.columnApi.getAllColumns().forEach(function(column) {
                allColumnIds.push(column.colId);
            });
             //   gridOptions.api.sizeColumnsToFit();
            }
        };
        var gridDiv = document.getElementById('myGrid');
        
        agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");new agGrid.Grid(gridDiv,gridOptions);
    });

</script>