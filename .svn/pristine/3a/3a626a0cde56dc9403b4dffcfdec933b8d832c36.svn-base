
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'447', '2017-01-14 19:09:40', 'Shrikant Suryawanshi', 'Temperature Sensor Specific Alerts', '0'
);


ALTER TABLE `comqueue` ADD `tempsensor` TINYINT NOT NULL AFTER `fenceid`;

CREATE TABLE tempSensorSpecificAlert(
probealertid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
userid INT NOT NULL,
tempSensor1 TINYINT NOT NULL,
tempSensor2 TINYINT NOT NULL,
tempSensor3 TINYINT NOT NULL,
tempSensor4 TINYINT NOT NULL,
customerno INT NOT NULL,
created_by INT NOT NULL DEFAULT 0,
created_on DATETIME NOT NULL,
updated_by INT NOT NULL DEFAULT 0,
updated_on DATETIME NOT NULL,
isdeleted TINYINT(1) DEFAULT 0
);

INSERT INTO tempSensorSpecificAlert(userid,tempSensor1,tempSensor2,tempSensor3,tempSensor4,customerno,created_on)
SELECT userid,1,1,1,1,a.customerno,'2017-01-11 16:11:00'
FROM user a
INNER JOIN customer on customer.customerno = a.customerno
WHERE (a.temp_email != '0' OR a.temp_sms != '0' OR a.temp_telephone != '0' OR a.temp_mobilenotification!='0')
AND customer.temp_sensors != 0
AND a.isdeleted = 0;


UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 447;
