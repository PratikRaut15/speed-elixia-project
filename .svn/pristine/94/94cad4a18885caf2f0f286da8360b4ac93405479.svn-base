
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'412', '2016-09-20 15:12:01', 'Mrudang Vora', 'Remove duplicate fence lat long for each customer', '0');

ALTER TABLE geofence ENGINE MyISAM;
ALTER IGNORE TABLE geofence
ADD UNIQUE INDEX ux_latlong (geolat,geolong,customerno);
ALTER TABLE geofence ENGINE InnoDB;


-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied = 1 
WHERE 	patchid = 412;
