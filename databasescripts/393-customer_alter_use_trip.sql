INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'393', '2016-06-20 16:00:01', 'Ganesh Papde', 'customer alter use_trip', '0'
);

ALTER TABLE `customer` ADD `use_trip` TINYINT(4) NOT NULL AFTER `use_humidity`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 393;


