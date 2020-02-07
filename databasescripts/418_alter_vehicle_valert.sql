
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'418', 'NOW()', 'GP', 'alter column names vehicle or valert table', '0'
);


ALTER TABLE `valert` CHANGE `speed_gov_expiry` `other5_expiry` DATETIME NOT NULL, CHANGE `speed_gov_sms_email` `other5_sms_email` TINYINT(1) NOT NULL, CHANGE `fire_expiry` `other6_expiry` DATETIME NOT NULL, CHANGE `fire_sms_email` `other6_sms_email` INT(1) NOT NULL;


ALTER TABLE `vehicle` CHANGE `speed_gov_filename` `other_upload5` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `fire_extinguisher_filename` `other_upload6` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;




UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 418;
