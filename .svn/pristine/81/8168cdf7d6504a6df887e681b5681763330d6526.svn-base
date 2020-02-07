INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`)
VALUES ('721', '2019-07-16 20:02:00', 'Arvind Thakur','temperature alert for every interval', '0');


DROP TABLE IF EXISTS alertTempUserMapping;
CREATE TABLE `alertTempUserMapping` (
  `atumid` int(11) PRIMARY KEY AUTO_INCREMENT,
  `uid` INT(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
    temp1_intv DATETIME DEFAULT '0000-00-00 00:00:00',
    temp2_intv DATETIME DEFAULT '0000-00-00 00:00:00',
    temp3_intv DATETIME DEFAULT '0000-00-00 00:00:00',
    temp4_intv DATETIME DEFAULT '0000-00-00 00:00:00',
  `customerno` int(11) NOT NULL,
  `insertedby` int(11) NOT NULL,
  `insertedon` datetime DEFAULT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO alertTempUserMapping(uid
        , vehicleid
        , userid
        , temp1_intv 
        , temp2_intv
        , temp3_intv
        , temp4_intv
        , customerno)
SELECT  uid
        , un.vehicleid
        , u.userid
        , temp1_intv 
        , temp2_intv
        , temp3_intv
        , temp4_intv
        , u.customerno
FROM    `user` u 
LEFT OUTER JOIN unit un ON un.customerno = u.customerno
LEFT OUTER JOIN vehiclewise_alert va ON va.customerno = un.customerno AND va.vehicleid = un.vehicleid AND va.userid = u.userid 
WHERE   u.isdeleted = 0
AND     (u.temp_email = 1 OR u.temp_sms = 1 OR temp_telephone = 1 OR temp_mobilenotification = 1)
AND     un.trans_statusid NOT IN (10,22)
AND     va.temp_active = 1;


UPDATE  dbpatches
SET     updatedOn = DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE)
        ,isapplied = 1
WHERE   patchid = 721;