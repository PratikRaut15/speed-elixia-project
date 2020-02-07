
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'428', NOW(), 'Shrikant Suryawanshi', 'Delicia route checkpoint etastatus', '0'
);

ALTER TABLE `routeman` ADD `etaStatus` VARCHAR(50) NOT NULL AFTER `sequence`;


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	,isapplied =1 
WHERE 	patchid = 428;
