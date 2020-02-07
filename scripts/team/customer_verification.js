  function getCustomer() {
       
       $(".unmappedVehiclesTable").remove();
       $("#unmapped_vehicle_div").hide();
       $(".customerDetailsTable").remove();
       $("#customer_details_div").hide();
       $("#additional_customer_Details").hide(); 
       $(".deviceInfoTable").remove();
       $("#devices_info").hide();
       $("#minimize_vehicle").hide();
       $("#maximize_vehicle").hide();
       $("#final_failure").hide();
       $("#final_success").hide();


        jQuery("#customername").autocomplete({
          type:  "post",
          source: "invoice_functions.php?get_customer=1",
          
            select: function (event, ui) {
              var customerno = ui.item.customerno;
              $("#customerno").val(customerno);
              $("#LedgerGrid").css('height','200px');
                var clean = [];

                jQuery.ajax({
                  type: "POST",
                  url: "ledger_ajax.php",
                  cache: false,
                  data: {
                      work: "getMappedLedger", custno: customerno
                  },
                    success: function(data){

                      var ledger_details=JSON.parse(data);
                      var gst_nums= [];
                      var address = [];
                      $.each(ledger_details,function(i,text){
                        gst_nums.push(text.gstno);
                        address.push(text.add1);
                      });

                      if(ledger_details.length==0 || gst_nums.includes("") || address.includes("")){
                        clean.push(1);
                        $("#ledger_failure").show();
                        $("#ledger_success").hide();
                      }
                      else{
                        clean.push(0);
                        $("#ledger_failure").hide();
                        $("#ledger_success").show();
                      }
                      gridOptions.api.setRowData(ledger_details);
                      $("#ledger").show();
                      if(ledger_details.length==0){
                          $("#create_ledger").show();
                      }
                      else{
                          $("#create_ledger").hide();
                      }
                    }
                });

                jQuery.ajax({
                  type: "POST",
                  url: "route_ajax.php",
                  cache: false,
                  data: {
                      action: "unmappedVehicles", custno: customerno
                  },
                  success: function(result){
                    var str1='';
                    var unmappedVehicles = JSON.parse(result);
                      if(unmappedVehicles.length>0){ 
                       str1="<table class='unmappedVehiclesTable' id ='unmappedVehiclesTable' border='1' align='center' style='text-align: center;font-size: 13px;'><tr><th>Sr no.</th><th>Customer No</th><th>Customer Company</th><th>Total Count</th><th>Edit</th></tr>";
                        $.each(unmappedVehicles,function(i,text){
                            var j = i+1;
                            str1+="<tr>";
                            str1+="<td>"+j+"</td>";
                            str1+="<td style='width:100px;'>"+text.customerno+"</td>";
                            str1+="<td style='width:100px;'>"+text.customercompany+"</td>";
                            str1+="<td style='width:100px;background-color:#db3236;color:#fff;'>"+text.count+"</td>";
                            str1+="<td style='width:100px;'><a href='ledger_mapvehicle.php?cno="+text.customerno+"' alt='Edit Mode' title='Edit' target='_blank' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a></td>";
                            str1+="</tr>";
                        });
                        str1+="</table>";
                        $("#unmapped_Vehicles").show();
                        $("#vehicle_failure").show();
                        $("#vehicle_success").hide();
                        $("#unmapped_vehicle_div").after(str1);
                        $("#minimize_vehicle").show();
                        $("#maximize_vehicle").show();
                        clean.push(1);
                      }
                      else{
                        clean.push(0);
                        $("#unmapped_Vehicles").show();
                        $("#vehicle_failure").hide();
                        $("#vehicle_success").show();
                        $("#unmapped_vehicle_div").after(str1);
                        $("#minimize_vehicle").hide();
                        $("#maximize_vehicle").hide();
                      }
                  }
                });

                jQuery.ajax({
                  type: "POST",
                  url: "route_ajax.php",
                  cache: false,
                  data: {
                      action: "getCustomerDetails", custno: customerno
                  },
                  success: function(result){
                    
                    var customerDetails = JSON.parse(result);
                     var str2 = '';
                      str2="<table class='customerDetailsTable' id ='customerDetailsTable' border='1' align='center' style='text-align: center;font-size: 12px;'><tr><th>SMS Left</th><th>Unit Price</th><th>Subs Price</th><th>Commercial Details</th><th>Renewal Period</th></th><th>Edit</th></tr>";
                            
                            str2+="<tr>";
                            if(customerDetails.smsleft<100){
                              str2+="<td style='width:100px;background-color:#db3236;color:#fff;'>"+customerDetails.smsleft+"</td>";
                              
                            }
                            else{
                              str2+="<td style='width:100px;'>"+customerDetails.smsleft+"</td>";
                            }
                            if(customerDetails.unit_msp==''){
                              str2+="<td style='width:100px;background-color:#db3236;color:#fff;'>"+customerDetails.unit_msp+"</td>";
                             
                            }
                            else{
                              str2+="<td style='width:100px;'>"+customerDetails.unit_msp+"</td>";
                             
                            }
                            if(customerDetails.unitprice==''){
                              str2+="<td style='width:100px;background-color:#db3236;color:#fff;'>"+customerDetails.unitprice+"</td>";
                              
                            }
                            else{
                              str2+="<td style='width:100px;'>"+customerDetails.unitprice+"</td>";
                              
                            }
                            if(customerDetails.commercial_details==''){
                              str2+="<td style='width:100px;background-color:#db3236;color:#fff;'>"+customerDetails.commercial_details+"</td>";
                              
                            }
                            else{
                              str2+="<td style='width:100px;overflow:auto;'>"+customerDetails.commercial_details+"</td>";
                             
                            }
                            if(customerDetails.renewal==''){
                              str2+="<td style='width:100px;background-color:#db3236;color:#fff;'>"+customerDetails.renewal+"</td>";
                             
                            }
                            else{
                              str2+="<td style='width:100px;'>"+customerDetails.renewal+"</td>";
                             
                            }
                            str2+="<td style='width:100px;'><a href='modifycustomer.php?cid="+customerDetails.customerno+"' alt='Edit Mode' title='Edit' target='_blank' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a></td>";
                            str2+="</tr>";
                       
                        str2+="</table>";
                        $("#customer_Details").show();
                        $("#customer_details_div").after(str2);

                        if(customerDetails.smsleft<100 ||customerDetails.unit_msp=='' || customerDetails.unitprice==''  || customerDetails.renewal==''){
                          clean.push(1); 
                          $("#customer_failure").show();
                          $("#customer_success").hide();
                        }
                        else{
                           clean.push(0);
                          $("#customer_failure").hide();
                          $("#customer_success").show();
                        }
                       
                  }
                });
              
                jQuery.ajax({
                  type: "POST",
                  url: "contactdetails_ajax.php",
                  cache: false,
                  data: { cno: customerno
                  },
                  success: function(result){
                   var customer_type = [];
                   var str3 = '';
                   var addition_details = JSON.parse(result);
                   //console.log(addition_details);
                   $.each(addition_details,function(i,text){
                    customer_type.push(text.type);
                   });
                   if(customer_type.includes('Accounts')){
                      $("#account_failure").hide();
                      $("#account_success").show();
                   }
                   else{
                      $("#account_failure").show();
                      $("#account_success").hide();
                   }
                   if(customer_type.includes('Owner')){
                      $("#owner_failure").hide();
                      $("#owner_success").show();
                   }
                   else{
                      $("#owner_failure").show();
                      $("#owner_success").hide(); 
                   }
                   if(customer_type.includes('Co-ordinator')){
                      $("#coord_failure").hide();
                      $("#coord_success").show();
                   }
                   else{
                      $("#coord_failure").show();
                      $("#coord_success").hide();
                   }
                   $("#additional_customer_Details").show();

                    if(!customer_type.includes('Owner')|| !customer_type.includes('Co-ordinator')|| !customer_type.includes('Accounts')){
                        $("#additional_link_image").show();
                        $("#additional_link").prop('href','modifycustomer.php?cid='+customerno);
                        clean.push(1);
                    }
                    else{
                      $("#additional_link_image").hide();
                      clean.push(0);
                    }
                  }
                });

                jQuery.ajax({
                  type: "POST",
                  url: "route_ajax.php",
                  cache: false,
                  data: {  action: "getDevicesInfo",custno: customerno
                  },
                  success: function(result){
                    var str3='';
                    var cust_devices_info = JSON.parse(result);
                       
                      str3="<table class='deviceInfoTable' id ='deviceInfoTable' border='1' align='center' style='text-align: center;font-size: 13px;'><tr><th>Active Devices</th><th>Inactive Devices</th><th>Expired Devices</th><th>Expiring in 15 days</th><th>Edit</th></tr>";
                      
                      str3+="<tr>";
                      str3+="<td style='width:100px;'>"+cust_devices_info.activeCount+"</td>";
                      if(cust_devices_info.inactiveCount>0){
                        
                        str3+="<td style='width:100px;background-color:#db3236;color:#fff;'>"+cust_devices_info.inactiveCount+"</td>";
                      }
                      else{
                        str3+="<td style='width:100px;'>"+cust_devices_info.inactiveCount+"</td>";
                      }
                      if(cust_devices_info.expiredCount>0){
                        str3+="<td style='width:100px;'>"+cust_devices_info.expiredCount+"</td>";
                      }else{
                        str3+="<td style='width:100px;background-color:#db3236;color:#fff;'>"+cust_devices_info.expiredCount+"</td>";
                      }
                      if(cust_devices_info.expiredCount2>0){
                        str3+="<td style='width:100px;background-color:#f4c20d;color:#000;'>"+cust_devices_info.expiredCount2+"</td>";
                      }else{
                        str3+="<td style='width:100px;'>"+cust_devices_info.expiredCount2+"</td>";
                      }
                      str3+="<td style='width:100px;'><a href='ledger_mapvehicle.php?cno="+customerno+"' alt='Edit Mode' title='Edit' target='_blank' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a></td>";
                      str3+="</tr>";
                        
                      str3+="</table>";

                        $("#devices_info").show();
                        $("#devices_info_div").after(str3);
                    
                      if(cust_devices_info.inactiveCount>0 || cust_devices_info.inactiveCount>0 || cust_devices_info.inactiveCount>0){

                        $("#devices_failure").show();
                        $("#devices_success").hide();
                        clean.push(1);
                      }
                      else{
                        $("#devices_failure").hide();
                        $("#devices_success").show();
                        clean.push(0);
                      }
                  }
                });

  
                $( document ).ajaxStop(function() {
                finalResult(clean);
                });
            }
        });
  }
  function finalResult(clean){
    console.log(clean);
    if(clean.includes(1)){
      $("#final_failure").show();
      $("#final_success").hide();
    }
    else{
      $("#final_failure").hide();
      $("#final_success").show();
    }
  }


  function minimize_customer(){
      $("#customerDetailsTable td").hide(300);
  }
  function maximize_customer(){
      $("#customerDetailsTable td").show(300);
  }
  function minimize_device(){
      $("#deviceInfoTable td").hide(300);
  }
  function maximize_device(){
      $("#deviceInfoTable td").show(300);
  }
  function minimize_vehicle(){
      $("#unmappedVehiclesTable td").hide(300);
  }
  function maximize_vehicle(){
      $("#unmappedVehiclesTable td").show(300);
  }
  function minimize_ledger(){
      $("#LedgerGrid").css('height','50px');
  }
  function maximize_ledger(){
      $("#LedgerGrid").css('height','200px');
  }