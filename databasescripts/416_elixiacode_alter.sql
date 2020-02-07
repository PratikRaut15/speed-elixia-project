
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'416', 'NOW()', 'GP', 'add column days', '0'
);


ALTER TABLE `elixiacode` ADD `days` INT(11) NOT NULL AFTER `expirydate`;



UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 416;
