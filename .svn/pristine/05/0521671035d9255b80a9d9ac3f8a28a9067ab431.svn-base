  function getCustomer() {

        jQuery("#customerno").autocomplete({
          type:  "post",
          source: "Docket_functions.php?get_customer=1",
          select: function (event, ui) {
            var customerno = ui.item.customerno;
            $("#customerno").val(customerno);
            $("#customerno_1").val(ui.item.customerno);
            
          }
        })

        jQuery("#to_customerno").autocomplete({
          type:  "post",
          source: "Docket_functions.php?get_customer=1",
          select: function (event, ui) {
            var customerno = ui.item.customerno;
            $("#to_customerno").val(customerno);
            $("#customerno_2").val(ui.item.customerno);
            
          }
        })
  }

  function getVehicle(id) {

    var vehicleno = '';
    var vehicleno1 = $("#vehicleno").value;
    var data = $('#customerno').val();
    $("#vehicleno").autocomplete({
        source: "Docket_functions.php?customerno=" + data+"&getVehicle=1",
        select: function (event, ui) {
            console.log(ui);
            insertVehicleDiv(ui.item.value, ui.item.unitno);

            jQuery(this).val("");
            return false;
        }
    });
  }

 function insertVehicleDiv(selected_name, unitno) {
    jQuery("#vehicleList").val(function (i, val) {
      if (!val.includes(unitno)) {
        return val + (!val ? '':  ',') + unitno;
      }
      else {
        return val;
      }
    });
    if (unitno != "" && $('#vehicle_migrate').find('#em_vehicle_div_' + unitno).val() == null) {
      var div = document.createElement('div');
      div.id = "contain";
      var remove_image = document.createElement('img');
      remove_image.src = "../../images/boxdelete.png";
      remove_image.className = 'clickimage';
      remove_image.title='remove vehicle ?';
      remove_image.onclick = function () {
        removeVehicleDiv(selected_name,unitno);
      };
      div.className = 'recipientbox';
      div.id = 'em_vehicle_div_' + unitno;

      div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + unitno + '" value="' + unitno + '"/>';
      $("#vehicleno").after(div).before();
      $(div).append(remove_image);
    }
  }

  function removeVehicleDiv(selected_name,unitno) {
    var rep = "," + unitno;
    $("#vehicleList").val($("#vehicleList").val().replace(rep, ""));
    $("#vehicleList").val($("#vehicleList").val().replace(unitno, ""));
    $('#vehicle_migrate').find('#em_vehicle_div_' + unitno).remove();
  }

  function migrateVehicles(){

    $("#new_vehicles").hide();
    $("#existing_vehicles").hide();
    $(".existingVehicle").remove();
    $(".newVehicle").remove();

    
    var from_customer = $("#customerno").val();
    var to_customer = $("#to_customerno").val();


    var result = "";
    result = confirm("Move vehicles from customer '"+from_customer+"' to customer '"+to_customer+"' ?");
    
    if(result==true){
      var data = $("#vehicle_migrate").serialize();

      jQuery.ajax({
        type: "POST",
        url: "route_ajax.php",
        data: data+"&migrate_vehicle=1",
          success: function (response) {
            
            var result_migration = JSON.parse(response);

            if(result_migration.new_added!=undefined){
              var str1='';
              str1="<table class='newVehicle' id ='newVehicle' border='1' align='center'><tr><th>Sr no.</th><th>Vehicle No</th></tr>";
                $.each(result_migration.new_added,function(i,text){
                    var j = i+1;
                    str1+="<tr>";
                    str1+="<td>"+j+"</td>";
                    str1+="<td style='width:100px;'>"+text+"</td>";
                    str1+="</tr>";
                });
                str1+="</table>";
                $("#new_vehicles").show();
                $("#new_table").after(str1);
            }

            
            if(result_migration.existing!=undefined){
              var str='';
              str="<table class='existingVehicle' id ='existingVehicle' border='1' align='center'><tr><th>Sr no.</th><th>Vehicle No</th></tr>";
                $.each(result_migration.existing,function(x,value){
                    var j = x+1;
                    str+="<tr>";
                    str+="<td>"+j+"</td>";
                    str+="<td style='width:100px;'>"+value+"</td>";
                    str+="</tr>";
                });
                str+="</table>";
                $("#existing_vehicles").show();
                $("#existing_table").after(str);
            }
            
           
          }
      });
    }
    else{
      return false;
    }
  }

  function resetForm(){
    var result = "";
    result = confirm("Are you sure you want to reset the form ?");
    
    if(result==true){
      window.location.reload();
    }
    else{
      return false;
    }
  }
