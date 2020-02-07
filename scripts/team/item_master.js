jQuery(document).ready(function () {  
  get_item_master();  
});


function addItemMaster(){
  var item_name = $("#item_name").val();
  var description = $("#description").val();

  if(item_name==""){
    alert("Please Enter Item Name.");
    $("#item_name").focus();
    return false;
  }
  else if(description==""){
    alert("Please Enter Description.");
    $("#description").focus();
    return false;
  }
  else{
    var data = $("#item_master").serialize();
    jQuery.ajax({
      type: "POST",
      url: "route_ajax.php",
      data: "insert_item_master=1&"+data,
      success: function(data){
        var result = JSON.parse(data);
        if(result>0){
          alert("Item Inserted Successfully.");
          $("#item_master")[0].reset();
          get_item_master();
        }
        if(result==0){
          alert("Item Already Exists");
          $("#item_name").focus();
        }
      }
    });
  }
}

function get_item_master(){
  jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: "fetch_item_master=1",
                success: function(data){
                    var result=JSON.parse(data);
                    gridOptions.api.setRowData(result);
                }
    });
}