<?php
    
     $customerno = $_SESSION['customerno'];
   
?>
<style>
     .recipientbox {
        border: 1px solid #999999;
        float: left;
        font-weight: 700;
        padding: 4px 27px;
        /*    width: 100px;*/

        float:left;
        -webkit-transition:all 0.218s;
        -webkit-user-select:none;
        background-color:#000;
        /*background-image:-webkit-linear-gradient(top, #4D90FE, #4787ED);*/
        border:1px solid #3079ED;
        color:#FFFFFF;
        text-shadow:rgba(0, 0, 0, 0.0980392) 0 1px;
        border:1px solid #DCDCDC;
        border-bottom-left-radius:2px;
        border-bottom-right-radius:2px;
        border-top-left-radius:2px;
        border-top-right-radius:2px;

        cursor:default;
        display:inline-block;
        font-size:11px;
        font-weight:bold;
        height:27px;
        line-height:27px;
        min-width:46px;
        padding:0 8px;
        text-align:center;

        border: 1px solid rgba(0, 0, 0, 0.1);
        color:#fff !important;
        font-size: 11px;
        font: bold 11px/27px Arial,sans-serif !important;
        vertical-align: top;
        margin-left:5px;
        margin-top:5px;
        text-align:left;
    }
    .recipientbox img {
        float:right;
        padding-top:5px;
    }
    .labelwidth{
        width:200px;
    }
    #mail_pop{
        position: absolute;
        top:300px;
    }
</style>
<div id='mail_pop' class='modal hide in' style='display:none;' >
    <div class='modal-header'>
        <button class='close' data-dismiss='modal'>×</button>
        <h4 style='color:#0679c0'>Mail Report</h4>
    </div>
    <div class='modal-body'>
        <span id='mailStatus'></span><br/>
        <form>
            <span class='add-on' style='color:#000000'>To(Email)</span>&nbsp;<input  type="email" name="emailText" id="emailText" size="20" value="" autocomplete="off" placeholder="Enter email id" onkeyup="refreshFun(<?php echo $customerno; ?>)" />
            <input  type="hidden" name="emailText1"  id="emailText1" size="20">
            <input type="button" style="float:right;margin-right: 40px;" class="g-button g-button-submit" onclick="insertMailId(<?php echo $customerno; ?>)" value="Add Mail Id" name="addMail"></button>                       
            <span class='add-on' style='color:#000000'>PDF</span>&nbsp;<input type='radio' name='emailtype' value='pdf' checked/>
            <span class='add-on' style='color:#000000'>Excel</span>&nbsp;<input type='radio' name='emailtype' value='excel'/>
            <input  type="hidden" name="getid"  id="getid" size="20">
            <input type='hidden' name='sentoEmail' id="sentoEmail" required />
            <br clear='both'>
            <div id="listvehicle1" ></div>
            <div style="clear:both"></div>
            <br>
            <div>
                <div id="list_of_id" style="text-align: left"></div>
                <br/><br/>
                <span class='add-on' style='color:#000000'>Comment</span>&nbsp;<textarea name="mailcontent" id="mailcontent" row="2" cols="35" required=""></textarea>
                <div style='width:15%;border:4px;'><input type="button" class="g-button g-button-submit" value="Send" name="sendMail" onclick="<?php echo $mail_function; ?>" id="sendMail"></div>
            </div>
        </form>
    </div> 
</div>
<script type="text/javascript" src="../../scripts/reports/mail_pop_up.js"></script>

