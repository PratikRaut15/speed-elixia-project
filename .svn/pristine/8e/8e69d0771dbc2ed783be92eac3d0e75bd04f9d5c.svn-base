<?php
function createmenu($pagename,$title,$new=NULL)
{
if(basename($_SERVER['PHP_SELF']) == "$pagename.php")
    echo "<li class='current_page_item'><a href='".$_SESSION['subdir']."/modules/$pagename/".$pagename.".php' title='$title'>$pagename $new</a></li>";
else
    echo "<li><a href='".$_SESSION['subdir']."/modules/$pagename/".$pagename.".php' title='$title'>$pagename $new</a></li>";
}

/**Ak added**/
function current_page_new($ipagename,$title,$disp_name)
{
    if($_SERVER['REQUEST_URI'] == $_SESSION['subdir']."/modules/$ipagename"){
        echo "<li class='current_page_item'><a href='".$_SESSION['subdir']."/modules/$ipagename' title='$title'>$disp_name</a></li>";
    }
    else{
        echo "<li><a href='".$_SESSION['subdir']."/modules/$ipagename' title='$title'>$disp_name</a></li>";
    }
}

?>
