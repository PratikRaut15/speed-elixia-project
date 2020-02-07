<?php
include_once("session.php");
include("loginorelse.php");
include("../../lib/bo/DocketManager.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/objectdatagrid.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i"); 
$teamid = GetLoggedInUserId();
include("header.php");
?>
 <?php
$dockets = Array();
$docketids = array ();

$dObj = new DocketManager;

$result = $dObj->fetchDocketInfo($teamid); 
$docketObj = new stdClass();
$srno = 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<script> var __basePath = ''; </script>
<style> html, body { margin: 0; padding: 0; height: 100%; } </style>
    <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>

</head>
<body>
<h3 style="text-align: center;">My Dockets</h3>
<div id="myGrid" class="ag-theme-blue" style="height:90%;width:100%;margin:0 auto;"></div>
</body>
</html>

<script>
var purposeArray=[];
jQuery.ajax({
    type: "POST",
    url: "Docket_functions.php",
    data: "get_purpose=1",
    async:false,
    success: function(data){
        var data=JSON.parse(data);
        $.each(data ,function(i,text){
            //console.log(text);
            purposeArray[parseInt(text.purpose_id)]=text.purpose_type;
        });
        //console.log(purposeArray[1]);
    }
});
//console.log(purposeArray);
// var dockets=<?php echo json_encode($dockets);?>;
var ticket;
var row=[];
var rowData=[];
var obj={};
var purposeStr='';
var tflag=true,bflag=true,dflag=true;
var rows;
jQuery(document).ready(function () {
    rows=<?php echo json_encode($result);?>; 
    var docketids = Object.keys(rows);
    var ticketObj = {};
    $.each(docketids,function(i,record){
        dflag=true;
        purposeStr='';
        row=[];
        participants=[];
        participants.records=[];
        ticketObj={};
        ticketObj.records=[];
        bucketObj={};
        bucketObj.records=[];   
        if(rows[record].tickets!=undefined){
            tflag=true;
            //console.log(record);
            leafData = {};
            $.each(rows[record].tickets,function(j,ticket){
                leafData={};
                leafData.priority = ticket.priority;
                leafData.id = ticket.ticketid;
                leafData.title = ticket.title;
                leafData.docketid = ticket.docketid;
                leafData.purpose = ticket.tickettype;
                leafData.allotTo = ticket.allot_to;
                leafData.date = ticket.create_on_date;
                leafData.status = ticket.ticketStatus;
                if(leafData.status != 'Resolved'){
                    tflag = false;
                }
                leafData.eclosedate = ticket.eclosedate;
                ticketObj.records.push(leafData);
            });
            ticketObj.docket="Tickets";
            if(tflag){
               ticketObj.status="Successful"; 
            }else{
                ticketObj.status="Unsuccessful";
                dflag=false;
            }
            if(row.participants==undefined){
                row.participants = [];
            }
            row.participants.push(ticketObj);
        }
        if(rows[record].buckets!=undefined){
            bflag=true;
            leafData = {};
            $.each(rows[record].buckets,function(j,bucket){
                leafData={};
                leafData.priority = bucket.priority;
                leafData.id = bucket.bucketid;
                leafData.title = bucket.purposeid;
                leafData.docketid = bucket.docketid;
                leafData.purpose = bucket.purposeid;
                leafData.allotTo = bucket.name;
                leafData.date = bucket.create_timestamp;
                leafData.status = bucket.status
                leafData.statusCheck=bucket.statusCheck;
                if(leafData.statusCheck==0 || leafData.statusCheck==4){
                    bflag=false;
                }
                leafData.eclosedate = bucket.apt_date;
                bucketObj.records.push(leafData);
            });
            bucketObj.docket="Buckets";
            if(bflag){
                bucketObj.status="Successful";
            }else{
                bucketObj.status="Unsuccessful";
                dflag=false;
            }
            if(row.participants==undefined){
                row.participants = [];
            }
            row.participants.push(bucketObj);
        }
        if(dflag){
            row.status = "Successful";
        }else{
            row.status = "Unsuccessful";
        }
        row.records=[];
        row.docket = "D00"+rows[record].info.docketid;
        row.customer=rows[record].info.customername;
        var purpose = rows[record].info.purpose_id.split(',');
        //console.log(rows[record].info);
        $.each(purpose,function(i,p){
            if(purposeStr!=''){
                purposeStr+=","+purposeArray[parseInt(p)];
            }else{
                purposeStr=purposeArray[parseInt(p)];
            }
        });
        row.purpose=purposeStr;
        row.createby = rows[record].info.create_name;
        row.allotTo=rows[record].info.name;
        row.timestamp = rows[record].info.timestamp;
        rowData.push(row);
    });
    //console.log(rowData);
});
var columnDefs = [
    {headerName:'Docket',field: 'docket', cellRenderer:'agGroupCellRenderer' , 
        cellRendererParams: {
            suppressCount: true,
            innerRenderer: 'simpleCellRenderer'
        }, sortingOrder: ['desc'], comparator: docketNumberComparator,suppressFilter:true},
    {headerName:'Customer',field: 'customer',filter: 'agTextColumnFilter'},
    {headerName:'Purpose',field: 'purpose',filter: 'agTextColumnFilter'},
    {headerName:'Status',field: 'status',filter: 'agTextColumnFilter'},
    {headerName:'Created by',field: 'createby',filter: 'agTextColumnFilter'},
    {headerName:'Allotted to',field: 'allotTo',filter: 'agTextColumnFilter'},
    {headerName: 'Created on', field:'timestamp',filter:'agDateColumnFilter', filterParams:{
        comparator:function (filterLocalDateAtMidnight, cellValue){
            var dateAsString = cellValue;
            var dateParts  = dateAsString.split("-");
            var cellDate = new Date(Number(dateParts[2]), Number(dateParts[1]) - 1, Number(dateParts[0]));

            if (filterLocalDateAtMidnight.getTime() === cellDate.getTime()) {
                return 0
            }

            if (cellDate < filterLocalDateAtMidnight) {
                return -1;
            }

            if (cellDate > filterLocalDateAtMidnight) {
                return 1;
            }
        }
    }},
];
function getSimpleCellRenderer() {  
    function SimpleCellRenderer() {}

    SimpleCellRenderer.prototype.init = function(params) {
        var tempDiv = document.createElement('div');
        //console.log(params);
        if (params.node.level==0) {
            //console.log("group");
            tempDiv.innerHTML = '<a href="edit_docket.php?docketid='+params.value.replace("D00","")+'">' + params.value + '</a>';
        }else{
            tempDiv.innerHTML = params.value;
        }
        this.eGui = tempDiv.firstChild;
    };

    SimpleCellRenderer.prototype.getGui = function() {
        return this.eGui;
    };

    return SimpleCellRenderer;
}
function getNodeChildDetails(rowItem) {
    if (rowItem.participants) {
        return {
            group:true,
            children: rowItem.participants,
            key: rowItem.group
        };
    } else {
        return null;
    }
}
function docketNumberComparator(docket1,docket2){
    var d1 = parseInt(docket1.substring(3));
    var d2 = parseInt(docket2.substring(3));
    return d2-d1;
}
var gridOptions = {
    components:{
        simpleCellRenderer: getSimpleCellRenderer()
    },
    enableFilter:true,
    pagination: true,
    enableSorting: true,
    rowData:rowData,
    suppressCount:true,
    floatingFilter:true,
    animateRows: true,
    columnDefs: columnDefs,
    getNodeChildDetails: getNodeChildDetails,
    masterDetail : true,
    detailCellRendererParams: {
        detailGridOptions: {
            columnDefs: [
                {headerName: 'ID', field:'id'},
                {headerName: 'Title', field:'title'},
                {headerName: 'Purpose', field:'purpose'},
                {headerName: 'Status', field:'status'},
                {headerName: 'Date of creation', field:'date'},
                {headerName: 'Priority', field:'priority'}
            ],
            onGridReady: function(params) { 
                params.api.sizeColumnsToFit();
            }
        },
        getDetailRowData: function(params) {
            params.successCallback(params.data.records);
        },

    },isRowMaster: function (dataItem) {
        return dataItem ? dataItem.records.length > 0 : false;
    },
};
$(document).ajaxStop(function() {
    //console.log(purposeArray);
});
document.addEventListener('DOMContentLoaded', function() {
    var gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    
});
agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
</script>   