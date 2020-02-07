
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'426', '2016-10-28 17:11:01', 'Arvind Thakur', 'refreshtime column added to user table', '0'
);

ALTER TABLE user
ADD COLUMN refreshtime tinyint(1) DEFAULT 1;

UPDATE `user` SET `refreshtime` = '0' WHERE `user`.`customerno` = 267;


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 426;
