
function FuelReport(){
    var stdate = jQuery("#SDate").val();
    var etdate = jQuery("#EDate").val();
    var dealerid = jQuery("#dealerid").val();
    var vehid = jQuery("#vehicleid").val();
    var vno = jQuery("#vehicleno").val();
    var data = 'STdate=' + stdate + '&ETdate=' + etdate + '&dealerid=' + dealerid + '&vehicleid=' + vehid + '&vehicleno=' + vno;
    
    var newsdate = stdate.split("-").reverse().join("-");
    var newedate = etdate.split("-").reverse().join("-");

    if (Date.parse(newsdate) > Date.parse(newedate)){
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    }else{
        //jQuery('#pageloaddiv').show();
        var dataString = "table_header=1&" + data;
        jQuery.ajax({
            url: 'reports_fuelhistory_ajax.php',
            type: 'POST',
            cache: false,
            data: dataString,
            success: function (html) {
                jQuery("#fuelRepdiv").show();
                if (jQuery("#tableheaderdiv").html().length > 0) {
                    jQuery("#tableheaderdiv").html("");
                }
                jQuery("#tableheaderdiv").append(html);
            },
            complete: function () {
                DataTableAjax(data);
            }
        });
    }
}

function DataTableAjax(data) {
    var dataString = "filter=1&" + data;
    var viewtransactionDataTable = null;
    jQuery.ajax({
        url: 'reports_fuelhistory_ajax.php',
        type: 'POST',
        cache: false,
        data: dataString,
        success: function (result) {
            jQuery("#fuelRepdiv").show();
            var transactionsList = jQuery.parseJSON(result);
            var tableId = 'fuel_report';
            var tableCols = [
                {"mData": "srno"}
                , {"mData": "vehicleno"}
                , {"mData": "transid"}
                , {"mData": "seatcapacity"}
                , {"mData": "fuel"}
                , {"mData": "amount"}
                , {"mData": "rate"}
                , {"mData": "refno"}
                , {"mData": "openingkm"}
                , {"mData": "endingkm"}
                , {"mData": "netkm"}
                , {"mData": "average"}
                , {"mData": "dealername"}
                , {"mData": "addedon"}
                , {"mData": "additional_amount"}
                , {"mData": "notes"}
                , {"mData": "ofasnumber"}
                , {"mData": "chequeno"}
                , {"mData": "chequeamt"}
                , {"mData": "chequedate"}
                , {"mData": "tdsamt"}
            ];
            viewtransactionDataTable = CreateDataTable(transactionsList, tableId, tableCols, 10);

        },
        complete: function () {
            jQuery('#pageloaddiv').hide();
        }
    });
}
function CreateDataTable(data, tableId, tableCols, pageLength) {
    var oTable = $('#' + tableId + '').DataTable({
        "destroy": true,
        "stateSave": true,
        "responsive": true,
        "processing": true,
        "paging": true,
        "data": data,
        "columns": tableCols,
        "order": [],
        "iDisplayLength": pageLength,
        "emptyTable": "No data found",
    });

    //Add filter columns
    $("thead input").keyup(function () {
        oTable.columns($('#' + tableId + '' + ' thead input').index(this)).search($(this).val()).draw();
    });
    return oTable;
}

