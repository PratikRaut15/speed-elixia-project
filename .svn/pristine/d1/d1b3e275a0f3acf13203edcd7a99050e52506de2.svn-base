jQuery(document).ready(function () {  
  fetch_prospCustomers();
});  

function submitProspect(){
  var companyName = $("#company_name").val();
  var email = $("#emailaddress").val();
  if(companyName=='' || email==''){
    alert("Please insert Mandatory Fields");
    return false;
  }else{
    var data = $("#prospCust").serialize();
    jQuery.ajax({
      type: "POST",
      url: "route_team.php",
      data: "insert_prospectiveCust=1&"+data,
      success: function(result){
          var response = JSON.parse(result);
          if(response>0){
            alert("Data Inserted Successfully");
            document.getElementById("prospCust").reset();
            fetch_prospCustomers();
          }else{
            alert("Please try again.");
          }
      }  
    });  
  }
}


function fetch_prospCustomers(){
            jQuery.ajax({
                type: "POST",
                url: "route_team.php",
                data: "fetch_prospCust=1",
                success: function(result){
                    var response = JSON.parse(result);
                    gridOptions_prospCust.api.setRowData(response);
                }
            });
}

function checkEmail() {
  var email = $("#emailaddress").val();
  var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
  if (pattern.test(email)) {
      return true;
  } else {
      alert("Enter valid email id");
      return false;
  }            
}

function deleteProsCust(id) {
        var result = "";
        result = confirm("Are you sure you want to delete the prospect ?");
        if (result == true) {
            jQuery.ajax({
                url: "route_team.php",
                type: 'POST',
                cache: false,
                data: "delete_prospCust=1&prospectId="+id,
                success: function (result) {
                    var response = JSON.parse(result);
                    if(response==1){
                      alert("Prospect Deleted Successfully.");
                      fetch_prospCustomers();
                    }else{
                      alert("Please try again.");
                    }
                }
            });
            return true;
        } else {
            return false;
        }   
}