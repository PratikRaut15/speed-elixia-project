  var department = $("#department").val();
  var prodList = [];
  var existingTask = 0;
  var searchTeamId=teamId;
  jQuery.ajax({
    type: "POST",
    url: "timesheet_functions.php",
    data: "fetchProducts=1",
    success: function(data){
      products=JSON.parse(data);
      $.each(products ,function(k,v){
        product = {value:v.prodName,id:v.prodId};
        prodList.push(product);
      });
      $('#product').html("");
      $('#product').append('<option value = '+"0"+'>'+"Select Product"+'</option>');
      $.each(data ,function(i,text){
      $('#product').append('<option value = '+text.prodId+'>'+text.prodName+'</option>');
      $("#product").selectedIndex=0;
      });
    }
  });
  jQuery("#product").autocomplete({
    source: prodList,
    select: function (event1, ui) {
      event1.preventDefault();
      $("#product").val("");
      productList += ','+ui.item.id;
      $("#product").after("<div id=P"+ui.item.id+" class='productContainer' onclick='removeProduct("+ui.item.id+")'>"+ui.item.value+"<span class='removeCustomer' onclick='removeProduct("+ui.item.prodId+")'>&times</span></div>");
    }
  });
  $("#task_name").autocomplete({
    type:  "post",
    source: "timesheet_functions.php?searchTask=1&teamId="+searchTeamId,
    select: function (event2, ui) {
      existingTask = 1;
      $("#customerLabel").hide();
      $("#customerText").hide();
      $("#productLabel").hide();
      $("#productText").hide();
      $("#task_description").hide();
      $("#descLabel").css("display","none ");
      $("#taskId").val(ui.item.taskId);
      customerList = ui.item.customerNos;
      productList = ui.item.productList;
      //console.log(ui);
    }
  });
var productList = '';
var customerList = '';
function getCustomer() {
  jQuery("#customername").autocomplete({
    type:  "post",
    source: "timesheet_functions.php?get_customer=1",
    select: function (event2, ui) {
      event2.preventDefault();
      $("#customername").val('');
      customerList += ','+ui.item.customerno;
      $("#customername").after("<div id=C"+ui.item.customerno+" class='customerContainer' onclick='removeCustomer("+ui.item.customerno+")'>"+ui.item.customerno+" - "+ui.item.customercompany+"<span class='removeCustomer' onclick='removeCustomer("+ui.item.customerno+")'>&times</span></div>");
      $("#customername").val("");
    }
  });
}