<?php
function GetSqlite($customerno,$devicekey,$file)
{
    $path = "sqlite:../../../customer/$customerno/$devicekey/sqlitefiles/locate/$file.sqlite";
    $DB = new PDO($path);
    return $DB;
}
function ChkSqlite($customerno,$devicekey,$lastupdated,$deviceid,$devicelat,$devicelong,$trackeeid)
{
    $datefile = date("Ymd",  strtotime($lastupdated));
    $db = GetSqlite($customerno,$devicekey,$datefile);
    $Query[0] = "CREATE TABLE IF NOT EXISTS devicehistory (id INTEGER,deviceid INTEGER,customerno INTEGER, trackeeid INTEGER, devicelat FLOAT, devicelong FLOAT, lastupdated DATETIME, devicekey INTEGER, PRIMARY KEY(id))";
    $Query[1] = "INSERT into devicehistory (deviceid,customerno,trackeeid,devicelat,devicelong,lastupdated,devicekey) values ($deviceid,$customerno,$trackeeid,$devicelat,$devicelong,'$lastupdated',$devicekey)";    
    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    $db->exec($Query[0]);
    $db->exec($Query[1]);
    $db->exec('COMMIT TRANSACTION');
}
?>
