
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'417', NOW(), 'GP', 'add column vehicle or valert table', '0'
);


ALTER TABLE `elixiacode` ADD `days` INT(11) NOT NULL AFTER `expirydate`;


ALTER TABLE `vehicle` ADD `speed_gov_filename` VARCHAR(100) NOT NULL AFTER `insurance_filename`, ADD `fire_extinguisher_filename` VARCHAR(100) NOT NULL AFTER `speed_gov_filename`;

ALTER TABLE `valert` ADD `speed_gov_expiry` DATETIME NOT NULL AFTER `insurance_sms_email`, ADD `speed_gov_sms_email` TINYINT(1) NOT NULL AFTER `speed_gov_expiry`, ADD `fire_expiry` DATETIME NOT NULL AFTER `speed_gov_sms_email`, ADD `fire_sms_email` INT(1) NOT NULL AFTER `fire_expiry`;



UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 417;
