jQuery(document).ready(function () {  

      jQuery('#payment_date').datepicker({
          dateFormat: "dd-mm-yy",
          language: 'en',
          autoclose: 1,
          startDate: Date()
      });

      jQuery('#cheque_date').datepicker({
          dateFormat: "dd-mm-yy",
          language: 'en',
          autoclose: 1,
          startDate: Date()
      });

      jQuery.ajax({
        type: "POST",
        url: "invoice_functions.php",
        data: "get_payment_mode=1",
          success: function(data){
            var data=JSON.parse(data);
            $('#payment_mode').html("");
            $('#payment_mode').append('<option value = '+"0"+'>'+"Select Payment Mode"+'</option>');
            //<-------- add this line
            $.each(data ,function(i,text){
              $('#payment_mode').append('<option value = '+text.pm_id+'>'+text.mode+'</option>');
              $("#payment_mode").selectedIndex=0;
            });
          }
      });
  });  


  function getCustomer() {

        jQuery("#customername").autocomplete({
          type:  "post",
          source: "invoice_functions.php?get_customer=1",
          select: function (event, ui) {
            var customerno = ui.item.customerno;
            $("#customerno").val(customerno);
            
            $("#invoice option").remove();
            $('#invoice').append('<option value = "0">'+"Select Pending Invoice"+'</option>');
            $("#paid_invoice option").remove();
            $('#paid_invoice').append('<option value = "0">'+"Select Paid Invoice"+'</option>');
            $("#Payment_Table").hide();

            jQuery.ajax({
              
                type: "POST",
                url: "invoice_functions.php",
                data: "&get_ledger=1&customerno="+customerno,
                success: function(data){
                  var data=JSON.parse(data);
                  $('#ledger').html("");
                  $('#ledger').append('<option value = "0">'+"Select Ledger"+'</option>');
                  //<-------- add this line
                  $.each(data ,function(i,text){
                    $('#ledger').append('<option value = '+text.ledgerid+'>'+text.ledgername+'</option>');
                  });
                  
                  $("#ledger").change(function(){
                    var ledgerid = $('#ledger').val();
                    
                    if(ledgerid>0){
                      jQuery.ajax({
                            type: "POST",
                            url: "invoice_functions.php",
                            data: "&get_invoices=1&ledgerid="+ledgerid,
                            success: function(data){
                              var data=JSON.parse(data);
                              //console.log(data);
                              $('#invoice').html("");
                              $('#invoice').append('<option value = "0">'+"Select Pending Invoice"+'</option>');
                              //<-------- add this line
                              $.each(data ,function(i,text){
                                $('#invoice').append('<option value = '+text.invoiceid+'>'+text.invoiceno+'</option>');
                              });


                              $("#invoice").change(function(){ 
                                  $("#paid_invoice")[0].selectedIndex=0; 
                                  var invoiceid = $('#invoice').val();
                                  var invoiceno = $('#invoice :selected').text();

                                  $("#payment_invoice_id").val(invoiceid);
                                  $("#payment_invoice_no").val(invoiceno);

                                  if(invoiceid!=0){
                                    jQuery.ajax({
                                      type: "POST",
                                      url: "invoice_functions.php",
                                      data: "&get_payment_invoices=1&invoiceid="+invoiceid,
                                      success: function(data){
                                        result = JSON.parse(data);
                                        gridOptions.api.setRowData(result);
                                             
                                        $("#Payment_Table").show();
                                        jQuery.ajax({
                                            type: "POST",
                                            url: "invoice_functions.php",
                                            data: "&get_sub_payment_details=1&invoiceid="+invoiceid,
                                            success: function(data){

                                                  var result_1 = JSON.parse(data);
                                                 
                                                  $("#invoice_amount").val(result_1[0].InvoiceAmountParam);
                                                  $("#payment_amount").val(result_1[0].TotalPaidAmountParam);
                                                  $("#pending_amount").val(result_1[0].TotalPendingAmountParam);
                                                  $("#tds_amount").val(result_1[0].TotalTdsAmountParam);
                                                  $("#unpaid_amt").val(result_1[0].TotalUnpaid_AmountParam);
                                            }
                                        });  
                                      }
                                    });
                                    jQuery.ajax({
                                      type: "POST",
                                      url: "invoice_functions.php",
                                      data: "&get_payment_invoices_old=1&invoiceid="+invoiceid,
                                      success: function(data){
                                        result_old = JSON.parse(data);
                                        gridOptions1.api.setRowData(result_old);
                                  
                                      }
                                    });
                                  }
                                  else{
                                    $("#Payment_Table").hide();
                                    alert("Please Select Correct Invoice");
                                  }
                              });
                            }                               
                      });                                   

                      jQuery.ajax({
                            type: "POST",
                            url: "invoice_functions.php",
                            data: "&get_paid_invoices=1&ledgerid="+ledgerid,
                            success: function(data){
                              var data=JSON.parse(data);
                              //console.log(data);
                              $('#paid_invoice').html("");
                              $('#paid_invoice').append('<option value = "0">'+"Select Paid Invoice"+'</option>');
                              //<-------- add this line
                              $.each(data ,function(i,text){
                                $('#paid_invoice').append('<option value = '+text.invoiceid+'>'+text.invoiceno+'</option>');
                              });


                              $("#paid_invoice").change(function(){ 

                                $("#invoice")[0].selectedIndex=0;  

                                var paid_invoiceid = $('#paid_invoice').val();
                                var paid_invoiceno = $('#paid_invoice :selected').text();

                                 $("#payment_invoice_id").val(paid_invoiceid);
                                 $("#payment_invoice_no").val(paid_invoiceno);

                                if(paid_invoiceid!=0){
                                  jQuery.ajax({
                                  type: "POST",
                                  url: "invoice_functions.php",
                                  data: "&get_payment_paid_invoices=1&paid_invoiceid="+paid_invoiceid,
                                  success: function(data){
                                      result_2 = JSON.parse(data);
                                      gridOptions.api.setRowData(result_2);
                                      $("#Payment_Table").show();
                                      jQuery.ajax({
                                        type: "POST",
                                        url: "invoice_functions.php",
                                        data: "&get_sub_payment_details=1&invoiceid="+paid_invoiceid,
                                        success: function(data){
                                          var result_3 = JSON.parse(data);
                                          $("#invoice_amount").val(result_3[0].InvoiceAmountParam);
                                          $("#payment_amount").val(result_3[0].TotalPaidAmountParam);
                                          $("#pending_amount").val(result_3[0].TotalPendingAmountParam);
                                          $("#tds_amount").val(result_3[0].TotalTdsAmountParam);
                                          $("#unpaid_amt").val(result_3[0].TotalUnpaid_AmountParam);

                                        }
                                      });  
                                  }
                                  });
                                  jQuery.ajax({
                                      type: "POST",
                                      url: "invoice_functions.php",
                                      data: "&get_payment_invoices_old=1&invoiceid="+paid_invoiceid,
                                      success: function(data){
                                        result_old = JSON.parse(data);
                                        gridOptions1.api.setRowData(result_old);
                                  
                                      }
                                    });
                                }
                                else{
                                  $("#Payment_Table").hide();
                                  alert("Please Select Correct Invoice");
                                }
                              });
                            } 
                      });                                   
                    }
                    else{
                      $("#invoice option").remove();
                      $('#invoice').append('<option value = "0">'+"Select Pending Invoice"+'</option>');
                      $("#paid_invoice option").remove();
                      $('#paid_invoice').append('<option value = "0">'+"Select Paid Invoice"+'</option>');
                      $("#Payment_Table").hide();
                    }
                  });
                } 
            });    
          }
        });
  }


  $("#payment_mode").change(function(){
      $('#new_payment_amount').off('input');
      $('#new_tds').off('input');

      var payment_id = $('#payment_mode').val();
      if(payment_id==1){
        $("#check_payment").show();
        $('#new_payment_amount').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });

        $('#new_tds').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });

        $('#new_unpaid_amt').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });

        $('#cheque_no').on('input', function() {
          this.value = this.value
          .replace(/[^\d]/g, '')             // numbers only
        });
      }
      else if(payment_id==2){
        $("#check_payment").hide();
          $('#new_payment_amount').on('input', function() {
          this.value = this.value
          .replace(/[^\d]/g, '')             // numbers only
          .replace(/\./g, '');
        });

        $('#new_tds').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });

        $('#new_unpaid_amount').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });
      }
      else if(payment_id==3){
        $("#check_payment").hide();
        $('#new_payment_amount').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });

        $('#new_tds').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });

        $('#new_unpaid_amount').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });
      }
      else{
        $("#check_payment").hide();
        alert("Please Select Payment Mode")
      }
  });

  function submitPayment(){
      var data = $("#invoice_payment").serialize();
      
      if($("#customername").val()==''){
        alert("Please Select Customer");
        return false;
      }

      else if($("#ledger").val()==0){
        alert("Please Select Ledger");
        return false;
      }

      else if($("#invoice").val()==0 && $("#paid_invoice").val()==0){
        alert("Please Select Invoice");
        return false;
      }
      else if($("#payment_mode").val()==0){
        alert("Please Select Payment Mode");
        return false;
      }

      else if($("#payment_mode").val()==1){
        if($("#cheque_no").val()==''){
          alert("Enter Cheque No.");
          return false;
        }
        else if($("#bank_name").val()==''){
          alert("Enter Bank Name");
          return false;
        }
        else if($("#bank_branch").val()==''){
          alert("Enter Bank Branch");
          return false;
        }
        else if($("#new_payment_amount").val()==''){
          alert("Enter Payment Amount");
          return false;
        }
        else{
          jQuery.ajax({
          type: "POST",
          url: "invoice_functions.php",
          data: "&new_payment=1&"+data,
          success: function (response) {
            var response=JSON.parse(response);
            if(response==1){
              window.location.reload();
            }
          }
          });
        }
      }
      else if($("#payment_mode").val()==2 || $("#payment_mode").val()==3){
        if($("#new_payment_amount").val()==''){
          alert("Enter Payment Amount");
          return false;
        }
        else{
          jQuery.ajax({
            type: "POST",
            url: "invoice_functions.php",
            data: "&new_payment=1&"+data,
            success: function (response) {
              var response=JSON.parse(response);
              if(response==1){
                window.location.reload();
              }
            }
            });
        }
      }
  }
