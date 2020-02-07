jQuery(document).ready(function () {

});

function getInformatics() {
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#infoForm").serialize();
    jQuery.ajax({
        url: "informatics_ajax_new.php",
        type: 'POST',
        data: data,
        success: function (result) {
            jQuery("#centerDiv").html(result);
        },
        complete: function () {
            jQuery('#pageloaddiv').hide();
        }
    });
}

function get_InformaticsDownload(customerno, downloadType, reportType, e) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));

    if (sdate == '') {
        alert('Please select startdate');
    }
    else if (edate == '') {
        alert('Please select enddate');
    }
    else if (datediff > 30) {
        alert('Date difference not more than 30 days');
    }
    else if (datediff <= 30) {
        if (reportType === 1) {
            if (downloadType == 'pdf') {
                window.open('informatics_pdf_table_new.php?SDate=' + sdate + '&EDate=' + edate + '&customerno=' + customerno + '&to_get=get_informatics_report', '_blank');
            }
            else if (downloadType == 'xls') {
                window.open('informatics_excel_table_new.php?SDate=' + sdate + '&EDate=' + edate + '&customerno=' + customerno + '&to_get=get_informatics_report', '_blank');
            }
            else {
                alert('Invalid Type');
            }
        }
        else if (reportType === 2) {
            if (downloadType == 'pdf') {
                window.open('informatics_pdf_table_new.php?SDate=' + sdate + '&EDate=' + edate + '&customerno=' + customerno + '&to_get=get_summarized_report', '_blank');
            }
            else if (downloadType == 'xls') {
                window.open('informatics_excel_table_new.php?SDate=' + sdate + '&EDate=' + edate + '&customerno=' + customerno + '&to_get=get_summarized_report', '_blank');
            }
            else {
                alert('Invalid Type');
            }
        }
    }
}
function daydiff(first, second) {
    return (second - first) / (1000 * 60 * 60 * 24);
}
function parseDate(str) {
    var mdy = str.split('-');
    return new Date(mdy[2], mdy[1], mdy[0] - 1);
}