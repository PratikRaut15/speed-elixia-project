jQuery(document).ready(function () {
    mappedcheckpoints();    
    mappedfences();
});

function mappedcheckpoints() {
	jQuery('.mappedcheckpoints').each(function () {
		var chkptid = jQuery(this).attr('rel');
            var chkpt = jQuery(this).val();
            ldchkpt(chkpt, chkptid);
	});
}   

function mappedfences() {
	jQuery('.mappedfences').each(function () {
		var fenceid = jQuery(this).attr('rel');
            var fence = jQuery(this).val();
            ldfence(fence, fenceid);
	});
}   

function ldchkpt(chkpt, chkptid)
{

    var selected_name = chkpt;
    if (chkptid > -1 && jQuery('#to_checkpoint_div_' + chkptid).val() == null)
    {
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() { removeCheckpoint(chkptid); };
        div.className = 'recipientbox';
        div.id = 'to_checkpoint_div_' + chkptid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_checkpoint_' + chkptid + '" value="' + chkptid + '"/>';
        jQuery('#checkpoint_list').append(div);
        jQuery(div).append(remove_image);
    }
}   
function ldfence(fence, fenceid)
{
    var selected_name = fence;
    if (fenceid > -1 && jQuery('#to_fence_div_' + fenceid).val() == null)
    {
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() { removefence(fenceid); };
        div.className = 'recipientbox';
        div.id = 'to_fence_div_' + fenceid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_fence_' + fenceid + '" value="' + fenceid + '"/>';
        jQuery('#fence_list').append(div);
        jQuery(div).append(remove_image);
    }
}