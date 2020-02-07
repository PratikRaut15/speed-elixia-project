<style>
  a.mover{ background: #0082CC; padding: 6px 12px; position: absolute;color: white; text-decoration: none; }
  .next-tab{ bottom: 0; right: 0; -moz-border-radius-topleft: 10px; -webkit-border-top-left-radius: 10px; }
  .prev-tab{ bottom: 0; left: 0; -moz-border-radius-topright: 10px; -webkit-border-top-right-radius: 10px; }
  .tabstyle{
    height: auto;
}
.req{
    color: red;
}
</style>

<div class='container' >
    <div style="float:right;">
        <a href="sectms.php?pg=customer"> <button class="btn-secondary" style="margin:5px; width:auto; display: inline-block;">View Customer</button></a>
    </div>
</div>
<div class='entry' >

    <center>
        <form class="form-horizontal well" id="frmLegalTracker" name="frmLegalTracker" method="post" action="action.php?action=save-legal-tracker">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">General Details</a></li>
                    <li><a href="#tabs-2">Registration Details</a></li>
                    <li><a href="#tabs-3">Finance Details</a></li>
                    <li><a href="#tabs-4">Drop Points</a></li>
                    <li><a href="#tabs-5">Ledger Details </a></li>
                    <li><a href="#tabs-6">User Details </a></li>
                    <li><a href="#tabs-7">Contract Details </a></li>
                    <li><a href="#tabs-8">Attachments</a></li>
                </ul>
                <div id="tabs-1" class="tabstyle">
                    <table style="width:100%; border: none; text-align: left;" >
                        <tr>
                            <td colspan="4">
                               General Details
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Name <span class="req">*</span> </span>
                            </td>
                            <td>
                                <input type="text" name="nametxt" id="nametxt" value=""  autocomplete="off" required="" />
                            </td>
                            <td>
                                <span class="add-on">Code <span class="req">*</span></span>
                            </td>
                            <td>
                                <input type="text" name="code" id="code" value="" autocomplete="off" required="" />
<!--                            <input type="hidden" name="vendorid" id="vendorid" value="" />-->
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Company <span class="req">*</span></span>
                            </td>
                            <td>
                                <input type="text" name="company" id="company" value="" autocomplete="off" required="" />
<!--                                <input type="hidden" name="depotid" id="locationid" value="" />-->
                            </td>
                            <td>
                                <span class="add-on">Branch</span>
                            </td>
                            <td>
                                <input type="text" name="branch" id="branch" value="" autocomplete="off"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Address</span>
                            </td>
                            <td>
                                <textarea name="address" id="address" rows="2" cols="20"></textarea>
                            </td>
                            <td>
                                <span class="add-on">City</span>
                            </td>
                            <td>
                                <input type="text" name="city" id="city" placeholder="City">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">State</span>
                            </td>
                            <td>
                                <input type="text" name="state" id="state" value=""autocomplete="off"/>
                            </td>
                            <td><span class="add-on">Zip Code </span></td>
                            <td>
                                <input type="text" name="zipcode" id="zipcode" value=""autocomplete="off"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Country</span>
                            </td>
                            <td>
                                <input type="text" name="country" id="country" value="" autocomplete="off"/>
                            </td>
                            <td>
                                <span class="add-on">Mobile</span>
                            </td>
                            <td>
                                <input type="text" name="mobile" id="mobile">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Email Id</span>
                            </td>
                            <td>
                               <input type="text" name="email_id" id="email_id" value="" autocomplete="off"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Turn On Email Alerts</span>
                            </td>
                            <td>
                                <input type="checkbox" name="emailalert" id="emailalert">
                            </td>
                            <td>
                                <span class="add-on">Turn On SMS Alerts</span>
                            </td>
                            <td>
                                <input type="checkbox" name="smsalert" id="smsalert">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Vehicle List</span>
                            </td>
                            <td>
                                <select name="vehiclelist" id="vehiclelist">
                                    <option value="1">Select Vehicle</option>
                                </select>
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>


                    </table>
                    <br/><br/>
                </div>
                <div id="tabs-2" class="tabstyle">
                    <table style="width:100%; border: none; text-align: left;">
                        <tr>
                            <td>
                                <span class="add-on">CST # / TIN #</span>
                            </td>
                            <td>
                                <input type="text" name="cst" id="cst" value=""/>
                            </td>
                            <td>
                                <span class="add-on">Service Tax #</span>
                            </td>
                            <td>
                                <input type="text" name="servicetax" id="servicetax" value=""/>
                            </td>
                        </tr>
                    </table>
                    <br/><br/>
                </div>
                <div id="tabs-3" class="tabstyle">
                    <table style="width:100%; border: none; text-align: left;" >
                        <tr>
                            <td>
                                <span class="add-on">Credit Days</span>
                            </td>
                            <td>
                                <input type="text" name="creditdays" id="creditdays" value="" />
                            </td>
                            <td>
                                <span class="add-on">Grace Days</span>
                            </td>
                            <td>
                                <input type="text" name="gracedays" id="gracedays" value="" />
                            </td>
                        </tr>
                    </table>
                    <br/><br/>
                </div>
                <div id="tabs-4" class="tabstyle">
                    <table style="width:50%; border: 1px; text-align: left;" >
                        <tr>
                            <th>SR No</th>
                            <th>DP Code</th>
                            <th>DP Name</th>
                            <th>ETA</th>
                        </tr>
                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                ABC
                            </td>
                            <td>
                                Drop1
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                    <br/><br/>
                </div>
                <div id="tabs-5" class="tabstyle">
                    <table style="width:100%; border: none; text-align: left;" >
                        <tr>
                            <td>
                                <span class="add-on">Ledger Code</span>
                            </td>
                            <td>
                                <input type="text" name="ledgercode" id="ledgercode" value="" />
                            </td>
                            <td>
                                <span class="add-on">Company Name</span>
                            </td>
                            <td>
                                <input type="text" name="compname" id="compname" value="" />
                            </td>
                        </tr>
                        <tr>
                          <td>
                            <span class="add-on">Address</span>
                        </td>
                        <td>
                           <textarea id="compaddress" name="compaddress" rows="2" cols="20"></textarea>
                        </td>
                        <td>
                            <span class="add-on">City</span>
                        </td>
                        <td>
                            <input type="text" name="city" id="city" value=""/>
                        </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">State</span>
                            </td>
                            <td>
                                <input type="text" name="state" id="state" value="" />
                            </td>
                            <td>
                                <span class="add-on">Zip</span>
                            </td>
                            <td>
                                <input type="text" name="zip" id="zip" value="" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Country</span>
                            </td>
                            <td>
                                <input type="text" name="country" id="country" value="" />
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <h3>Ledger Details</h3>
                    <table style="width:50%; border: 1px; text-align: left;" >
                        <tr>
                            <th>SR No</th>
                            <th>Ledger Code</th>
                            <th>Company Name</th>
                            <th>City</th>
                        </tr>
                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                E1234
                            </td>
                            <td>
                                ABC PVT LTD
                            </td>
                            <td>
                                Mumbai
                            </td>
                        </tr>
                    </table>
                    <br/><br/>
                </div>
                <div id="tabs-6" class="tabstyle">
                    <table style="width:100%; border: none; text-align: left;" >
                        <tr>
                            <td>
                                <span class="add-on">User Name</span>
                            </td>
                            <td>
                                <input type="text" name="username" id="username" value="">
                            </td>
                            <td>
                                <span class="add-on">Password</span>
                            </td>
                            <td>
                                <input type="text" name="password" id="password" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Role</span>
                            </td>
                            <td>
                                <select>
                                    <option>Select Role</option>
                                    <option>Role1</option>
                                    <option>Role2</option>
                                </select>
                            </td>
                            <td>
                                <span class="add-on">Email</span>
                            </td>
                            <td>
                                <input type="text" name="email" id="email">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Phone No</span>
                            </td>
                            <td>
                                <input type="text" name="phoneno" id="phoneno" value="" />
                            </td>
                        </tr>
                    </table>
                    <br>
                    <h3>User Details</h3>
                    <table style="width:50%; border: 1px; text-align: left;" >
                        <tr>
                            <th>SR No</th>
                            <th>UserName</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                rajesh123
                            </td>
                            <td>
                                rajesh@demo.com
                            </td>
                            <td>
                                8794561235
                            </td>
                        </tr>
                    </table>
                    <br/><br/>
                </div>
                <div id="tabs-7" class="tabstyle">
                    <table style="width:100%; border: none; text-align: left;" >
                        <tr>
                            <td>
                                <span class="add-on">Start Date </span>
                            </td>
                            <td>
                                <input type="text" name="startdate" id="startdate" value=""/>
                            </td>
                            <td>
                                <span class="add-on">End Date </span>
                            </td>
                            <td>
                                <input type="text" name="enddate" id="enddate" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Billing Range</span>
                            </td>
                            <td>
                                <select>
                                    <option>Monthly</option>
                                    <option>Quarterly</option>
                                    <option>fortnightly</option>
                                    <option>Weekly</option>
                                </select>
                            </td>
                            <td>
                                <span class="add-on">Fright Type</span>
                            </td>
                            <td>
                                <select>
                                    <option>Per KM</option>
                                    <option>Per Product</option>
                                    <option>Per KG</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">Freight value</span>
                            </td>
                            <td>
                                <input type="text" name="frightvalue" id="frightvalue" value="" />
                            </td>
                            <td>
                                 <span class="add-on">Refer Cost</span>
                            </td>
                            <td>
                                <input type="text" name="refercost" id="refercost">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="add-on">One Time Cost</span>
                            </td>
                            <td>
                                <input type="text" name="otc" id="otc" value="" />
                            </td>
                            <td>
                                <span class="add-on">Distance Limit</span>
                            </td>
                            <td>
                                <input type="text" name="distancelimit" id="distancelimit" value="" />
                            </td>
                        </tr>
                    </table><br/>
                    <h3>Contract Details</h3>
                    <table style="width:50%; border: 1px; text-align: left;" >
                        <tr>
                            <th>SR No</th>
                            <th>Contract Start Date</th>
                            <th>End Date</th>
                            <th>Billing Range</th>
                        </tr>
                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                01-April-2017
                            </td>
                            <td>
                                01-April-2018
                            </td>
                            <td>
                                Quarterly
                            </td>
                        </tr>
                    </table>

                    <br/><br/>
                </div>
                <div id="tabs-8" class="tabstyle">
                    <table style="width: 90%;">
                        <tr>
                            <td>
                                <span class="add-on">Title</span>
                            </td>
                            <td colspan="3">
                                <input type="text" name="title" id="title" value="" style="width: 100%;"/>
                            </td>
                            <td>
                                <input type="button" name="browse" id="browse" value="Browse" class=" btn info-btn"/>
                                <input type="button" name="Upload" id="Upload" value="Upload" class="btn-secondary"/>
                            </td>
                        </tr>
                    </table><br/>
                    <h3>Attachment Details</h3>
                    <table style="width:50%; border: 1px; text-align: left;">
                        <tr>
                            <th>SR No</th>
                            <th>Title</th>
                            <th>Download</th>
                        </tr>
                        <tr>
                           <td colspan="3">Attachment Not Uploaded.</td>

                        </tr>

                    </table>

                    <br/><br/>
                    <button class="btn-secondary" >Save</button>
                    <p>
<!--                        <button  class="btn-secondary" name="saveLegalTracker" id="saveLegalTracker" onclick="submitLegalTracker();"> Save Details</button>-->
                    </p>
                </div>
            </div>
        </form>
    </center>
</div>
<script type="text/javascript" src="./../../scripts/validation/jquery.validate.js"></script>
<script>
jQuery('document').ready(function () {
    /*Date Input Initialisation*/
    jQuery('#ll_fromdate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#ll_duedate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#mifl_fssai_startdate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#mifl_fssai_expirydate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#cfa_fromdate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#cfa_duedate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#cfa_fssai_startdate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#cfa_fssai_expirydate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#transporter_fromdate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#transporter_duedate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#transporter_fssai_startdate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#transporter_fssai_expirydate').datepicker({format: 'dd-mm-yyyy', autoclose: true});

    /*Active Tabs for Legal Tracker*/
    jQuery( "#tabs" ).tabs();

    /*Navigation For Tabs */
    var $tabs = jQuery('#tabs').tabs();
    jQuery(".ui-tabs-panel").each(function(i){
      var totalSize = jQuery(".ui-tabs-panel").size() - 1;
      if (i != totalSize) {
        next = i + 1;
        $(this).append("<a href='#' class='next-tab mover' rel='" + next + "'>Next Page </a>");
      }
      if (i != 0) {
        prev = i-1;
        jQuery(this).append("<a href='#' class='prev-tab mover' rel='" + prev + "'> Prev Page</a>");
      }
    });
    jQuery('.next-tab, .prev-tab').click(function() {
           //$tabs.tabs('select', jQuery(this).attr("rel"));
           $tabs.tabs( "option", "active", jQuery(this).attr("rel"));
           return false;
    });
    /*Navigation End*/
});
</script>
