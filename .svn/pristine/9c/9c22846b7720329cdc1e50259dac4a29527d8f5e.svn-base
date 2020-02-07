-- Insert SQL here.
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'377', '2016-04-12 14:00:00', 'Mrudang Vora', 'Alter Ignition Alert table for cronignition changes', '0'
);



ALTER TABLE `ignitionalert` 
ADD `idleignon_count` TINYINT NOT NULL DEFAULT '0' AFTER `count`
, ADD `running_count` TINYINT NOT NULL DEFAULT '0' AFTER `idleignon_count`;

-- Successful. Add the Patch to the Applied Patches table.


UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 377;