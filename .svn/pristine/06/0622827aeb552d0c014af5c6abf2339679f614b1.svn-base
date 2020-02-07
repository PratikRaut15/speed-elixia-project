  var department = $("#department").val();
  var prodList = [];
  var existingTask = 0;
  var searchTeamId=teamId;
if(typeof(productList)=='undefined'){ 
  var productList = '';
  var customerList = '';
}
var assignTo = $("#assignTo").val();
var trIdFlag = 0;
jQuery.ajax({
  type: "POST",
  url: "timesheet_functions.php",
  data: {
    fetchTeam:1,
    department:department,
    teamId :assignTo
  },
  success: function(data){
    var data=JSON.parse(data);
    if(data.length==0){
      alert("Please assign member role before inserting timesheet.");
      window.location.href = "addTeamRole.php";
      return;
    }
    trId = data[0].trId;
    $("#assignTr").val(trId);
    //console.log( );
    trIdFlag = 1;
  }
});
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
      //console.log(productList);
      $("#product").after("<div id=P"+ui.item.id+" class='productContainer' onclick='removeProduct("+ui.item.id+")'>"+ui.item.value+"<span class='removeCustomer' onclick='removeProduct("+ui.item.prodId+")'>&times</span></div>");
    }
  });
function taskAutoComplete(){
    $("#task_name").autocomplete({
      type:  "post",
      source: "timesheet_functions.php?searchTask=1&teamId="+searchTeamId+"&trId="+$("#assignTr").val(),
      select: function (event2, ui) {
        // removeAllProducts(productList);
        // removeAllCustomers(customerList);
        $("#taskId").val(ui.item.taskId);
        $("#tMapId").val(ui.item.tMapId);
        existingTask = 1;
        var task = ui.item;
        //prodList = task.productIds.split(',');
        //productList = ','+task.productIds;
        //var productNames = task.productNames.split(',');
        // custList = task.customerNos.split(',');
        // customerList =','+ task.customerNos;
        // var customerNames = task.customerNames.split(',') ;
        // $.each(prodList,function(k,v){
        //   $("#product").after("<div id=P"+v+" class='productContainer' onclick='removeProduct("+v+")'>"+productNames[k]+"<span class='removeCustomer' onclick='removeProduct("+v+")'>&times</span></div>");
        // });
        // $.each(custList,function(k,v){
        //   $("#customername").after("<div id=C"+v+" class='customerContainer' onclick='removeCustomer("+v+")'>"+v+" - "+customerNames[k]+"<span class='removeCustomer' onclick='removeCustomer("+v+")'>&times</span></div>");
        // });
      }
    }); 
}
function removeAllProducts(prodList){
  prodList = prodList.substring(1);
  prodList = prodList.split(',');
  $.each(prodList,function(k,v){
    removeProduct(v);
  });
}
function removeAllCustomers(custList){
  custList = custList.substring(1);
  custList = custList.split(',');
  $.each(custList,function(k,v){
    removeCustomer(v);
  });
}
function getCustomer() {
  jQuery("#customername").autocomplete({
    type:  "post",
    source: "timesheet_functions.php?get_customer=1",
    select: function (event2, ui) {
      event2.preventDefault();
      $("#customername").val('');
      customerList += ','+ui.item.customerno;
      //console.log(customerList);
      $("#customername").after("<div id=C"+ui.item.customerno+" class='customerContainer' onclick='removeCustomer("+ui.item.customerno+")'>"+ui.item.customerno+" - "+ui.item.customercompany+"<span class='removeCustomer' onclick='removeCustomer("+ui.item.customerno+")'>&times</span></div>");
      $("#customername").val("");
    }
  });
}

function removeCustomer(customerno){
  //console.log(customerno);
  $("#customername").val('');
  $("#C"+customerno).filter(".customerContainer").remove();
  customerList = customerList.replace(','+customerno+'','');
}

function removeProduct(prodId){
  //console.log(prodId);
  $("#product").val('');
  $("#P"+prodId).filter(".productContainer").remove();
  productList = productList.replace(','+prodId+'','');
}

