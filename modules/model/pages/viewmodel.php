<?php
$make = getmake();
?>
<div id="error">
    <span id="error_modalname" style="color:#FE2E2E;display: none;">Please Enter Model Name</span>
    <span id="error_makename" style="color:#FE2E2E;display: none;">Please Select Make</span>
</div>
<table class="table borderless" style="alignment-baseline: middle">
    <tr>

        <td>

            <select name="makeid" id="makeid">
                <option value="0">Select Make</option>
                <?php foreach ($make as $makes) {
                    ?>

                    <option value="<?php echo $makes->id ?>"><?php echo $makes->name; ?></option>

                <?php }
                ?>
            </select>
            <input type="button" name="search" id="search" class=" btn btn-primary"value="Search" onclick="getmodel();"/>

        </td>


    </tr>
</table>
<div id="disp" style="display:none">
    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
        <thead>

            <tr>

                <th>Sr. No.</th>
                <th>Model Name</th>
                <th colspan="2">Options</th>
            </tr>
        </thead>
        <tbody id="demo">

        </tbody>
    </table>
    <form style="width: 48%;" class="simple_form adminform form-horizontal" id="add_model" name="add_model" action="route.php" method="POST" onsubmit="return submitmodel();">

        <span id="problem" style="display: none;color: #FE2E2E">Please Enter Model Name</span>
        <span id="error_make" style="display: none;color: #FE2E2E">Please Select Make Name</span>

        <div class="control-group string required"><label class="string required control-label" for="model_name"><abbr title="required" style="color:#FE2E2E">*</abbr> model Name</label><div class="controls"><input class="string required" id="modelname" name="modelname" size="50" type="text" autofocus="" placeholder="Type model Of Vehicle"></div></div>
        <div>
            <input type="hidden" name="make_id" id="make_id" value=''>
            <input type="submit" name="addmodel" class="btn  btn-primary" value="Add model">
        </div>
    </form>
</div>
<style>
    .borderless td, .borderless th {
        border: none;
        text-align: center;
    }    

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
    function submitmodel()
    {
        if (jQuery("#modelname").val() == "")
        {
            jQuery("#error_modalname").show();
            jQuery("#error_modalname").fadeOut(3000);
            return false;
        } else if (jQuery("#makeid").val() == "0") {

            jQuery("#error_makename").show();
            jQuery("#error_makename").fadeOut(3000);
            return false;
        }
        else
        {
            jQuery("#add_model").submit();
        }
    }

    function getmodel()
    {

        if (jQuery("#makeid").val() == "0")
        {
            jQuery("#error_makename").show();
            jQuery("#error_makename").fadeOut(3000);

        } else {
            jQuery("#disp").show();
            var makeid = jQuery('#makeid').val();
            jQuery.ajax({
                type: "POST",
                url: "route.php",
                cache: false,
                data: {makeid: makeid},
                success: function (res) {
                    var data = jQuery.parseJSON(res);
                    //console.log(data);

                    if (data.length === 0) {
                        var emp = '';
                        emp += "<tr>"
                        emp += "<td colspan=100%>No Model Created</td>"
                        emp += "</tr>"
                        jQuery('#make_id').val(makeid);
                        jQuery('#demo').html(emp);

                    }

                    else {
                        details(data, makeid);
                    }
                }
            });
        }
    }
    function details(data, makeid)
    {
        var tags = '';

        jQuery(data).each(function (i, v) {
            tags += "<tr><td>" + v.x + "</td>";
            tags += "<td>" + v.name + "</td>";
            if (v.customerno != 0) {
                tags += "<td><a href='model.php?id=4&modelid=" + v.model_id + "'><i class='icon-pencil'></i></a></td>"
                tags += "<td><a href='route.php?delmodelid=" + v.model_id + "'><i class='icon-trash'></i></a></td></tr>"
            } else {
                tags += "<td colspan='2'>---</td></tr>"
            }
            jQuery('#make_id').val(makeid);

        });


        jQuery('#demo').html(tags);
    }
</script>