INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'412', '2016-09-21 15:12:01', 'Ganesh Papde', 'add 3 cols', '0');


ALTER TABLE `vehicle` ADD `puc_filename` VARCHAR(100) NOT NULL AFTER `transmitter2`, ADD `registration_filename` VARCHAR(100) NOT NULL AFTER `puc_filename`, ADD `insurance_filename` VARCHAR(100) NOT NULL AFTER `registration_filename`;



UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 412;