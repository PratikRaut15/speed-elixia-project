<?php
include_once("session/sessionhelper.php");
include_once("lib/bo/MessageManager.php");
include_once("db.php");

$userid = GetLoggedInUserId();
$message_manager = new MessageManager(GetCustomerno());

$count = $message_manager->getPendingCount($userid, $message_manager->rectype["user"]);
if ($count == 0)
    echo "";
else
    echo "<span" . ($count > 99 ? " style='font-size: xx-small;'" : "") . ">" . ($count > 999 ? "!!!" : $count) . "&nbsp;</span>";
?>