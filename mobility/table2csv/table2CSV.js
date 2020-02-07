var table_name="report";

function table2CSV(el,options,r_name) {
    
		jQuery('#left_menu').hide();

		jQuery('#show_menu').show();

		jQuery('body').addClass('nobg');

		jQuery('#content').css('marginLeft', 30);

		jQuery('#wysiwyg').css('width', '97%');

		setNotifications();
   // alert(r_name);
    if(r_name!=="undefined" || r_name!=""){
        table_name=r_name;
    }

    
    var options = jQuery.extend({
        separator: ',',
        header: [],
        delivery: 'popup' // popup, value
    },
    options);

    var csvData = [];
    var headerArr = [];
    
    //header
    var numCols = options.header.length;
    var tmpRow = []; // construct header avalible array

    if (numCols > 0) {
        for (var i = 0; i < numCols; i++) {
            tmpRow[tmpRow.length] = formatData(options.header[i]);
        }
    } else {
        jQuery(el).filter(':visible').find('th').each(function() {
            if (jQuery(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData(jQuery(this).html());
        });
    }

   // row2CSV(tmpRow);

    // actual data
    jQuery(el).find('tr').each(function() {
        var tmpRow = [];
        jQuery(this).filter(':visible').find('td').each(function() {
            if (jQuery(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData(jQuery(this).html());
        });
        row2CSV(tmpRow);
    });
    if (options.delivery == 'popup') {
        var mydata = csvData.join('\n');
        return popup(mydata,table_name);
    } else {
        var mydata = csvData.join('\n');
        return mydata;
    }

    function row2CSV(tmpRow) {
        var tmp = tmpRow.join('') // to remove any blank rows
        // alert(tmp);
        if (tmpRow.length > 0 && tmp != '') {
            var mystr = tmpRow.join(options.separator);
            csvData[csvData.length] = mystr;
        }
    }
    function formatData(input) {
        // replace " with â€œ
        var regexp = new RegExp(/["]/g);
        var output = input.replace(regexp, "â€œ");
        //HTML
        var regexp = new RegExp(/\<[^\<]+\>/g);
        var output = output.replace(regexp, "");
        if (output == "") return '';
        return '"' + output + '"';
    }
    function popup(dataa,table_name) {
		//call ajax here.
		jQuery.ajax({ url: "savecsv.php",
			   type: "POST",
 			   data: ({csv : dataa}),
      		   dataType: "html",
			   success: function(){
        		window.location="download_csv.php?reportname="+table_name;
      }});
		
 /*       var generator = window.open('', 'csv', 'height=400,width=600');
        generator.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/csv; charset=iso-8859-1" />');
        generator.document.write('</head><body >');
        generator.document.write(data);
        generator.document.write('</body></html>');
		generator.document.close();
        return true;*/
    }
};