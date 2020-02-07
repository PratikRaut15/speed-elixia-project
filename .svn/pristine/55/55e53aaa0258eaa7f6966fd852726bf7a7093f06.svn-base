var _total_elements = 0;
var _checkbox = 0;
var _selectbox = 0;
var _textfield = 0;
var _options = 0;


jQuery(document).ready(function () {
    data_bind();
});


function remove_add() {
    jQuery(this).parent().remove();
    jQuery("#s" + this.id).remove();
}


function add_button() {
    var select_option = jQuery("#type").val();
    // create text field 		
    if (select_option > 0) {
        if (select_option == 1) {
            _total_elements++;
            _textfield++;
            jQuery("#dom_generator").append("<tr id='" + _total_elements + "' ><th>text field</th><td><input type='text' name='textfield[]' id='textfield" + _textfield + "'/></td><td id='d_" + _total_elements + "'>remove</td></tr>");
            jQuery('#d_' + _total_elements + '').bind("click", remove_add);
            jQuery("#sequence").append("<input type='hidden' name='sequence_order[]' value='text' id='sd_" + _total_elements + "' >");
        }
        // create text field 		
        if (select_option == 2) {
            _total_elements++;
            _checkbox++;
            jQuery("#dom_generator").append("<tr id='" + _total_elements + "'><th>checkbox</th><td><input type='text' name='checkbox[]' id='checkbox" + _checkbox + "'/></td><td id='d_" + _total_elements + "'>remove</td></tr>");
            jQuery('#d_' + _total_elements + '').bind("click", remove_add);
            jQuery("#sequence").append("<input type='hidden' name='sequence_order[]' value='checkbox' id='sd_" + _total_elements + "' >");
        }
        if (select_option == 3) {
            _total_elements++;
            _selectbox++;
            jQuery("#dom_generator").append("<tr id='" + _total_elements + "'><th>selectbox<input type='hidden' name='option_list[]' value='0' id='options" + _total_elements + "'  ></th><td><input type='text' name='selectbox[]' id='selectbox" + _selectbox + "'/></td><td ><table class='data' id='p_o_" + _total_elements + "'></table></td><td id='o_" + _total_elements + "'>Add options</td><td id='d_" + _total_elements + "'>remove</td></tr>");
            jQuery('#d_' + _total_elements + '').bind("click", remove_add);
            jQuery('#o_' + _total_elements + '').bind("click", options_add);
            jQuery("#sequence").append("<input type='hidden' name='sequence_order[]' value='select' id='sd_" + _total_elements + "' >");
        }

    }
}




function options_add() {

    var pid = jQuery(this).parent().attr("id");
    var counts = parseInt(jQuery("#options" + pid).val());
    counts = counts + 1;
    jQuery("#options" + pid).val(counts);
    _options++;
    jQuery("#p_" + this.id).append('<tr><td>options </td><td><input type="text" id="option" name="option[]" id="option"></td></tr>');
}


function data_bind() {
    jQuery("#dom_generator").each(function () {

        jQuery('.rem').bind("click", remove_add);
        jQuery('.op').bind("click", options_add);

    });

}


function check_values_edit() {
    if (jQuery("#checklist_edit").length) {

        _total_elements = jQuery("#_total_elements").val();
        _checkbox = jQuery("#_checkbox").val();
        _selectbox = jQuery("#_selectbox").val();
        _textfield = jQuery("#_textfield").val();
        _options = jQuery("#_options").val();


        jQuery("#type").change(function (event, ui) {
            // create text field 				  
            if (jQuery(this).val() == 1) {
                _total_elements++;
                _textfield++;
                jQuery("#dom_generator").append("<tr id='" + _total_elements + "' ><th>text field</th><td><input type='text' name='textfield[]' id='textfield" + _textfield + "'/></td><td id='d_" + _total_elements + "'>remove</td></tr>");
                jQuery('#d_' + _total_elements + '').bind("click", remove_add);

            }
            // create text field 		
            if (jQuery(this).val() == 2) {
                _total_elements++;
                _checkbox++;
                jQuery("#dom_generator").append("<tr id='" + _total_elements + "'><th>checkbox</th><td><input type='text' name='checkbox[]' id='checkbox" + _checkbox + "'/></td><td id='d_" + _total_elements + "'>remove</td></tr>");
                jQuery('#d_' + _total_elements + '').bind("click", remove_add);
            }
            if (jQuery(this).val() == 3) {
                _total_elements++;
                _selectbox++;
                jQuery("#dom_generator").append("<tr id='" + _total_elements + "'><th>selectbox<input type='hidden' name='option_list[]' value='0' id='options" + _total_elements + "'  ></th><td><input type='text' name='selectbox[]' id='selectbox" + _selectbox + "'/></td><td ><table class='data' id='p_o_" + _total_elements + "'></table></td><td id='o_" + _total_elements + "'>Add options</td><td id='d_" + _total_elements + "'>remove</td></tr>");
                jQuery('#d_' + _total_elements + '').bind("click", remove_add);
                jQuery('#o_' + _total_elements + '').bind("click", options_add);
            }

        });

    }

}


function add_button_edit() {
    var select_option = jQuery("#type").val();
    // create text field 	
	_total_elements = jQuery("#_total_elements").val();
    if (select_option > 0) {
        if (select_option == 1) {
            _total_elements++;
            _textfield++;
            jQuery("#dom_generator").append("<tr id='" + _total_elements + "' ><th>text field</th><td><input type='text' name='textfield[]' id='textfield" + _textfield + "'/></td><td id='d_" + _total_elements + "'>remove</td></tr>");
            jQuery('#d_' + _total_elements + '').bind("click", remove_add);
            jQuery("#sequence").append("<input type='hidden' name='sequence_order[]' value='text' id='sd_" + _total_elements + "' >");
        }
        // create text field 		
        if (select_option == 2) {
            _total_elements++;
            _checkbox++;
            jQuery("#dom_generator").append("<tr id='" + _total_elements + "'><th>checkbox</th><td><input type='text' name='checkbox[]' id='checkbox" + _checkbox + "'/></td><td id='d_" + _total_elements + "'>remove</td></tr>");
            jQuery('#d_' + _total_elements + '').bind("click", remove_add);
            jQuery("#sequence").append("<input type='hidden' name='sequence_order[]' value='checkbox' id='sd_" + _total_elements + "' >");
        }
        if (select_option == 3) {
            _total_elements++;
            _selectbox++;
            jQuery("#dom_generator").append("<tr id='" + _total_elements + "'><th>selectbox<input type='hidden' name='option_list[]' value='0' id='options" + _total_elements + "'  ></th><td><input type='text' name='selectbox[]' id='selectbox" + _selectbox + "'/></td><td ><table class='data' id='p_o_" + _total_elements + "'></table></td><td id='o_" + _total_elements + "'>Add options</td><td id='d_" + _total_elements + "'>remove</td></tr>");
            jQuery('#d_' + _total_elements + '').bind("click", remove_add);
            jQuery('#o_' + _total_elements + '').bind("click", options_add);
            jQuery("#sequence").append("<input type='hidden' name='sequence_order[]' value='select' id='sd_" + _total_elements + "' >");
        }

    }
}