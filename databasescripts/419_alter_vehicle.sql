-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'419', '2016-10-10 16:11:01', 'Gp', 'alter vehicle add 3 columnstaxdate,permitdate,fitnessdate', '0'
);

-- Insert SQL here.

ALTER TABLE `vehicle` ADD `tax_date` DATE NOT NULL AFTER `purchase_date`, ADD `permit_date` DATE NOT NULL AFTER `tax_date`, ADD `fitness_date` DATE NOT NULL AFTER `permit_date`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 419;
