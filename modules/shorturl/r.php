<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
echo $_GET["c"];
if ($_GET["c"]=='') {
    header("Location: shorten.html");
    exit;
}
$code = $_GET["c"];
$shortUrl = new ShorturlManager();
//print_r($shortUrl);
try {
    $url = $shortUrl->shortCodeToUrl($code);
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $url);
} catch (Exception $e) {
    print_r($e);
    header("Location: error.html");
    exit;
}