function daydiff(first, second) {
    return (second - first) / (1000 * 60 * 60 * 24)
}
function parseDate(str) {
    var mdy = str.split('-')
    return new Date(mdy[2], mdy[1], mdy[0] - 1);
}
function fuel_hist_maintenance(){
    var vehicleno = jQuery("#vehicleno").val();
    var vehicleid = jQuery("#vehicleid").val();
    var dealer = jQuery("#dealerid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    
    var datastring = "";
    
    var srno_filter='';
    srno_filter = jQuery("#srno_filter").val();
    
    var vehicleno_filter = '';
    vehicleno_filter = jQuery("#vehicleno_filter").val();
    
    var transactionid_filter='';
    transactionid_filter = jQuery("#transactionid_filter").val();
    
    var seatcapacity_filter='';
    seatcapacity_filter = jQuery("#seatcapacity_filter").val();
    
    var fuel_filter='';
    fuel_filter = jQuery("#fuel_filter").val();
    
    var amount_filter='';
    amount_filter = jQuery("#amount_filter").val();
        
    var rate_filter ='';
    rate_filter = jQuery("#rate_filter").val();
   
    var refno_filter='';
    refno_filter = jQuery("#refno_filter").val();
    
    var startkm_filter='';
    startkm_filter = jQuery("#startkm_filter").val();
    
    var endkm_filter='';
    endkm_filter = jQuery("#endkm_filter").val();
    
    var netkm_filter ='';
    netkm_filter = jQuery("#netkm_filter").val();
    
    var average_filter='';
    average_filter = jQuery("#average_filter").val();
    
    var dealer_filter='';
    dealer_filter = jQuery("#dealer_filter").val();
    
    var date_filter='';
    date_filter = jQuery("#date_filter").val();
    
    var additional_amount_filter='';
    additional_amount_filter = jQuery("#additional_amount_filter").val();
    
    var notes_filter='';
    notes_filter = jQuery("#notes_filter").val();
    var ofasnumber_filter='';
    ofasnumber_filter = jQuery("#ofasnumber_filter").val();
    
    
    var chequeno_filter='';
    chequeno_filter = jQuery("#chequeno_filter").val();
    
    var chequeamount_filter='';
    chequeamount_filter = jQuery("#chequeamount_filter").val();
    
    var chequedate_filter='';
    chequedate_filter = jQuery("#chequedate_filter").val();
    
    var tdsamount_filter='';
    tdsamount_filter = jQuery("#tdsamount_filter").val();
    
    
    
    var sortInfo = $("#fuel_report").dataTable().fnSettings().aaSorting;
    var sortdetails = new Array();
    sortdetails = sortInfo.toString().split(",");
    var sortedcol='';
    sortedcol = sortdetails[0];
    var sortorder='';
    sortorder = sortdetails[1];
    if(sortorder===undefined){
        sortorder="";
    }
    
    var datastring = "sortorder="+sortorder+"&sortcol="+sortedcol+"&srnof="+srno_filter+"&vehiclenof="+vehicleno_filter+"&transactionidf="+transactionid_filter+"&seatcapacityf="+seatcapacity_filter+"&fuelf="+fuel_filter+"&amountf="+amount_filter+"&ratef="+rate_filter+"&&refnof="+refno_filter+"&startkmf="+startkm_filter+"&endkmf="+endkm_filter+"&netkmf="+netkm_filter+"&averagef="+average_filter+"&dealerf="+dealer_filter+"&datef="+date_filter+"&addamtf="+additional_amount_filter+"&notesf="+notes_filter+"&ofasnumberf="+ofasnumber_filter+"&chequenof="+chequeno_filter+"&chequeamountf="+chequeamount_filter+"&chequedatef="+chequedate_filter+"&tdsamountf="+tdsamount_filter;
    
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (datediff < 0) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    }else{
        window.open('savexls.php?report=fuelHistMaintenance&vehicleid=' + vehicleid + '&vehno=' + vehicleno + '&EDdate=' + edate + '&STdate=' + sdate + '&dealer=' + dealer+'&'+datastring, '_blank');
    }
}

