-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'408', '2016-09-08 17:05:01', 'Ganesh Papde', 'alter maintenance-maintenance-history', '0');
-- Insert SQL here.

ALTER TABLE `maintenance` ADD `chequeno` VARCHAR(50) NOT NULL AFTER `ofasno`, ADD `chequeamt` DECIMAL(10,2) NOT NULL AFTER `chequeno`, ADD `chequedate` DATE NOT NULL AFTER `chequeamt`, ADD `tdsamt` DECIMAL(10,2) NOT NULL AFTER `chequedate`;

ALTER TABLE `maintenance_history` ADD `chequeno` VARCHAR(50) NOT NULL AFTER `ofasno`, ADD `chequeamt` DECIMAL(10,2) NOT NULL AFTER `chequeno`, ADD `chequedate` DATE NOT NULL AFTER `chequeamt`, ADD `tdsamt` DECIMAL(10,2) NOT NULL AFTER `chequedate`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 408;
