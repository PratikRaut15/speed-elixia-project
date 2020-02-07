<?php
for($i=1;$i<=660;$i++){
$output = shell_exec('du -sh /var/www/html/speed/customer/'. $i.'/log');
echo "<br>$output";
}
?>
