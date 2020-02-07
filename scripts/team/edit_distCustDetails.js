jQuery(document).ready(function () {  
    get_distributor_details();   
    get_vehicle_details();

    var wrapper         = $(".container1"); 
    var add_button      = $(".add_form_field"); 
    
    var x = 1; 
    $(add_button).click(function(e){ 
        e.preventDefault();
       
            $(wrapper).append('<div><label>Vehicle No.</label><input type="text" name="vehicle_no[]" placeholder="Enter Vehicle No."/><label>Engine No.</label><input type="text" name="engine_no[]" placeholder="Enter Engine No."/><label>Chasis No.</label><input type="text" name="chasis_no[]" placeholder="Enter Chasis No."/><a href="#" class="delete">Delete</a></div>'); //add input box
        
    });
    
    $(wrapper).on("click",".delete", function(e){ 
        e.preventDefault(); 
        $(this).parent('div').remove(); 
    })     
});

function get_distributor_details(){
          jQuery.ajax({
                type: "POST",
                url: "distributor_functions.php",
                data: "fetch_distributor_details=1&dcId="+dc_id,
                success: function(data){
                    var temp_result=JSON.parse(data);
                    var result = temp_result[0];
                    $("#c_name").val(result.customer_name);
                    $("#comp_name").val(result.customercompany);
                    $("#address").val(result.address);
                    $("#phone").val(result.phone);
                    $("#email").val(result.email);

                    var add_file_preview='';
                    add_file_preview=result.addressPath+result.addressFileName;

                    if(add_file_preview!=0){
                      $("#address_pdfPreviewLink").prop("src",add_file_preview);
                      $("#attachmentLink").val(result.filepath);
                      $("#filename").val(result.filename);
                    }
                    else{
                      $("#address_pdfPreviewLink").prop("src","");
                      $("#attachmentLink").val('');
                      $("#filename").val('');
                    }

                    var photo_file_preview='';
                    photo_file_preview=result.photoPath+result.photoFileName;

                    if(photo_file_preview!=0){
                      $("#photo_pdfPreviewLink").prop("src",photo_file_preview);
                    }
                    else{
                      $("#photo_pdfPreviewLink").prop("src","");
                    }
                }
          }); 
}

function editDistributorInfo(){
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
  if($('#file_address').val()!=''){
      var address_element_id = 'file_address';
      var file_upload_result = check_file(address_element_id);
      if(file_upload_result==1){
        formData.append('addressFile',$('#file_address')[0].files[0]);
      }
  }
  if($('#file_photo').val()!=''){
      var photo_element_id = 'file_photo';
      var file_upload_result = check_file(photo_element_id);
      if(file_upload_result==1){
        formData.append('photoFile',$('#file_photo')[0].files[0]);
      }
  }

     
    formData.append('update_distributorCustomer_details',1);
    var form_data = $('#distributor_form_edit').serializeArray();
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
          alert("Details Updated Successfully.");
          window.location.reload();
        }
        else{
          alert("Please Try Again.");
          window.location.reload();
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

function addVehicle_Details(){
   var dcId = dc_id;
   var form_data = $('#distributor_vehicle_form').serialize();
   jQuery.ajax({
      type: "POST",
      url: "distributor_functions.php",
      data: "insert_vehicle_details=1&dcId="+dcId+"&"+form_data,
      success: function(data){
        var result = JSON.parse(data);
        if(result>0){
          get_vehicle_details();
          $("#distributor_vehicle_form")[0].reset();
        }
        else{
          alert("Please Try Again.");
          window.location.reload();
        }
      }
    });
}

function get_vehicle_details(){
  jQuery.ajax({
                type: "POST",
                url: "distributor_functions.php",
                data: "fetch_distributorVehicle_details=1&dcId="+dc_id+"&teamId="+teamId,
                success: function(data){
                    var result=JSON.parse(data);
                    gridOptions.api.setRowData(result);
                }
    });
}