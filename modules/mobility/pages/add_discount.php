<?php
/**
 * Add Discount form
 */
?>
<br/>
<div class='container' >
    <center>
    <form id="adddiscount" name="adddiscount" method="POST" action="mobility.php?pg=add-discount" onsubmit="addDiscount();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Add Discount</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                <tr><td class='frmlblTd'>Discount Code<span class="mandatory">*</span></td><td><input type="text" name="disccode" id="disccode" required></td></tr>
            <tr><td class='frmlblTd'>Expiry Date </td><td><input type="text" name="expdate"></td></tr>
            <tr><td class='frmlblTd'>Discount Type</td><td><input type="radio" name="disctype" value="1" checked="checked"> Amount <input type="radio" name="disctype" value="2"> Percentage </td></tr>
            <tr id="distype_val"><td class='frmlblTd'>&nbsp;</td><td><input type="text" name="disctype_value" step="0.01"></td></tr>
            <tr><td class='frmlblTd'>Specific Type</td>
                <td>
                    <input type="radio" name="spectype" value="1"> Individual User 
                    <input type="radio" name="spectype" value="2"> Location 
                    <input type="radio" name="spectype" value="3"> Branch 
                    <input type="radio" name="spectype" value="4"> City 
                </td></tr>
            <tr id="sp_type_individual"><td class='frmlblTd'>&nbsp;</td>
                <td>
                    <input type='text' placeholder="Name" name="ind_client" id="ind_client"  style="width: 100%;"/>
                </td>
            </tr>
            <tr id="sp_type_loc"><td class='frmlblTd'>&nbsp;</td>
                <td>
                    <input type='text' placeholder="Location" name="location" id="location"/>
                    <input type='hidden' id="locid" name="locid">
                </td>
            </tr>
            <tr id="sp_type_group"><td class='frmlblTd'>&nbsp;</td>
                <td>
                    <input type='text' placeholder="Branch" name="branchname" id="branchname"/>
                    <input type='hidden' id="grpid" name="grpid">
                </td>
            </tr>
            <tr id="sp_type_city"><td class='frmlblTd'>&nbsp;</td>
                <td>
                    <input type='text' placeholder="City" name="cityname" id="cityname"/>
                    <input type='hidden' id="cityid" name="cityid">
                </td>
            </tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
