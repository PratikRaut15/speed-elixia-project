INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'401', '2016-07-26 05:11:01', 'Ganesh Papde', 'Alter Master Order', '0'
);

ALTER TABLE `master_orders` ADD `is_mobileorder` TINYINT(1) NOT NULL DEFAULT '0' AFTER `flag`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 401;
