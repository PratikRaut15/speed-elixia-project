<form style="width: 48%;" class="simple_form adminform form-horizontal" id="add_task" name="add_task" action="route.php" method="POST">
    <span id="problem" style="display: none; color:red;">Please enter name<br></span>
    <span id="amounterrblank" style="display: none; color:red;">Please enter unit amount<br> </span>
    <span id="amounterr" style="display: none; color:red;">Unit Amount should be greater than zero <br> </span>
    <span id="discerr" style="display: none; color:red;">Discount amount should not be greater than Unit amount<br></span>
    <span id="discerrblank" style="display: none; color:red;">Please enter discount amount <br></span>
    <div class="control-group string required">
        <label class="string required control-label" for="service_task_name">
            <span>*</span> Name
        </label>
        <div class="controls">
            <input class="string required" id="taskname" name="taskname" size="50" type="text">
            <p class="help-block">Type of service task carried out during a vehicle service</p>
        </div>
    </div>
    <div class="control-group string required">
        <label class="string required control-label">
            Unit Amount
        </label>
        <div class="controls">
            <input id="taskamount" name="taskamount" size="50" type="text" style="width:200px;" placeholder="0.00" onkeypress="return isNumber(event)">
        </div>
    </div>
    <div class="control-group string required">
        <label class="string required control-label">
            Unit Discount
        </label>
        <div class="controls">
            <input id="taskdiscount" name="taskdiscount" size="50" type="text" style="width:200px;" placeholder="0.00" onkeypress="return isNumber(event)">
        </div>
    </div>
    <div class="form-actions">
        <input type="button" name="adduserdetails" class="btn  btn-primary" value="Add task" onclick="submittask();">
    </div>
</form>
<style>
.form-horizontal .control-label {
    float: left;
    width: 160px;
    padding-top: 5px;
    text-align: right;
}
.adminform {
padding: 7px 14px;
margin: 10px 0 10px;
list-style: none;
background-color: #fbfbfb;
background-image: -moz-linear-gradient(top, #fff, #f5f5f5);
background-image: -ms-linear-gradient(top, #fff, #f5f5f5);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fff), to(#f5f5f5));
background-image: -webkit-linear-gradient(top, #fff, #f5f5f5);
background-image: -o-linear-gradient(top, #fff, #f5f5f5);
background-image: linear-gradient(top, #fff, #f5f5f5);
background-repeat: repeat-x;
border: 1px solid #ddd;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffff', endColorstr='#f5f5f5', GradientType=0);
-webkit-box-shadow: inset 0 1px 0 #ffffff;
-moz-box-shadow: inset 0 1px 0 #ffffff;
box-shadow: inset 0 1px 0 #ffffff;
}
</style>
<script>
function submittask1()
{
    if(jQuery("#taskname").val() == "")
    {
        jQuery("#problem").show();
        jQuery("#problem").fadeOut(3000);                 
    }
    else
    {
        jQuery("#add_task").submit();
    }
}


function submittask()
{
    var discount = "";
    var amount = "";
    var taskname = jQuery("#taskname").val();
    amount = jQuery("#taskamount").val();
    discount = jQuery("#taskdiscount").val();
    if (taskname == "")
    {
        jQuery("#problem").show();
        jQuery("#problem").fadeOut(3000);
    }else if(amount == ""){
            jQuery("#amounterrblank").show();
            jQuery("#amounterrblank").fadeOut(3000);
            return false;
    }else if(amount <= 0){
            jQuery("#amounterrblank").show();
            jQuery("#amounterrblank").fadeOut(3000);
            return false;
    }else if(discount ==""){
            jQuery("#discerrblank").show();
            jQuery("#discerrblank").fadeOut(3000);
            return false;
    }else if(parseFloat(discount)>parseFloat(amount)){
            jQuery("#discerr").show();
            jQuery("#discerr").fadeOut(3000);
            return false;
    }else{
            jQuery("#add_task").submit();
    } 
}






    function isNumber(evt){
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>