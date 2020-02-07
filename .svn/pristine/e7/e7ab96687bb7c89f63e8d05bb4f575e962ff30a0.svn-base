
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'456', '2016-02-07 17:12:00', 'Arvind Thakur', 'Customize Warehouse', '0'
);


INSERT INTO `customtype`(`name`) VALUES('Warehouse');


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 456;