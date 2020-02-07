    <?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include("header.php");

$teamId = GetLoggedInUserId();
?>
<style>
    table tr td{
        vertical-align: middle;
    }
/*.field-icon {
float: right;
margin-left: -25px;
margin-top: -25px;
position: relative;
z-index: 2;
}
*/
</style>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css'>
<div class="panel">
    <div class="paneltitle" align="center">Account Settings</div>
    <div class="panelcontents">
        <form method="post" name="team_account_settings" id="team_account_settings">
            <table width="100%">
                <tr>
                <input type="hidden" name="team_id" id="team_id" value="<?php echo $teamId; ?>">
                <td>Login<span style="color:red;">* </span></td><td><input name = "tlogin" id="tlogin" type="text"  placeholder="Enter Username"></td>
                <td>Password <input name = "tpassword" id="tpassword" type="password"  placeholder="Enter Password" required>
                </td>
                <td>Re-enter Password <input name="tpassword2" id="tpassword2" type="password"  placeholder="Enter Password"></td>
                </tr>
            </table>
            <input style="margin: 0 45%;" type="button" id="submitpros" value="Submit" onclick="editSettings();" />
        </form>
    </div>
</div>
<br/>
<br/>
<script>
    var team_id = <?php echo $teamId;?>;
    jQuery(document).ready(function () {  
        jQuery.ajax({
            type: "POST",
            url: "route_team.php",
            data: "get_team_members=1&team_id="+team_id,
            success: function(data){
                var temp_result=JSON.parse(data);
                var result = temp_result[0];
                $("#tlogin").val(result.username)
            }
        });        
    });

</script>
<script>
    function editSettings(){
        var tlogin = $("#tlogin").val();
        var tpassword = $("#tpassword").val();
        var tpassword2 = $("#tpassword2").val();
        if(tlogin==""){
            alert("Please enter loginname");
            return false;
        }
        if(tpassword!="" && tpassword.length<6){
            alert("Password length should be not be less than 6 digits.");
            return false;
        }
        else if(tpassword!=tpassword2){
            alert("Passwords do not match.");
            return false;
        }else{

           var data = $("#team_account_settings").serialize();
           jQuery.ajax({
                    type: "POST",
                    url: "route_team.php",
                    data: "update_team_settings=1&"+data,
                    success: function(data){
                        var result=JSON.parse(data);
                        if(result=="1"){
                            alert("Settings Saved Successfully.");
                            window.location.reload();
                        }
                        else if(result=="0"){
                            alert("Username already exists.");
                            return false;
                        }
                        else{
                            alert("Please Try Again.");
                        }
                    }
            });
        }
    }
</script>
