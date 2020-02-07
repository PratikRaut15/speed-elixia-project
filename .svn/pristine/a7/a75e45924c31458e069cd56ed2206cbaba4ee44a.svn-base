INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'413', '2016-09-24 20:18:18', 'Mrudang Vora', 'Add email in checpoint table', '0');

ALTER TABLE `checkpoint` ADD `email` VARCHAR(100) NOT NULL AFTER `phoneno`;
ALTER TABLE `checkpoint` CHANGE `cgeolat` `cgeolat` DECIMAL(11,8) NOT NULL;
ALTER TABLE `checkpoint` CHANGE `cgeolong` `cgeolong` DECIMAL(11,8) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 411;
