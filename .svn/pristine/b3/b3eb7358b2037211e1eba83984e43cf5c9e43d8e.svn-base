function fetch_prospCustomers(){
  jQuery.ajax({
      type: "POST",
      url: "route_team.php",
      data: "fetch_prospCust=1&prospectId="+prospectId,
      success: function(result){
          var response = JSON.parse(result);
          response = response[0];
          console.log(response);
          $("#realname").val(response.customername);
          $("#company_name").val(response.customercompany);
          $("#company_add").val(response.company_address);
          $("#phone").val(response.phone);
          $("#emailaddress").val(response.email);
          $("#remarks").val(response.remarks);
      }
  });
}


function updateProspect() {
  var companyName = $("#company_name").val();
  var email = $("#emailaddress").val();
  if(companyName=='' || email==''){
    alert("Please insert Mandatory Fields");
    return false;
  }
  else{
    var data = $("#edit_prospCust").serialize();
    jQuery.ajax({
        url: "route_team.php",
        type: 'POST',
        cache: false,
        data: "update_prospCust=1&"+data,
        success: function (result) {
            var response = JSON.parse(result);
            if(response==1){
              alert("Prospect Updated Successfully.");
              window.location.href='../../modules/team/prospectiveCustomers.php';
            }else{
              alert("Please try again.");
            }
        }
    });
  }
}

