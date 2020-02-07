<?php
session_start();
ini_set('include_path',ini_get('include_path'). PATH_SEPARATOR .$_SERVER['DOCUMENT_ROOT'] . $subdir . '/lib/');
//$customerno = GetCustomerno();
$customerno =$_SESSION['customerno'];
define('SECURE_ROOT', $subdir);
define('SITE_ROOT', $subdir);
define('INCLUDE_ROOT', $_SERVER['DOCUMENT_ROOT'] . $subdir);
define('ITEMS_PER_PAGE',isset($itemsperpage)?$itemsperpage:12);

define('ImagesFolder', $includeroot . 'images/');
define('XMLFolder',  $includeroot.'xml/');

// Customer Constants
define('CheckpointImagesFolder',  $includeroot.'/customer/' . $customerno . '/images/checkpoint/');
define('Thumb48Folder',  $includeroot.'/customer/' . $customerno . '/images/checkpoint/thumb48/');
define('Thumb48FolderRelative',  $subdir.'/customer/' . $customerno . '/images/checkpoint/thumb48/');

define('TrackeeImagesFolder',  $includeroot.'/customer/' . $customerno . '/images/trackee/');
define('Thumb48TrackeeFolder',  $includeroot.'/customer/' . $customerno . '/images/trackee/thumb48/');
define('Thumb48TrackeeFolderRelative',  $subdir.'/customer/' . $customerno . '/images/trackee/thumb48/');

define('CustomerFolder',  $subdir.'/customer/' . $customerno . '/');

define('CustomerXMLFolder',  $includeroot.'/customer/' . $customerno . '/xml/');

define('CustomerSQLite',  $includeroot.'customer/' . $customerno . '/sqlitefiles/');
?>