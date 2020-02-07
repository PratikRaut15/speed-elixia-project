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
});



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

$('#cheque_no').on('input', function() {
          this.value = this.value
          .replace(/[^\d]/g, '')             // numbers only
});


function editPayment(){

		var pay_mode_check = pay_mode;

		if($("#new_payment_amount").val()==''){
	      alert("Enter Payment Amount");
	      return false;
     	}else if(pay_mode_check==1){
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
		    
		    else{			
		       var data = $("#invoice_payment_edit").serialize();
		       jQuery.ajax({
			        type: "POST",
			        url: "invoice_functions.php",
			        data: "&edit_payment=1&"+data+"&pay_mode_check="+pay_mode_check,
			        success: function (response) {
			          var response=JSON.parse(response);
			          if(response==1){
			            window.location.reload();
			          }
			        }
		        });
			}
		}
		

   		else if(pay_mode_check!=1){

		    	var data = $("#invoice_payment_edit").serialize();
		       	jQuery.ajax({
			        type: "POST",
			        url: "invoice_functions.php",
			        data: "&edit_payment=1&"+data,
			        success: function (response) {
			          var response=JSON.parse(response);
			          if(response==1){
			            window.location.reload();
			          }
			        }
		        });
   			
		}
		   
}