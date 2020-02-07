
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'431', NOW(), 'Shrikant Surywanshi', 'Checkpoint Delay And Checkpoint Id In Vehicle Id', '0'
);


/*Cron All Changes*/
ALTER TABLE `checkpointmanage`
ADD `inTime` DATETIME  NULL AFTER `conflictstatus`
, ADD `outTime` DATETIME NULL AFTER `inTime`
, ADD `isDelayExpected` TINYINT(1) NOT NULL DEFAULT '0' AFTER `outTime`;



ALTER TABLE `vehicle` ADD `checkpointId` INT NOT NULL DEFAULT '0' AFTER `hum_max`;


UPDATE 	dbpatches
SET 	patchdate = NOW()
	, isapplied =1
WHERE 	patchid = 431;
