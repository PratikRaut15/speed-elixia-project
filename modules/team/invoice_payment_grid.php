<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/bo/TeamManager.php"); 
include_once("../../lib/system/DatabaseManager.php"); 
include_once("../../lib/system/Sanitise.php");

?>
<br>
<fieldset>
  <legend>New Payment Table</legend>
  <div id="myGrid" class="ag-theme-fresh" style="height:200px;width:70%;margin:0 auto;"></div>
</fieldset>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
  
    // function deleteCellRenderer(params){
    //     return "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" +params.data.pipelineid+ ");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete.png'/></a>"
    // }

    function editCellRenderer(params){
        return "<a href='invoice_payment_edit.php?ip_id="+params.data.ip_id+"' target='_blank'><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }
  
       
                          columnDefs = [
                          {headerName: 'Edit', cellRenderer:'editCellRenderer',width:100},
                          //{headerName: 'Delete', cellRenderer:'deleteCellRenderer',width: 80},
                           {headerName:'Payment Date',field: 'paymentdate',width:170},
                         
                          {headerName:'Payment Mode',field: 'payment_mode',cellRenderer:'agGroupCellRenderer',width:170},
                          {headerName:'Paid Amount',field: 'paid_amt'},
                           {headerName:'TDS Amount',field: 'tds_amt'},
                        ];
                        
                                  var gridOptions;
                                  gridOptions = {
                                  enableFilter:true,
                                  enableSorting: true,
                                  animateRows:true,

                                  columnDefs: columnDefs,
                                  masterDetail: true,
                                  components: {editCellRenderer : editCellRenderer},
                                  detailCellRendererParams: {
                                  detailGridOptions: {
                                  columnDefs: [
                                          {headerName:'Cheque no',field: 'cheque_no'},
                                          {headerName:'Bank Name',field: 'bank_name'},
                                          {headerName:'Bank Branch',field: 'bank_branch'},
                                          {headerName:'Cheque Date',field: 'cheque_date'}
                                              ],
                                    onGridReady: function(params) {
                                          params.api.sizeColumnsToFit();
                                    }
                                  },
                                    getDetailRowData: function (params) {
                                      var res = {
                                        data: []
                                      };
                                      res.data.push(params.data);
                                      params.successCallback(res.data);
                                    }

                                  },
                              
                                isRowMaster: function (dataItem) {
                                  return dataItem ? dataItem.cheque_no > 0 : false;
                                }
                            };
                             
                             //gridOptions.rowHeight = 20;
                             var gridDiv = document.getElementById('myGrid');
                             new agGrid.Grid(gridDiv,gridOptions); 
</script>

