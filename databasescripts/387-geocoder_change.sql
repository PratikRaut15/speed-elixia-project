INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'387', 'NOW()', 'Shrikant Suryawanshi', 'Geocoder Live Location', '0'
);

ALTER TABLE geotest add country varchar(255) NOT NULL after state;
ALTER TABLE geotest add created_on datetime NOT NULL after checkpointid;

ALTER TABLE `geotest` CHANGE `lat` `lat` DECIMAL(10,6) NOT NULL;
ALTER TABLE `geotest` CHANGE `long` `long` DECIMAL(10,6) NOT NULL;

create index index_customerno on geotest(customerno);

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 387;
