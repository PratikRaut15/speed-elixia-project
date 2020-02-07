
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'422', '2016-10-14 16:11:01', 'Arvind Thakur', 'get_conversion column added to unit table for new temperature sensor', '0'
);

ALTER TABLE unit
ADD COLUMN get_conversion tinyint(1) DEFAULT 0;

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 422;