function fuel_hist_maintenance_pdf() {
    var vehicleno = jQuery("#vehicleno").val();
    var vehicleid = jQuery("#vehicleid").val();
    var dealer = jQuery("#dealerid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datastring = "";
    
    
    var srno_filter='';
    srno_filter = jQuery("#srno_filter").val();
        
    var vehicleno_filter = '';
    vehicleno_filter = jQuery("#vehicleno_filter").val();
    
    var transactionid_filter='';
    transactionid_filter = jQuery("#transactionid_filter").val();
    
    var seatcapacity_filter='';
    seatcapacity_filter = jQuery("#seatcapacity_filter").val();
    
    var fuel_filter='';
    fuel_filter = jQuery("#fuel_filter").val();
    
    var amount_filter='';
    amount_filter = jQuery("#amount_filter").val();
        
    var rate_filter ='';
    rate_filter = jQuery("#rate_filter").val();
   
    var refno_filter='';
    refno_filter = jQuery("#refno_filter").val();
    
    var startkm_filter='';
    startkm_filter = jQuery("#startkm_filter").val();
    
    var endkm_filter='';
    endkm_filter = jQuery("#endkm_filter").val();
    
    var netkm_filter ='';
    netkm_filter = jQuery("#netkm_filter").val();
    
    var average_filter='';
    average_filter = jQuery("#average_filter").val();
    
    var dealer_filter='';
    dealer_filter = jQuery("#dealer_filter").val();
    
    var date_filter='';
    date_filter = jQuery("#date_filter").val();
    
    var additional_amount_filter='';
    additional_amount_filter = jQuery("#additional_amount_filter").val();
    
    var notes_filter='';
    notes_filter = jQuery("#notes_filter").val();
    
    var ofasnumber_filter='';
    ofasnumber_filter = jQuery("#ofasnumber_filter").val();
    
    var chequeno_filter='';
    chequeno_filter = jQuery("#chequeno_filter").val();
    
    var chequeamount_filter='';
    chequeamount_filter = jQuery("#chequeamount_filter").val();
    
    var chequedate_filter='';
    chequedate_filter = jQuery("#chequedate_filter").val();
    
    var tdsamount_filter='';
    tdsamount_filter = jQuery("#tdsamount_filter").val();
    
    
    
    var sortInfo = $("#fuel_report").dataTable().fnSettings().aaSorting;
    var sortdetails = new Array();
    sortdetails = sortInfo.toString().split(",");
    var sortedcol='';
    sortedcol = sortdetails[0];
    var sortorder='';
    sortorder = sortdetails[1];
    if(sortorder===undefined){
        sortorder="";
    }
    
    var datastring = "sortorder="+sortorder+"&sortcol="+sortedcol+"&srnof="+srno_filter+"&vehiclenof="+vehicleno_filter+"&transactionidf="+transactionid_filter+"&seatcapacityf="+seatcapacity_filter+"&fuelf="+fuel_filter+"&amountf="+amount_filter+"&ratef="+rate_filter+"&&refnof="+refno_filter+"&startkmf="+startkm_filter+"&endkmf="+endkm_filter+"&netkmf="+netkm_filter+"&averagef="+average_filter+"&dealerf="+dealer_filter+"&datef="+date_filter+"&addamtf="+additional_amount_filter+"&notesf="+notes_filter+"&ofasnumberf="+ofasnumber_filter+"&chequenof="+chequeno_filter+"&chequeamountf="+chequeamount_filter+"&chequedatef="+chequedate_filter+"&tdsamountf="+tdsamount_filter;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (datediff < 0) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    }
    else {
        window.open('pdftest.php?report=FuelHistMaintenancePdf&vehicleid=' + vehicleid + '&vehno=' + vehicleno + '&EDdate=' + edate + '&STdate=' + sdate + '&dealer=' + dealer+'&'+datastring, '_blank');
    }
}


function fuel_hist_maintenance_mail(customerno) {
    var vehicleno = jQuery("#vehicleno").val();
    var vehicleid = jQuery("#vehicleid").val();
    var dealer = jQuery("#dealerid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var emailid = jQuery("#sentoEmail").val();
    var body = jQuery("#mailcontent").val();
    var mail_type = jQuery('input[name=emailtype]:checked').val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (datediff < 0) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    }
    else {
        var dataString = 'report=FuelHistMaintenanceMail&vehicleid=' + vehicleid + '&vehno=' + vehicleno + '&EDdate=' + edate + '&STdate=' + sdate + '&dealer=' + dealer + '&customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + body + '&mailType=' + mail_type;
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function (result) {
                jQuery("#mailStatus").html(result);
            }
        });
    }
}


