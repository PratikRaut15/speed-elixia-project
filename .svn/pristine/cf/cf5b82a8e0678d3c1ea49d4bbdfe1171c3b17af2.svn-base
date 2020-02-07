
	jQuery(document).ready(function() {
		gebo_datepicker.init();
        jQuery('.elixiaTimePicker').timepicker({'timeFormat': 'H:i'});
                 //gebo_time.init();
		//* timepicker
		//gebo_timepicker.init();
	});

//        $('.datepicker').datepicker().on('changeDate', function(){
//          jQuery('#STime').blur();
//        });

                var curtime = jQuery('#STime').data('date');
                var curtime2 = jQuery('#ETime').data('date2');

                jQuery('#STime').timepicker({
                minuteStep: 1,
                showInputs: false,
                disableFocus: true,
                showMeridian: false,
                //defaultTime: '00:00:00'
                defaultTime: curtime
                //autoclose:true
            });
                jQuery('#ETime').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false,
                defaultTime: curtime2,
                //defaultTime: '00:00:00',
                disableFocus: true
                //autoclose:true
            });

            jQuery('.STime_pop').timepicker({
                minuteStep: 1,
                showInputs: false,
                disableFocus: true,
                showMeridian: false,
                //defaultTime: '00:00:00'
                defaultTime: curtime
                //autoclose:true
            });

            jQuery(document).click(function () {
		jQuery('#STime').timepicker('hide');
        jQuery('#ETime').timepicker('hide');
		jQuery('.STime_pop').timepicker('hide');

	});

        jQuery('#ETime').click(function(){
          jQuery('#STime').timepicker('hide');

        });

        jQuery('#STime').click(function(){
          jQuery('#ETime').timepicker('hide');
        });

        jQuery('.STime_pop').click(function(){
          jQuery('.STime_pop').timepicker('hide');
        });



        jQuery('#STTime').timepicker({
                minuteStep: 1,
                showInputs: false,
                disableFocus: true,
                showMeridian: false,
                defaultTime: '00:00:00'
                //autoclose:true
            });
                jQuery('#EDTime').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false,
                defaultTime: '23:59:59',
                disableFocus: true
                //autoclose:true
            });

            jQuery(document).click(function () {
		jQuery('#STTime').timepicker('hide');
		jQuery('#EDTime').timepicker('hide');
	});

        jQuery('#EDTime').click(function(){
          jQuery('#STTime').timepicker('hide');
        });

        jQuery('#STTime').click(function(){
          jQuery('#EDTime').timepicker('hide');
        });

gebo_datepicker = {
		init: function() {
                    jQuery('#STTime').timepicker({
                                                    minuteStep: 1,
                                                    showInputs: false,
                                                    disableFocus: true,
                                                    showMeridian: false,
                                                    defaultTime: '00:00:00'
                                                    //autoclose:true
                                                });



			jQuery('#SDate').datepicker({format: "dd-mm-yyyy",autoclose:true});
			jQuery('#EDate').datepicker({format: "dd-mm-yyyy",autoclose:true});



			jQuery('#SDate').datepicker({format: "dd-mm-yyyy"}).on('changeDate', function(ev){
				var dateText = jQuery(this).data('date');

				var endDateTextBox = jQuery('#EDate input');
				if (endDateTextBox.val() != '') {
					var testStartDate = new Date(dateText);
					var testEndDate = new Date(endDateTextBox.val());
					if (testStartDate > testEndDate) {
						endDateTextBox.val(dateText);
					}
				}
				else {
					endDateTextBox.val(dateText);
				};
				//jQuery('#EDate').datepicker('setStartDate', dateText);
				jQuery('#SDate').datepicker('hide');
				jQuery('#tp_2').focus();
				//jQuery('#EDate')

			});
			jQuery('#EDate').datepicker({format: "dd-mm-yyyy"}).on('changeDate', function(ev){
				var dateText = jQuery(this).data('date');
				var startDateTextBox = jQuery('#SDate input');
				if (startDateTextBox.val() != '') {
					var testStartDate = new Date(startDateTextBox.val());
					var testEndDate = new Date(dateText);
					if (testStartDate > testEndDate) {
						startDateTextBox.val(dateText);
					}
				}
				else {
					startDateTextBox.val(dateText);
				};
				//jQuery('#SDate').datepicker('setEndDate', dateText);
				jQuery('#EDate').datepicker('hide');
			});
			jQuery('#dp_modal').datepicker();
		}




	};



gebo_timepicker = {
		init: function() {
			jQuery('#tp_1').timepicker({
				defaultTime: 'current',
				minuteStep: 10,
				disableFocus: true,
				template: 'modal',
				showMeridian: false
			});
			jQuery('#tp_3').timepicker({
				defaultTime: 'current',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown',
				showMeridian: false
			});
			jQuery('#tp_2').timepicker({
				defaultTime: 'current',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown',
				showMeridian: false
			});
			jQuery('#tp_modal').timepicker({
				defaultTime: 'current',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown'
			});
		}
	};

        function timepicker_realtime(vehicleid){
            var curtime1 = jQuery('#STime1_'+vehicleid).data('date_'+vehicleid);
            jQuery('#STime1_'+vehicleid).timepicker({
                minuteStep: 1,
                showInputs: false,
                disableFocus: true,
                showMeridian: false,
                //defaultTime: '00:00:00'
                defaultTime: curtime1,
                //autoclose:true
                show:true
            });
            jQuery(document).click(function () {
		//jQuery('#STime1_'+vehicleid).timepicker('hide');
            });
        }

        function datetime_realtime(vehicleid){

                       //alert(vehicleid);


        }


