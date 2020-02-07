INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'399', '2016-07-22 10:58:01', 'Ganesh Papde', 'Maintenance table changes', '0'
);
ALTER TABLE `fuelstorrage` ADD `notes` VARCHAR(250) NOT NULL AFTER `refilldate`, ADD `ofasnumber` VARCHAR(50) NOT NULL AFTER `notes`;

ALTER TABLE `maintenance_tasks` ADD `tax_amount` DECIMAL(10,2) NOT NULL AFTER `amount`, ADD `discount` INT(11) NOT NULL AFTER `tax_amount`;

ALTER TABLE `maintenance_parts` ADD `tax_amount` DECIMAL(10,2) NOT NULL AFTER `amount`, ADD `discount` INT(11) NOT NULL AFTER `tax_amount`;

ALTER TABLE `fuelstorrage` ADD `isclosed` TINYINT(1) NOT NULL DEFAULT '0' AFTER `notes`;


-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 399;
