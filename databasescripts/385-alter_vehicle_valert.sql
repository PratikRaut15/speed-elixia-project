INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'385', 'NOW()', 'Ganesh Papade', 'Vehicle Alert', '0'
);

ALTER TABLE `vehicle` ADD `other_upload4` VARCHAR(250) NOT NULL AFTER `other_upload3`;

ALTER TABLE `valert` ADD `other4_expiry` DATETIME NOT NULL AFTER `other3_sms_email`, ADD `other4_sms_email` TINYINT(1) NOT NULL AFTER `other4_expiry`;

ALTER TABLE `fuelstorrage` ADD `additional_amount` DECIMAL(11,2) NOT NULL AFTER `amount`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 385;
