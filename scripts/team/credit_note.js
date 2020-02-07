
function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
               var customerno = ui.item.cid;

                jQuery.ajax({
                type: "POST",
                url: "ledger_ajax.php",
                cache: false,
                data: {
                    work: "getMappedLedger", custno: customerno
                },
                success: function(data){
                var data=JSON.parse(data);
                $('#ledger_name').html("");
                $('#ledger_name').append('<option value = "0">'+"Select Ledger"+'</option>');
                //<-------- add this line
                $.each(data ,function(i,text){
                  $('#ledger_name').append('<option value = '+text.ledgerid+'>'+text.ledgerid+'-'+text.ledgername+'</option>');
                });

                $("#ledger_name").change(function(){
                    var ledgerid = $('#ledger_name').val();

                    if(ledgerid>0){
                      jQuery.ajax({
                            type: "POST",
                            url: "credit_note_functions.php",
                            data: "&get_invoices=1&ledgerid="+ledgerid,
                            success: function(data){
                              var data=JSON.parse(data);
                              $('#invoiceno').html("");
                              $('#invoiceno').append('<option value = "0">'+"Select Invoice "+'</option>');
                              //<-------- add this line
                              $.each(data ,function(i,text){
                                $('#invoiceno').append('<option value = '+text.invoiceid+'>'+text.invoiceno+'</option>');

                              });

                               $("#invoiceno").change(function(){
                                var invoiceid = $('#invoiceno').val();
                                jQuery.ajax({
                                type: "POST",
                                url: "credit_note_functions.php",
                                data: "&get_invoices=1&invoiceid="+invoiceid,
                                success: function(data){
                                  var data=JSON.parse(data);
                                  $('#inv_amount').html("");
                                  $('#inv_amount').append('<label>Invoice Amount</label>');
                                  //<-------- add this line
                                  $.each(data ,function(i,text){
                                    // $('#inv_amount').append('<option value = '+text.invoiceid+'>'+text.inv_amt+'</option>');
                                    // $('#inv_date').append('<option value = '+text.invoiceid+'>'+text.inv_date+'</option>');
                                    $('#inv_amount').val(text.inv_amt);
                                    $('#inv_date').val(text.inv_date);

                                  });

                                  }
                                });
                          });                               
                      }
                    
                  });

                }
            });
          }
      });
    }
  });
};

  
  function submitCreditNote(){
      var data = $("#credit_note").serialize();
      // alert(data); return false;
      if($("#customername").val()==''){
        alert("Please Select Customer");
        return false;
      } else if($("#ledger_name").val()==0){
        alert("Please Select Ledger");
        return false;
      } else if($("#invoiceno").val()==0){
        alert("Please Select Invoice Number");
        return false;
      } else if($("#reason").val()==''){
        alert("Please Enter Reason");
        return false;
      } else if($("#status").val()==0){
        alert("Please Enter Status");
        return false;
      }
        else{
          jQuery.ajax({
          type: "POST",
          url: "credit_note_functions.php",
          data: "&new_creditNote=1&"+data,
          success: function (response) {
            // console.log(response); return false;
            var response=JSON.parse(response);
            if(response==1){
              window.location.reload();
            }
          }
          });
        }
      }

      function updateCreditNote(){
      var data = $("#update_credit_note").serialize();
      // status=$("#edit_status").val();
      // alert(status); return false;
      if($("#customername").val()==''){
        alert("Please Select Customer");
        return false;
      } else if($("#ledger_name").val()==0){
        alert("Please Select Ledger");
        return false;
      } else if($("#invoiceno").val()==0){
        alert("Please Select Invoice Number");
        return false;
      } else if($("#reason").val()==''){
        alert("Please Enter Reason");
        return false;
      } else if($("#edit_status").val()==0){
        alert("Please Enter Status");
        return false;
      }
        else{
          jQuery.ajax({
          type: "POST",
          url: "credit_note_functions.php",
          data: "&update_creditNote=1&"+data,
          success: function (response) {
            // console.log(response); return false;
            var response=JSON.parse(response);
            if(response==1){
              // window.location.reload();
              // window.location="http://localhost/speed/modules/team/credit_note.php";
              // window.location="http://uat-speed.elixiatech.com/modules/team/credit_note.php";http://speed.elixiatech.com/
              window.location="http://speed.elixiatech.com/modules/team/credit_note.php";
            }
          }
          });
        }
      }

 function reset(){
     $('#edit_status').val('');
     }