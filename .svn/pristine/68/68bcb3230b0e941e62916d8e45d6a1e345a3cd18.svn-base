<?php
for($i=1;$i<=660;$i++){
$dir = '/var/www/html/speed/customer/'. $i;
if(is_dir($dir)){
	$output = shell_exec('du -sh '. $dir);
	echo "<br>$output";
	}
}
// du -hs /var/www/html/speed/customer/63/unitno/* | sort -hr

?>
