<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 2) {
            echo "<li><a class='selected' href='insurance_dealer.php?id=2'>View Insurance Dealer</a></li>";
        } else {
            echo "<li><a href='insurance_dealer.php?id=2'>View Insurance Dealer</a></li>";
        }
        if ($_GET['id'] == 3) {
            echo"<li><a class='selected' href='insurance_dealer.php?id=3&insdealerid=$_GET[insdealerid]'>Edit Insurance Dealer</a></li>";
        }
        //if ($_GET['id'] == 3)
        //echo"<li><a class='selected' href='insurance_dealer.php?id=3'>Upload Insurance Dealer Data</a></li>";
        //else
        //echo "<li><a href='insurance_dealer.php?id=3'>Upload Insurance Dealer Data</a></li>";
    } else {
        //echo "<li><a href='insurance_dealer.php?id=1'>Add Insurance Dealer</a></li>";
        echo "<li><a class='selected' href='insurance_dealer.php?id=2'>View Insurance Dealer</a></li>";
    }
    ?>
</ul>
<?php
include 'insurance_dealer_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 2)
    include 'pages/viewinsurancedealer.php';
//else if ($_GET['id'] == 3)
//    include 'pages/uploadinsurancedealer.php';
else if ($_GET['id'] == 3) {
    include 'pages/editinsurancedealer.php';
}
?>