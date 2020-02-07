<?php
$creationstart=strtok(microtime()," ")+strtok(" ");
$dbhost="localhost";
$dbname="elixia5_mobility";
$dbuser="elixia5_Mobility";
$dbpass="eptspolice";
$mailto="sanketsheth1@gmail.com";
$subject="Backup DBEM";
$from_name="Elixia Mobility";
$from_mail="noreply@elixiatech.com";
mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);
$tablesblocklist=array();
$tables = array();
$result = mysql_query("SHOW TABLES");
while($row = mysql_fetch_row($result))
$tables[] = $row[0];
foreach($tables as $table) {
if (!isset($tablesblocklist[$table])) {
$result = mysql_query("SELECT * FROM $table");
$return.= "DROP TABLE IF EXISTS $table;";
$row2 = mysql_fetch_row(mysql_query("SHOW CREATE TABLE $table"));
$return.= "\n\n".$row2[1].";\n\n";
while($row = mysql_fetch_row($result)) {
$return.= "INSERT INTO $table VALUES(";
$fields=array();
foreach ($row as $field)
$fields[]="'".mysql_real_escape_string($field)."'";
$return.= implode(",",$fields).");\n";
}
$return.="\n\n\n";
}
}
$filename='db-backup-'.date("Y-m-d H.m.i").'.sql.bz2';
$content=chunk_split(base64_encode(bzcompress($return,9)));
$uid=md5(uniqid(time()));
$header=
"From: ".$from_name." <".$from_mail.">\r\n".
"Reply-To: ".$replyto."\r\n".
"MIME-Version: 1.0\r\n".
"Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n".
"This is a multi-part message in MIME format.\r\n".
"--".$uid."\r\n".
"Content-type:text/plain; charset=iso-8859-1\r\n".
"Content-Transfer-Encoding: 7bit\r\n\r\n".
$message."\r\n\r\n".
"--".$uid."\r\n".
"Content-Type: application/octet-stream; name=\"".$filename."\"\r\n".
"Content-Transfer-Encoding: base64\r\n".
"Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n".
$content."\r\n\r\n".
"--".$uid."--";
if(mail($mailto,$subject,"",$header))
{
    $exequery = mysql_query("DELETE FROM notifications WHERE timestamp < DATE_SUB(NOW(), INTERVAL 2 DAY)");
    if (!$exequery) {
        die('Invalid query: ' . mysql_error());
    }    
}
$creationend=strtok(microtime()," ")+strtok(" ");
$creationtime=number_format($creationend-$creationstart,4);
echo "Database backup compressed to bz2 and sent by email in $creationtime seconds";
?>