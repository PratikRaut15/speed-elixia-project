<?php
include_once("session.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/system/Date.php");
include_once("../lib/components/gui/datagrid.php");
include_once("../lib/components/gui/objectdatagrid.php");
include_once("../lib/system/DatabaseManager.php");
include("header.php");

$customerid = GetSafeValueString( isset($_GET["cid"])?$_GET["cid"]:$_POST["customerid"], "long");
$teamid = GetLoggedInUserId();
$db = new DatabaseManager();

if(isset($_POST["createreceipt"]))
{
    // Create a Receipt
    $receiptno = GetSafeValueString($_POST["receiptno"], "string");        
    $receiptdate = GetSafeValueString($_POST["receiptdate"], "string");
    $receiptdatestrtotime = strtotime($receiptdate);
    $finalreceiptdate = date("Y-m-d", $receiptdatestrtotime);
    $amountpaid = GetSafeValueString($_POST["amountpaid"], "string");        
    $today = date("Y-m-d H:i:s");            
    $SQL = sprintf( "INSERT INTO receipt (
    `receiptno` ,`receiptdate` ,`dateadded`,`teamid`,`customerno`,`amount`,`approval`)
    VALUES (
    '%d', '%s', '%s', '%d', '%d', '%d', '%d')" ,
    $receiptno, $finalreceiptdate, Sanitise::DateTime($today), $teamid, $customerid, $amountpaid, 0);
    $db->executeQuery($SQL);    
}

