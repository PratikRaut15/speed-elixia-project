
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'427', '2016-11-03 03:30:01', 'Ganesh Papde', 'alter freeze vehicle ', '0'
);

ALTER TABLE `freezelog` ADD `odometer` BIGINT NOT NULL AFTER `devicelong`;


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 427;