function submitTimeSheet(){
  $("#edit_task").attr("disabled","disabled");
  if($("#assignTo").val()==''){
    alert("Please assign member role before inserting timesheet.");
    $("#submit_task").removeAttr("disabled");
    $("#edit_task").removeAttr("disabled");
    return;
  }

  var date = $("#dateTS").val();
  var dateObj = new Date();
  var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
  var day = ('0' + dateObj.getDate()).slice(-2);
  var year = dateObj.getFullYear();
  var shortDate = year + '-' + month + '-' + day;
  var today =  new Date(shortDate);
  var tryDate =   new Date(date);
  if(today<tryDate){
    alert("You cannot select a date greater than today's date.");
    $("#submit_task").removeAttr("disabled");
    $("#edit_task").removeAttr("disabled");
    return;
  }
  var data = $("#updateTime").serialize();
  data += "&customerList="+customerList+"&productList="+productList;
  var taskId='';
  if(existingTask==1){
    taskId = $("#taskId").val();
    data+="&existing_task=1&taskId="+taskId;
  }else{
    data+="&new_task=1"
  }
  if(customerList == '' || productList == ''){
    alert("Customer and product fields are mandatory.");
    $("#submit_task").removeAttr("disabled");
    $("#edit_task").removeAttr("disabled");
    return;
  }else if (($("#estHrs").val()==0)&&($("#estMin").val()==0)){
    // console.log($("#estHrs").val());
    // console.log($("#estMin").val());
    alert("Please enter duration.");
    $("#submit_task").removeAttr("disabled");
    $("#edit_task").removeAttr("disabled");
    return;
  }else if($("#task_name").val()==''){
    alert("Please enter task name.");
    $("#submit_task").removeAttr("disabled");
    $("#edit_task").removeAttr("disabled");
    return;
  }
  var time = $("#estHrs").val()+":"+$("#estMin").val();
  $("#submit_task").attr("disabled", "disabled");
  if(typeof tsId == 'undefined'){
    var tsId = $("#tsId").val();
  }
  var exitFlag = false;
    jQuery.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: "getTime=1&estimated_time="+time+"&tsId="+tsId+"&date="+date,
      async:false,
      success: function(response){
        if(response=="1"){
          //console.log(typeof response);
          exitFlag = true;
        }
      }
    });
    if(exitFlag==true){
      alert("Your total hours working hours for the day are more than 14.");
      $("#submit_task").removeAttr("disabled");
      $("#edit_task").removeAttr("disabled");
      return;
    }
  data+='&estimated_time='+time;
  var editFlag = false;
  if(typeof(edit_timesheet)!='undefined'&&edit_timesheet==1){
    editFlag = true;
    data+="&editTimesheet=1&tsId="+tsId;
    var taskId = $("#taskId").val();
    data+="&existing_task=1&taskId="+taskId;
  }
  jQuery.ajax({

    type: "POST",
    url: "timesheet_functions.php",
    data: "updateTimesheet=1&date="+date+"&"+data,
    success: function(data){
      $("#submit_task").removeAttr("disabled");
      $("#edit_task").removeAttr("disabled");
      var data = JSON.parse(data);
      if(editFlag == true){
        if(data.editTimesheet.rowsAffected!=0){
          //console.log(data);
          alert("Timesheet entry updated.");
        }
      }else{
         if(data.timesheet.rowsAffected!=0){
          document.getElementById("updateTime").reset();
          $("#dateTS").val(date);
          var cDivs = $(".customerContainer");
          $.each(cDivs,function(k,v){
            v.remove();
          });
          var pDivs = $(".productContainer");
          $.each(pDivs,function(k,v){
            v.remove();
          });
          customerList = '';
          productList = '';
          existingTask = 0;
          $("#task_description").show();
          $("#descLabel").show();
          $("#customerLabel").show();
          $("#customerText").show();
          $("#productLabel").show();
          $("#productText").show();
          $("#taskId").val("");
          if(data.taskId > 0){
            //window.location.href = "updateTimesheet.php";
          }
          alert("Timesheet entry saved.");
        }
      }
      refreshTimesheetGrid();
      var taskGrid =  $("#taskGrid");
      if(taskGrid.length>0){
        refreshTaskGrid();
      }

    }
  });
  //console.log(data); 
}