$SQL = sprintf("SELECT c.* from customer c 
where c.customerno = '%d' LIMIT 1 ",$customerid);
$db->executeQuery($SQL);
while($row = $db->get_nextRow())
{
    $custname = $row["customername"];
    $custcompany = $row["customercompany"];
}

?>

<div class="panel">
<div class="paneltitle" align="center">Create Invoice - IMEI</div>
<div class="panelcontents">
    
<script>
var imeiquant = 0;  
function calcdevamt()
{
    if($("devqty").value != "" && $("devrate").value != "")
    {        
        $("devamount").value = parseFloat($("devqty").value) * parseFloat($("devrate").value);
    }
     calcvat();    
     calcdevtotal();  
     showdevgenerate();
}

function calcvat()
{
    $("vat").value = 0 * parseFloat($("devamount").value);
    calcdevtotal();    
}


function calcdevtotal()
{
    if($("devamount").value == "")
    {
        $("devamount").value = 0;
    }
    $("devtotal").value = parseFloat($("devamount").value) + parseFloat($("vat").value);
    showdevgenerate();    
}

function showdevgenerate()
{
    if($("devrate").value == "" || $("devamount").value == "" || $("imei").value == "" ||
        $("devinwords").value == "" || $("devtotal").value == "")
    {
        $("devgeninv").disabled = true;
    }
    else
    {
        $("devgeninv").disabled = false;
    }
}

function addIMEI()
{
    if($("devrate").value !="")
    {
        var imei_id = $('imei').getValue();
        if (imei_id > -1 && $('to_imei_div_' + imei_id) == null)
        {
            imeiquant = imeiquant+1;
            var imei_name = $('imei').options[$('imei').selectedIndex].text;
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "<?php echo $rootpath;?>images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function() { removeIMEI(imei_id); };
            div.id = 'to_imei_div_' + imei_id;
            div.innerHTML = '<span>' + imei_name + '</span><input type="hidden" name="to_imei_' + imei_id + '" value="' + imei_id + '"/>';
            $('imei_list').appendChild(div);
            $(div).appendChild(remove_image);
            $("devqty").value = imeiquant;        
        }
        calcdevamt();
        $('imei').selectedIndex = 0;    
    }
    else
    {
        alert("Please enter the rate first");
    }        
}

function removeIMEI(imei_no)
{
    imeiquant = imeiquant-1;
    $('to_imei_div_' + imei_no).remove();    
    $("devqty").value = imeiquant;    
    calcdevamt();    
}

</script>
<form method="post" action="createinvoiceimei.php"  enctype="multipart/form-data" target="_blank">
<?php
    class imei{
        // Empty class
    }
    $imeis = Array();
    $imeiquery = sprintf("SELECT imei FROM `purchase` where approval=1 AND sold = 0");
    $db->executeQuery($imeiquery);		

    if ($db->get_rowCount() > 0) 
    {
        while ($row = $db->get_nextRow())            
        {
            $imei = new imei();
            $imei->name = $row["imei"];
            $imeis[] = $imei;
        }
    }    
?>
    <table align="center">
        <tr>
            <td>
                Customer No: <b><?php echo($customerid); ?></b>, 
                <input type="hidden" name="customerno" id="customerno" value="<?php echo($customerid); ?>" />
            </td>
            <td>
                Name: <b><?php echo($custname); ?></b>, 
            </td>
            <td>
                Company: <b><?php echo($custcompany); ?></b>,
            </td> 
            <td>
                Date: <b><?php echo(date("F d, Y")); ?></b>
            </td>
        </tr>
    </table>
<table border="1" align="center">
    <tr>
        <th>Description</th>
        <th>Rate</th>
        <th>IMEI</th>
        <th>Amount</th>
    </tr>
    
    <tr>
        <td align="center">Device</td>
        <td align="center"><input type="hidden"size="3" maxlength="3" id="devqty" name="devqty">
            <input type="text" size="7" maxlength="7" id="devrate" name="devrate" onblur="calcdevamt();">/piece</td>
        <td align="center">
        <select name="imei" id="imei" onchange="addIMEI();"> 
        <?php        
            if(isset($imeis))
            {
                foreach($imeis as $thisimei)
                {
                    $imeilist .= "<option value=" . $thisimei->name . ">" . trim($thisimei->name) . "</option>\n";
                }
            }
        ?>
        <option value=-1>Select IMEI</option>
        <?php echo $imeilist;?>                
        </select>
        <div id="imei_list"></div>            
        </td>
        <td align="center"><input type="text" size="7" maxlength="7"  onblur="showdevgenerate();" id="devamount" name="devamount"></td>
    </tr>
    
    <tr>
        <td align="center">VAT (00.00 %)</td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"><input type="text" size="7" maxlength="7" name="vat" id="vat" onblur="calcvat();"></td>
    </tr>
    
    <tr>
        <td align="center">Total (in Rs)</td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"><input type="text" size="7" maxlength="7" name="devtotal" id="devtotal" onblur="calctotal();"></td>
    </tr>    
</table>    
    Total in words: <input type="text" size="105" name="devinwords" id="devinwords" onblur="showdevgenerate();">
    <input type="submit" value="Generate Invoice" id="devgeninv" name="devgeninv" disabled>
</form>    
</div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center">My Invoices - IMEI</div>
<div class="panelcontents">
<?php
$dg = new objectdatagrid();
$dg->SetNoDataMessage("No Invoices");
$dg->Render();
?>
</div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center">Create Invoice - License</div>
<div class="panelcontents">
    
<script>
function calcsoftamt(rate)
{
    if(rate != "")
    {
        if($("softamount").value == "")
        {
            $("softamount").value = 0;
        }            
        $("softamount").value = parseFloat($("softamount").value) + parseFloat(rate);
    }    
    $("licdiscount").value = 0;
    $("licsubtotal").value = $("softamount").value;
    calcservicetax(); 
}

function calclicdiscount()
{
    $("discountperc").value = (parseFloat($("licdiscount").value)/parseFloat($("softamount").value)) * 100;
    $("licsubtotal").value = parseFloat($("softamount").value) - parseFloat($("licdiscount").value);
    calcservicetax();
}


function calcservicetax()
{
    $("servicetax").value = 0.0000 * parseFloat($("licsubtotal").value);
    calctotal();
}

function calctotal()
{
    $("dtotalamount").value = parseFloat($("licsubtotal").value) + parseFloat($("servicetax").value) + parseFloat($("pndgamt").value);
    showgenerate();    
}

function showgenerate()
{
    if($("devicekey").value == "" || $("dinwords").value == "" ||
       $("softamount").value == "" || $("servicetax").value == "" || $("dtotalamount").value == ""
        || $("licsubtotal").value == "")
    {
        $("generateinvoice").disabled = true;
    }
    else
    {
        $("generateinvoice").disabled = false;
    }
}

function addalldevices()
{
    var select_box = $('devicekey');
    for (var i=1; i<select_box.options.length; i++)
    {
        select_box.selectedIndex = i;
        adddevicekey();
    }
}

function adddevicekey()
{
    var devicekey_id = $('devicekey').getValue();
    if (devicekey_id > -1 && $('to_devicekey_div_' + devicekey_id) == null)
    {
        var devicekey_name = $('devicekey').options[$('devicekey').selectedIndex].text;
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "<?php echo $rootpath;?>images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() { removedevicekey(devicekey_id); };
        div.id = 'to_devicekey_div_' + devicekey_id;
        div.innerHTML = '<span>' + devicekey_name + '</span><input type="hidden" name="to_devicekey_' + devicekey_id + '" value="' + devicekey_id + '"/>';
        $('devicekey_list').appendChild(div);
        $(div).appendChild(remove_image);
        pullcontract(devicekey_id);
    }
    $('devicekey').selectedIndex = 0;    
}

function removedevicekey(devicekey_no)
{
    $('to_devicekey_div_' + devicekey_no).remove();    
    deletecontract(devicekey_no);
}

function deletecontract(devicekey_id)
{
    var params = "deviceid=" + encodeURIComponent(devicekey_id);
    params+= "&customerno=" + encodeURIComponent($("customerno").value);    
    new Ajax.Request('pulldevicerateAjax.php',
    {
        parameters: params,
        onSuccess: function(transport)
        {
            var statuscheck = transport.responseText;
            if(statuscheck == "notok")
            {
                // Do nothing
            }
            else
            {
                var cdata = transport.responseText.evalJSON();                        
                var rate = cdata.rate;
                $("softamount").value = parseFloat($("softamount").value) - parseFloat(rate);
                $("licsubtotal").value = parseFloat($("softamount").value) - parseFloat($("licdiscount").value);
                calcservicetax(); 
            }
        },
        onComplete: function()
        {
        }
    });                        
}


function pullcontract(devicekey_id)
{
    var params = "deviceid=" + encodeURIComponent(devicekey_id);
    params+= "&customerno=" + encodeURIComponent($("customerno").value);    
    new Ajax.Request('pulldevicerateAjax.php',
    {
        parameters: params,
        onSuccess: function(transport)
        {
            var statuscheck = transport.responseText;
            if(statuscheck == "notok")
            {
                // Do nothing
            }
            else
            {
                var cdata = transport.responseText.evalJSON();                        
                var rate = cdata.rate;
                calcsoftamt(rate);
            }
        },
        onComplete: function()
        {
        }
    });                        
}

function calcpending()
{
    if($("selmonth").value != -1 && $("selyear").value != -1)
    {
        var params = "customerno=" + encodeURIComponent($("customerno").value);    
        params+= "&month=" + encodeURIComponent($("selmonth").value);            
        params+= "&year=" + encodeURIComponent($("selyear").value);                    
        new Ajax.Request('calcpendingamountAjax.php',
        {
            parameters: params,
            onSuccess: function(transport)
            {
                var statuscheck = transport.responseText;
                if(statuscheck == "notok")
                {
                    // Do nothing
                }
                else
                {
                    var cdata = transport.responseText.evalJSON();                        
                    var pending = cdata.pending;
                    $("pndgamt").value = pending;
                    calctotal();
                }
            },
            onComplete: function()
            {
            }
        });                                        
    }
}

</script>
<form method="post" action="createinvoicedevkey.php" enctype="multipart/form-data" target="_blank">
<?php
    class devkey
    {
        // Do nothing
    }
    $devices = Array();
    $devicesquery = sprintf("SELECT devicekey, rate FROM `devices` where customerno = %d", $customerid);
    $db->executeQuery($devicesquery);		

    if ($db->get_rowCount() > 0) 
    {
        while ($row = $db->get_nextRow())            
        {
            $devkey = new devkey();
            $devkey->name = $row["devicekey"];
            $devkey->rate = $row["rate"];
            $devices[] = $devkey;
        }
    }    
?>
    <table align="center">
        <tr>
            <td>
                Customer No: <b><?php echo($customerid); ?></b>, 
                <input type="hidden" name="customerno" id="customerno" value="<?php echo($customerid); ?>" />
            </td>
            <td>
                Name: <b><?php echo($custname); ?></b>, 
            </td>
            <td>
                Company: <b><?php echo($custcompany); ?></b>,
            </td> 
            <td>
                Date: <b><?php echo(date("F d, Y")); ?></b>
            </td>
        </tr>
    </table>
<table border="1" align="center">
    <tr>
        <th align="center" colspan="3">
            Month: 
            <select id="selmonth" name="selmonth" onchange="calcpending();">
                <option value="-1">Select Month</option>                
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>                
            </select>
            
            Year: 
            <select id="selyear" name="selyear" onchange="calcpending();">
                <option value="-1">Select Year</option>                
                <option value="2012">2012</option>
                <option value="2013">2013</option>
                <option value="2014">2014</option>
            </select>            
            
            Pending Amount: Rs <input type="text" readonly size="5" id="pndgamt" name="pndgamt" value="0">/-
        </th>
    </tr>
    <tr>
        <th>Description</th>
        <th>Device Key</th>
        <th>Amount</th>
    </tr>
    
    <tr>
        <td align="center">Software</td>
        <td align="center">
        <select name="devicekey" id="devicekey" onchange="adddevicekey();"> 
        <?php        
            if(isset($devices))
            {
                foreach($devices as $thisdevice)
                {
                   $devicekeylist .= "<option value=" . $thisdevice->name . ">" . trim($thisdevice->name)." (Rate: Rs ". $thisdevice->rate. " /month )</option>\n";
                }
            }
        ?>
        <option value=-1>Select Device Key</option>
        <?php echo $devicekeylist;?>                
        </select>
       <input type="button" value="Add all" onclick="addalldevices();" />                                    
        <div id="devicekey_list"></div>          
        </td>
        <td align="center"><input type="text" size="7" maxlength="7" name="softamount" id="softamount" onblur="calcservicetax();"></td>
    </tr>
    
    <tr>
        <td align="center">Discount <input type="text" readonly size="5" maxlength="5" name="discountperc" id="discountperc">%</td>
        <td align="center"></td>
        <td align="center"><input type="text" size="7" maxlength="7" name="licdiscount" id="licdiscount" onblur="calclicdiscount();"></td>
    </tr>
    
    <tr>
        <td align="center">Sub-Total</td>
        <td align="center"></td>
        <td align="center"><input type="text" readonly size="7" maxlength="7" name="licsubtotal" id="licsubtotal"></td>
    </tr>    

    <tr>
        <td align="center">Service Tax (00.00 %)</td>
        <td align="center"></td>
        <td align="center"><input type="text" size="7" maxlength="7" name="servicetax" id="servicetax" onblur="calcservicetax();"></td>
    </tr>    
    
    <tr>
        <td align="center">Total (in Rs)</td>
        <td align="center"></td>
        <td align="center"><input type="text" size="7" maxlength="7" name="dtotalamount" id="dtotalamount" onblur="calctotal();"></td>
    </tr>    
</table>    
    Total in words: <input type="text" size="105" name="dinwords" id="dinwords" onblur="showgenerate();">
    <input type="submit" value="Generate Invoice" id="generateinvoice" name="generateinvoice" disabled>
</form>    
</div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center">My Invoices - License</div>
<div class="panelcontents">
<?php
$dg = new objectdatagrid();
$dg->SetNoDataMessage("No Invoices");
$dg->Render();
?>
</div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center">Create Receipt</div>
<div class="panelcontents">
<form method="post" action="createreceipt.php" enctype="multipart/form-data">
    <input type="hidden" name="customerid" value="<?php echo( $customerid ); ?>"/>
<table border="1" align="center">
    <tr>
    <td>Receipt Number</td><td><input name = "receiptno" id="receiptno" type="text" onkeyup="showcreatereceipt();" size="5" maxlength="5"></td>
    <td>Receipt Date</td>
      <td>
            <?php
            $today = date("Y-m-d H:i:s");        
            ?>
        <input name="receiptdate" type="text" id="receiptdate" value="<?php echo date("Y-m-d", $today); ?>" size="10" maxlength="10" /><button id="trigger">...</button>
      </td>                          
    <td>Amount Paid</td><td><input name = "amountpaid" id="amountpaid" type="text" onkeyup="showcreatereceipt();" size="7" maxlength="7"></td>
    <td><select name="type" id="type"><option value="License">License</option>
            <option value="IMEI">IMEI</option>
            
        </select></td>
    </tr>    
</table> 
    
<div align="center">    
<input type="submit" name="createreceipt" id="createreceipt" value="Generate Receipt" disabled/>
</div>
</form>    
<script>
function showcreatereceipt()
{
    if($("receiptno").value == "" || $("amountpaid").value == "")
    {
        $("createreceipt").disabled = true;
    }
    else
    {
        $("createreceipt").disabled = false;        
    }
}

Calendar.setup(
{
    inputField : "receiptdate", // ID of the input field
    ifFormat : "%Y-%m-%d", // the date format
    button : "trigger" // ID of the button
});

function SetAllCheckBoxes(doc)
{
    var c = document.getElementsByTagName('input');
    var d = $("checkall").checked;
    if(d==0)
    {
        for (var i = 0; i < c.length; i++)
        {
            if (c[i].type == 'checkbox')
                c[i].checked = 0;
        }
    }
    if(d==1)
    {
        for (var i = 0; i < c.length; i++)
        {
            if (c[i].type == 'checkbox')
                c[i].checked = 1;
        }
    }
}
</script>

</div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center">Not Approved Receipts</div>
<div class="panelcontents">
<form id="deleteform" name="form" method="post" action="deletereceipt.php">                        
        <input type="hidden" name="customerid" value="<?php echo( $customerid ); ?>"/>
<?php
$db = new DatabaseManager();
$SQL = sprintf("SELECT *, t.name as addedby FROM receipt r INNER JOIN team t ON t.teamid = r.teamid WHERE approval = 0 AND customerno=%d", $customerid);
$db->executeQuery($SQL);

$dg2 = new datagrid( $db->getQueryResult() );
$dg2->AddAction("View/Edit", "../images/add.png", "approvereceipt.php?rid=%d");
$dg2->AddColumn("Receipt Number", "receiptno");
$dg2->AddColumn("Receipt Date", "receiptdate");
$dg2->AddColumn("Amount Paid (in Rs)", "amount");
$dg2->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg2->AddColumn("Added by", "addedby");
$dg2->AddColumn("Type", "type");
$dg2->AddColumn("Select", "group","","grp","checkbox");
$dg2->AddCustomFooter( "Select All<input type='checkbox' name='checkall' id='checkall' onclick=\"SetAllCheckBoxes('myform');\"><input type=\"submit\" name=\"groupdelete\" value=\"Delete\">");
$dg2->SetNoDataMessage("No Receipts");
$dg2->AddIdColumn("receiptid");
$dg2->Render();
?>
</form>    
</div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center">Approved Receipts</div>
<div class="panelcontents">
<?php
$db = new DatabaseManager();
$SQL = sprintf("SELECT *, t.name as addedby FROM receipt r INNER JOIN team t ON t.teamid = r.teamid WHERE approval = 1 AND customerno=%d", $customerid);
$db->executeQuery($SQL);

$dg3 = new datagrid( $db->getQueryResult() );
$dg3->AddColumn("Receipt Number", "receiptno");
$dg3->AddColumn("Receipt Date", "receiptdate");
$dg3->AddColumn("Amount Paid (in Rs)", "amount");
$dg3->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg3->AddColumn("Added by", "addedby");
$dg3->AddColumn("Type", "type");
$dg3->SetNoDataMessage("No Receipts");
$dg3->AddIdColumn("receiptid");
$dg3->Render();
?>
</div>
</div>
<br/>

<?php
include("footer.php");
?>