
<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
    <thead>

        <tr>

            <th>Sr. No.</th>
            <th>Insurance Dealer Name</th>
            <th colspan="2">Options</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $dealer_list = getAllInsdealer();
        if (isset($dealer_list)) {
            $x = 1;
            foreach ($dealer_list as $dealer) {

                echo "<tr>";
                echo "<td>$x</td>";
                echo "<td>" . $dealer->ins_dealername . "</td>";
                echo "<td><a href='insurance_dealer.php?id=3&insdealerid=$dealer->ins_dealerid'><i class='icon-pencil'></i></a></td>";
                echo "<td><a href='route.php?work=delete&insdelid=$dealer->ins_dealerid' onclick='return confirm('Are you sure you want to delete?');'><i class='icon-trash'></i></a></td>";        
            echo "</tr>";
            $x++;
        }
    } else
        echo
        "<tr>
            <td colspan=100%>No Insurance Company Created</td>
        </tr>";
    ?>
</tbody>
</table>
<form style="width: 48%;" class="simple_form adminform form-horizontal" id="add_insurance_dealer" name="add_insurance_dealer" action="route.php" method="POST" onsubmit="return submitinsuranceDealer();">
    <span id="problem" style="display: none;color: #FE2E2E">Please Enter Insurance Dealer Name</span> 
    <div class="control-group string required"><label class="string required control-label" for="insurance"><abbr title="required" style="color:#FE2E2E">*</abbr> Insurance Dealer Name</label><div class="controls"><input class="string required" id="insurance_dealer_name" name="insurance_dealer_name" size="50" type="text" autofocus="" placeholder="Type Insurance Dealer Company"></div></div>
    <div>
        <input type="submit" name="addinsurancedealer" class="btn  btn-primary" value="Add Insurance Dealer">
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
    function submitinsuranceDealer()
    {
        if (jQuery("#insurance_dealer").val() == "")
        {
            jQuery("#problem").show();
            jQuery("#problem").fadeOut(3000);
            return false;
        }
        else
        {
            jQuery("#add_insurance_dealer").submit();
        }
    }
</script>