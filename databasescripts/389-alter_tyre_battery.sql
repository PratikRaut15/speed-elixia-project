
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'389', 'NOW()', 'ganesh', 'Alter change tyre or battery', '0'
);


ALTER TABLE `maintenance_mapbattery` CHANGE `batt_serialno` `batt_serialno` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `maintenance_mapbattery_history` CHANGE `batt_serialno` `batt_serialno` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `maintenance_maptyre` CHANGE `serialno` `serialno` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `maintenance_maptyre_history` CHANGE `serialno` `serialno` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 389;
