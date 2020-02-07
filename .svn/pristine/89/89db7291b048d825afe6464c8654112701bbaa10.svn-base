
	jQuery(document).ready(function() {
		gebo_datepicker.init();
		//* timepicker
		gebo_timepicker.init();					   
	});
						   
gebo_datepicker = {
		init: function() {
			//jQuery('#SDate').datepicker({format: "dd-mm-yyyy",autoclose:true});
			//jQuery('#EDate').datepicker({format: "dd-mm-yyyy",autoclose:true});
			
			
			
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
				jQuery('#EDate').datepicker('setStartDate', dateText);
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
				jQuery('#SDate').datepicker('setEndDate', dateText);
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