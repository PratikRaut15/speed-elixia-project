<?php
   ob_start ("ob_gzhandler");
   header ("content-type: text/css; charset: UTF-8");
   header ("cache-control: must-revalidate");
   $offset = 60 * 60;
   $expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
   header ($expire);
?>