jQuery(document).ready(function () {  
});  

function submitDistributorInfo(){
  var customer_name = $("#c_name").val();
  var customer_company = $("#comp_name").val();
  var phone = $("#phone").val();
  var email = $("#email").val();
  var address = $("#address").val();

  var formData = new FormData("#distributor_form");
  if(customer_name==""){
    alert("Please Enter Customer Name.");
    $("#c_name").focus();
    return false;
  }

  else if(customer_company==""){
    alert("Please Enter Company Name.");
    $("#comp_name").focus();
    return false;
  }
  else if(address==""){
    alert("Please Enter Address.");
    $("#address").focus();
    return false;
  }
  else if(phone==""){
    alert("Please Enter Phone Number.");
    $("#phone").focus();
    return false;
  }
  else if(email==""){
    alert("Please Enter Email.");
    $("#email").focus();
    return false;
  }
  else if(email!=""){
    var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
    if(!pattern.test(email)){         
       alert("Enter valid Email Id");
       $("#email").focus();
       return false;
    }
  }
  if($('#file_address').val()!=''){
      var address_element_id = 'file_address';
      var file_upload_result = check_file(address_element_id);
      if(file_upload_result==1){
        formData.append('addressFile',$('#file_address')[0].files[0]);
      }
      else{
        return false;
      }
  }
  if($('#file_photo').val()!=''){
      var photo_element_id = 'file_photo';
      var file_upload_result = check_file(photo_element_id);
      if(file_upload_result==1){
        formData.append('photoFile',$('#file_photo')[0].files[0]);
      }
      else{
        return false;
      }
  }

     
    formData.append('insert_distributorCustomer_details',1);
    var form_data = $('#distributor_form').serializeArray();
    $.each(form_data, function (key, input) {
          formData.append(input.name, input.value);
    });
    
    jQuery.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: "distributor_functions.php",
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      success: function(data){
        var result = JSON.parse(data);
        if(result>0){
          alert("Customer Details Added Successfully.");
          window.location.href = 'view_distCustDetails.php'; 
        }
        else{
          alert("Please Try Again.");
          return false;
        }
      }
    });
}

function check_file(element_id){
  var fileSize = 0;
  fileSize = $("#"+element_id)[0].files[0].size;
 
  fileSize = fileSize / 2000000;

  var fileInput = $("#"+element_id)[0];
  var filePath = fileInput.value;
  var allowedExtensions =  /(\.pdf)$/i;

  if(fileSize>2){
    alert("fileSize cannot be greater than 2MB.");
    fileInput.value = '';
    return false;
  }
  else if(!allowedExtensions.exec(filePath)){
    alert('Please upload file having extensions PDF only.');
    fileInput.value = '';
    return false;
  }
  else{
    return true;
  }
}

$('#phone').on('input', function() {
  this.value = this.value
  .replace(/[^\d]/g, '')             // numbers and decimals only
});
