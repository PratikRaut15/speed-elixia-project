-- Insert SQL here.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'372', '2016-03-08 11:38:30', 'Ganesh Papade', 'Maintenance-Trigger For Battary/tyre', '0'
);

DELIMITER $$
DROP TRIGGER IF EXISTS before_maintenance_mapbattery_update $$
CREATE TRIGGER `before_maintenance_mapbattery_update` BEFORE UPDATE ON `maintenance_mapbattery`
FOR EACH ROW BEGIN
IF (NEW.`batt_serialno` <> OLD.`batt_serialno`) THEN
BEGIN
INSERT INTO maintenance_mapbattery_history
SET 
batt_mapid = OLD.batt_mapid
, vehicleid = OLD.vehicleid
, customerno = OLD.customerno
, batt_serialno = OLD.batt_serialno
, installedon = OLD.installedon
, createdby = OLD.createdby
, createdon = OLD.createdon
, updatedby = OLD.updatedby
,updatedon = OLD.updatedon
, isdeleted = OLD.isdeleted
, entrytime = DATE_ADD(NOW(), INTERVAL '5:30' HOUR_MINUTE);
END;
END IF;
END $$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `maintenance_mapbattery_history` (
`batthistid` int(11) primary key auto_increment,
`batt_mapid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `batt_serialno` varchar(15) NOT NULL,
  `installedon` date NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `entrytime` datetime NOT NULL
) 

DELIMITER $$
DROP TRIGGER IF EXISTS before_maintenance_maptyre_update $$
CREATE TRIGGER `before_maintenance_maptyre_update` BEFORE UPDATE ON `maintenance_maptyre`
FOR EACH ROW BEGIN
IF (NEW.`serialno` <> OLD.`serialno`) THEN
BEGIN
INSERT INTO maintenance_maptyre_history
SET 
tyremapid = OLD.tyremapid
, vehicleid = OLD.vehicleid
, customerno = OLD.customerno
, tyreid = OLD.tyreid
, serialno = OLD.serialno
, installedon = OLD.installedon
, createdby = OLD.createdby
, createdon = OLD.createdon
, updatedby = OLD.updatedby
,updatedon = OLD.updatedon
, isdeleted = OLD.isdeleted
, entrytime = DATE_ADD(NOW(), INTERVAL '5:30' HOUR_MINUTE);
END;
END IF;
END $$
DELIMITER ;


CREATE TABLE IF NOT EXISTS `maintenance_maptyre_history` (
`tyrehistid` int(11) primary key auto_increment,
  `tyremapid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `tyreid` int(11) NOT NULL,
  `serialno` varchar(15) NOT NULL,
  `installedon` date NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `entrytime` datetime NOT NULL
);


-- Successful. Add the Patch to the Applied Patches table.

UPDATE     dbpatches 
SET     patchdate = NOW()
        , isapplied =1 
WHERE     patchid = 372;

