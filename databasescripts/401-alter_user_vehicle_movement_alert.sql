INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'401', '2016-08-17 15:00:01', 'Ganesh Papde', 'Alter user vehicle movement alert', '0'
);

ALTER TABLE `user` ADD `vehicle_movement_alert` TINYINT(1) NOT NULL DEFAULT '0' AFTER `dailyemail`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 401;
