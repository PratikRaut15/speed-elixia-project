  function getCustomer() {
        $("#unit_details_div").hide();
        
        
        jQuery("#customername").autocomplete({
          type:  "post",
          source: "invoice_functions.php?get_customer=1",
          
            select: function (event, ui) {
              var customerno = ui.item.customerno;
              $("#customerno").val(customerno);
              $("#LedgerGrid").css('height','200px');

              jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: {action: "fetch_unit", custno: customerno},
                  success: function(data){
                    var data=JSON.parse(data);
                    $('#unit_no').html("");
                    $('#unit_no').append('<option value = '+"0"+'>'+"Select Unit"+'</option>');
                    //<-------- add this line
                    $.each(data ,function(i,text){
                      $('#unit_no').append('<option value = '+text.unitno+'>'+text.unitno+'</option>');
                      $("#unit_no").selectedIndex=0;
                    });
                  }
              });

              $('#unit_no').change(function(event) {
                 if ($('#unit_no').val()==0){
                  alert("Please Select Valid Unit No.");
                  return false;
                 }
                 else{
                  var clean = [];
                  var unit_no = $('#unit_no').val()
                  jQuery.ajax({
                  type: "POST",
                  url: "route_ajax.php",
                  data: {action: "fetch_unit_details", custno: customerno,unitno:unit_no},
                  success: function(data){
                    var unit_details=JSON.parse(data);
                    var unit_details = unit_details[0];
                    

                      $("#unit_details_div").show();
                      
                      if(unit_details.data_transimission_delay=='yes'){
                         clean.push(1);
                         $("#delay_success").hide();
                         $("#delay_failure").show();
                      }
                      else{
                        clean.push(0);
                        $("#delay_failure").hide();
                         $("#delay_success").show();
                      }
                      if(unit_details.devicelatVar=='0.000000' || unit_details.devicelongVar=='0.000000'){
                         clean.push(1);
                         $("#location_failure").show();
                         $("#location_success").hide();
                      }
                      else{
                         clean.push(0);
                         $("#location_failure").hide();
                         $("#location_success").show();
                      }
                      if(unit_details.gpsActiveVar==null){
                         clean.push(1);
                         $("#gps_failure").show();
                         $("#gps_success").hide();
                      }
                      else{
                         clean.push(0); 
                         $("#gps_failure").hide();
                         $("#gps_success").show();
                      }
                      if(unit_details.powercutVar=='0'){
                         clean.push(1);
                         $("#powercut_failure").show();
                         $("#powercut_success").hide();
                      }
                      else{
                        clean.push(0);
                         $("#powercut_failure").hide();
                         $("#powercut_success").show();
                      }
                      if(unit_details.tamperedVar=='1'){
                         clean.push(1);
                         $("#tamper_failure").show();
                         $("#tamper_success").hide();
                      }
                      else{
                        clean.push(0);
                         $("#tamper_failure").hide();
                         $("#tamper_success").show();
                      }
                      
                        if(clean.includes(1)){
                          $("#final_success").hide();
                          $("#final_failure").show();
                          
                        }
                        else{
                          $("#final_success").show();
                          $("#final_failure").hide();
                        }
                  }
              });
                 }
                       
              });
            }
        });
  }