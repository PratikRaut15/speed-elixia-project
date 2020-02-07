var department;
$(document).ready(function(){
  department = $("#department").val();
  var prodList = [];
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
  //fetch team and sort by roles
  jQuery.ajax({
    type: "POST",
    url: "timesheet_functions.php",
    data: {
      fetchTeam:1,
      department:department
    },
    success: function(data){
      var data=JSON.parse(data);
      if(department==1){
        var devList = new Array();
        var testerList = new Array();
        var migratorList = new Array();
        $.each(data,function(i,k){
          if(k.roleName=='Developer'){
            $('#developer').append('<option value = '+k.teamId+' data-trid ='+k.trId+'>'+k.name+'</option>');
            devList.push([k.name,k.teamId]);
          }else if(k.roleName=='Tester'){
            $('#tester').append('<option value = '+k.teamId+' data-trid ='+k.trId+'>'+k.name+'</option>');
            testerList.push([k.name,k.teamId]);
          }else if(k.roleName=='Migrator'){
            $('#migrator').append('<option value = '+k.teamId+' data-trid ='+k.trId+'>'+k.name+'</option>');
            migratorList.push([k.name,k.teamId]);
          }
        });
        $('#assignTo').hide();
        $('#migrator').show();
        $('#developer').show();
        $('#tester').show();
      }else{
        $.each(data,function(i,k){
          $('#assignTo').append('<option value = '+k.teamId+' data-trid ='+k.trId+'>'+k.name+'</option>');
        });
        $('#assignTo').show();
        $('#migrator').hide();
        $('#developer').hide();
        $('#tester').hide();
      }
    }
  });
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

function removeCustomer(customerno){
  $("#customername").val('');
  $("#C"+customerno).filter(".customerContainer").remove();
  //console.log(customerList);
  customerList = customerList.replace(','+customerno+'','');
  //console.log(customerList);
}

function removeProduct(prodId){
  $("#product").val('');
  $("#P"+prodId).filter(".productContainer").remove();
  //console.log(customerList);
  productList = productList.replace(','+prodId+'','');
  //console.log(productList);
}

function submitTask(){
  var data = $("#addTask").serialize();
  data += "&customerList="+customerList+"&productList="+productList;
  var product = $("#product").val();
  var task_name = $("#task_name").val();
  var customerno = $("#customerno").val();
  if(department == 1){
    var devTr = $("#developer").find(':selected').attr("data-trid");
    var migTr = $("#migrator").find(':selected').attr("data-trid");
    var tesTr = $("#tester").find(':selected').attr("data-trid");
    data +="&devTr="+devTr+"&migTr="+migTr+"&tesTr="+tesTr;
  }else{
    var assignTr = $("#assignTo").find(':selected').attr("data-trid");
    data += "&assignTr="+assignTr;
  }
  if(customerList==''){
    alert("Select Customer Please");
    $('#customername').focus();
    return false;
  }else if(productList==''){
    alert("Select Product Please");
    return false;
  }else if(task_name==''){
    alert("Enter Task Name Please");
    $('#task_name').focus();
    return false;
  }else{
    jQuery.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: "&new_task=1&"+data,
      success: function(data){
        var result=JSON.parse(data);
        if(result.taskId>0){
          alert("Task Successfully Created");
          window.location.href = 'taskList.php'; 
        }
        else{
         alert("Task Not Created.Please Try Again");
        }
      }
    });
  }
}

