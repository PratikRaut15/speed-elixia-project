jQuery(document).ready(function () {  


      $("#payment_mode").change(function(){
      var payment_id = $('#payment_mode').val();
      
      if(payment_id==1){
        // alert (payment_id); return false;
        $("#cheque_payment").show();
        $('#cheque_no').on('input', function() {
          this.value = this.value
          .replace(/[^\d]/g, '')             // numbers only
        });
      }
      else if(payment_id==2){
        $("#cheque_payment").hide();
      }
      else if(payment_id==3){
        $("#cheque_payment").hide();
      }
      else{
        $("#cheque_payment").hide();
        alert("Please Select Payment Mode")
      }
  });




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
          source: "route_ajax.php?customername=getcustomer",
          select: function (event, ui) {
             jQuery(this).val(ui.item.value);
             jQuery('#customerno').val(ui.item.cid);
            var customerno = ui.item.cid;
            
            jQuery.ajax({
              
                type: "POST",
                url: "payment_collection_functions.php",
                data: "&get_invoices=1&customerno="+customerno,
                success: function(data){
                  var data=JSON.parse(data);
                  $('#invoiceno').html("");
                  $('#invoiceno').append('<option value = "0">'+"Select Invoice Number"+'</option>');
                  //<-------- add this line
                  $.each(data ,function(i,text){
                    $('#invoiceno').append('<option value = '+text.invoiceid+'>'+text.invoiceno+'</option>');
                  });
                 
                } 
            });    
          }
        });
  }


   function getCollectedBy() {

        jQuery("#collectedby").autocomplete({
          type:  "post",
          source: "payment_collection_functions.php?get_collectedby=1",
          select: function (event, ui) {
            jQuery(this).val(ui.item.value);
            var collectedby = ui.item.tid;
            $("#collectedby_id").val(collectedby);
          }
        });
  }

  
  function submitPaymentCollection(){
      var data = $("#payment_collection").serialize();
      // if($("#invoiceno").val()==0){
      //   alert("Please Select Invoice Number");
      //   return false;
      // }

      // if (!empty($("#payment_mode").val()) && $("#payment_mode").val()==1) {

      // if($("#cheque_status").val()==0){
      //   alert("Please Select Cheque Status");
      //   return false;
      // }
      // else if($("#cheque_number").val()==0){
      //   alert("Please Enter Cheque Number");
      //   return false;
      // }
      //  else if($("#bank_name").val()==''){
      //   alert("Please Enter Bank Name");
      //   return false;
      // }
      // else if($("#bank_branch").val()==''){
      //   alert("Please Enter Bank Branch");
      //   return false;
      // }
      // else if($("#cheque_date").val()==''){
      //   alert("Please Enter Cheque Date");
      //   return false;
      // }
      // }
      // if($("#paid_amount").val()==''){
      //   alert("Please Enter Paid Amount");
      //   return false;
      // }
      // else if($("#customerno").val()==''){
      //   alert("Please Enter Customer");
      //   return false;
      // }
      // else if($("#status").val()==0){
      //   alert("Please Select Status");
      //   return false;
      // }
      //  else if($("#collectedby").val()==0){
      //   alert("Please Select Collected By");
      //   return false;
      // }
      // else if($("#payment_mode").val()==0){
      //   alert("Please Select Payment Mode");
      //   return false;
      // }
      // else if($("#status").val()==0){
      //   alert("Please Select Status");
      //   return false;
      // }
      //  else if($("#cheque_status").val()==0){
      //   alert("Please Select Cheque Status");
      //   return false;
      // }
      // else if($("#cheque_number").val()==0){
      //   alert("Please Enter Cheque Number");
      //   return false;
      // }
      //  else if($("#bank_name").val()==''){
      //   alert("Please Enter Bank Name");
      //   return false;
      // }
      // else if($("#bank_branch").val()==''){
      //   alert("Please Enter Bank Branch");
      //   return false;
      // }
      //  else if($("#remark").val()==''){
      //   alert("Please Enter Remark");
      //   return false;
      // }


      if ($("#payment_mode").val()==1) {

      if($("#cheque_status").val()==0){
        alert("Please Select Cheque Status");
        return false;
      }
      else if($("#cheque_number").val()==0){
        alert("Please Enter Cheque Number");
        return false;
      }
       else if($("#bank_name").val()==''){
        alert("Please Enter Bank Name");
        return false;
      }
      else if($("#bank_branch").val()==''){
        alert("Please Enter Bank Branch");
        return false;
      }
      else if($("#cheque_date").val()==''){
        alert("Please Enter Cheque Date");
        return false;
      }
      }
      
      if($("#customerno").val()==''){
        alert("Please Select Customer");
        return false;
      }
      else if($("#paid_amount").val()==''){
        alert("Please Enter Paid Amount");
        return false;
      }
      else if($("#status").val()==0){
        alert("Please Select Status");
        return false;
      }
      else if($("#collectedby").val()==0){
        alert("Please Select Collected By");
        return false;
      }
      else if($("#payment_mode").val()==0){
        alert("Please Select Payment Mode");
        return false;
      }
      else if($("#status").val()==0){
        alert("Please Select Status");
        return false;
      }
      else if($("#status").val()==4) {
        if($("#remark").val()==''){
        alert("Please Enter Remark");
        return false;
      }
    }
        else{
          jQuery.ajax({
          type: "POST",
          url: "payment_collection_functions.php",
          data: "&new_payment=1&"+data,
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

      function editPayment(){
      var data = $("#payment_collection_edit").serialize();

      if ($("#payment_mode").val()==1) {

      if($("#cheque_status").val()==0){
        alert("Please Select Cheque Status");
        return false;
      }
      else if($("#cheque_number").val()==0){
        alert("Please Enter Cheque Number");
        return false;
      }
       else if($("#bank_name").val()==''){
        alert("Please Enter Bank Name");
        return false;
      }
      else if($("#bank_branch").val()==''){
        alert("Please Enter Bank Branch");
        return false;
      }
      else if($("#cheque_date").val()==''){
        alert("Please Enter Cheque Date");
        return false;
      }
      }

      if($("#status").val()==4) {
        if($("#remark").val()==''){
        alert("Please Enter Remark");
        return false;
      }
    }
      
      if($("#customerno").val()==''){
        alert("Please Select Customer");
        return false;
      }
      else if($("#paid_amount").val()==''){
        alert("Please Enter Paid Amount");
        return false;
      }
      else if($("#status").val()==0){
        alert("Please Select Status");
        return false;
      }
      else if($("#collectedby").val()==0){
        alert("Please Select Collected By");
        return false;
      }
      else if($("#payment_mode").val()==0){
        alert("Please Select Payment Mode");
        return false;
      }
      else if($("#status").val()==0){
        alert("Please Select Status");
        return false;
      }

        else{
          jQuery.ajax({
          type: "POST",
          url: "payment_collection_functions.php",
          data: "&edit_payment=1&"+data,
          success: function (response) {
            // console.log(response); return false;
            var response=JSON.parse(response);
            if(response==1){
              // header("location:payment_collection.php");
              // window.location="http://localhost/speed/modules/team/payment_collection.php";
              // window.location="http://uat-speed.elixiatech.com/modules/team/payment_collection.php";
              window.location="http://speed.elixiatech.com/modules/team/payment_collection.php";
              // window.location.reload();
            }
          }
          });
        }
      }

      function deletePayment(){
      var data = $("#payment_collection_edit").serialize();
      
          jQuery.ajax({
          type: "POST",
          url: "payment_collection_functions.php",
          data: "&delete_payment=1&"+data,
          success: function (response) {
            // console.log(response); return false;
            var response=JSON.parse(response);
            if(response==1){
              // header("location:payment_collection.php");
              // window.location="http://localhost/speed/modules/team/payment_collection.php";
              // window.location="http://uat-speed.elixiatech.com/modules/team/payment_collection.php";
              window.location.reload();
            }
          }
          });
        
      }

     function reset(){
     $('#payment_mode').val('');
     }
