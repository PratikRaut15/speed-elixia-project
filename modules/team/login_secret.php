<?php

include_once("session.php");
include_once("db.php");
//include_once("../constants/constants.php");
$message="";

if(isset($_POST["username"]) && isset($_POST["password"]))
{
    // Attempting to Log in.
    include_once("../../lib/system/DatabaseManager.php");
    $db = new DatabaseManager();
    $username = GetSafeValueString($_POST["username"], "string");
    $password = GetSafeValueString($_POST["password"], "string");
    $SQL = sprintf("SELECT st.* FROM ".DB_PARENT.".team st 
                    where st.username = '%s' and st.password = '%s' 
                    limit 1 ", $username, $password);
    $db->executeQuery($SQL);
    while( $row = $db->get_nextRow())
    {
        SetUsername($row["username"]);
        SetLoggedInUserId($row["teamid"]);
        SetLoggedInrid($row["rid"]);
        SetLoginUser($row["name"]);
        SetRole($row["role"]); 
        if(IsDealer()){
            $_SESSION["sessiondistributorid"] = $row["distributor_id"];
        }

        if(IsData())
        {
            header("Location: realtime.php");            
        }else if(IsDistributor()){
            header("Location: dealers.php");
        }else if(IsDealer()){
            header("Location: retailers.php");
        }else if(IsRepair()){
            header("Location: repairview.php");
        }else
        {
            header("Location: customers.php");
        }
        exit;
    }
    $message="Invalid Login";
}

include("header.php");
?>
<div class="panel">
<div class="paneltitle" align="center">Elixia Team Portal</div>
<div class="panelcontents">
<?php echo($message);?>
    <h3>Please Log in</h3>
    <form method="post" action="login_secret.php">
    <div><label for="username">Username:</label><input id="username" type="text" name="username"/></div>
    <div><label for="password">Password: </label><input id="password" type="password" name="password"/></div>
    <div><input type="submit" value="Login"/></div>
    </form>
</div>
</div>
</div>
<?php
include("footer.php");
?>