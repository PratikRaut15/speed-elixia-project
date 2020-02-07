<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='enh_checkpoint.php?id=1'>Create Checkpoint</a></li>";
    else
        echo "<li><a href='enh_checkpoint.php?id=1'>Create Checkpoint</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='enh_checkpoint.php?id=2'>View Checkpoints</a></li>";
    else
        echo "<li><a href='enh_checkpoint.php?id=2'>View Checkpoints</a></li>";
    if($_GET['id']==3)
        echo"<li><a class='selected' href='enh_checkpoint.php?id=3&enh_chkid=$_GET[enh_chkid]'>Edit Checkpoint</a></li>";
}
    else
    {
        echo "<li><a class='selected' href='enh_checkpoint.php?id=1'>Create Checkpoint</a></li>";
        echo "<li><a href='enh_checkpoint.php?id=2'>View Checkpoints</a></li>";
    }
?>
</ul>
<?php
include 'enh_checkpoint_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/createchk.php';
else if($_GET['id']==2)
    include 'pages/viewchk.php';
else if($_GET['id']==3)
    include 'pages/editchk.php';
?>
