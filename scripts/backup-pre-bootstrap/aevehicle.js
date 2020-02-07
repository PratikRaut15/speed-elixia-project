




function addchk() {
	var checkpoint_id = jQuery('#chkid').val();
	
	if (checkpoint_id > -1 && jQuery('#to_checkpoint_div_' + checkpoint_id) == null) {
		var selected_name = jQuery('#chkid').options[jQuery('#chkid').selectedIndex].text;
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeCheckpoint(checkpoint_id);
		};
		div.className = 'recipientbox';
		div.id = 'to_checkpoint_div_' + checkpoint_id;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_checkpoint_' + checkpoint_id + '" value="' + checkpoint_id + '"/>';
		jQuery('#checkpoint_list').appendChild(div);
		$(div).appendChild(remove_image);
	}
	jQuery('#chkid').selectedIndex = 0;
}

function removeCheckpoint(checkpoint_no) {
	jQuery('#to_checkpoint_div_' + checkpoint_no).remove();
}

function addallchk() {
	var select_box = jQuery('#chkid');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		addchk();
	}
}

function addfence() {
	var fence_id = jQuery('#fenceid').val();
	if (fence_id > -1 && jQuery('#to_fence_div_' + fence_id) == null) {
		var selected_name = jQuery('#fenceid').options[jQuery('#fenceid').selectedIndex].text;
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removefence(fence_id);
		};
		div.className = 'recipientbox';
		div.id = 'to_fence_div_' + fence_id;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_fence_' + fence_id + '" value="' + fence_id + '"/>';
		jQuery('#fence_list').appendChild(div);
		$(div).appendChild(remove_image);
	}
	jQuery('#fenceid').selectedIndex = 0;
}

function removefence(fenceid) {
	jQuery('#to_fence_div_' + fenceid).remove();
}

function addallfence() {
	var select_box = jQuery('#fenceid');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		addfence();
	}
}