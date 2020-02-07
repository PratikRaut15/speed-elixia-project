/* new tables */
DELIMITER $$
DROP TABLE IF EXISTS `maintenance_mapbattery`;
CREATE TABLE IF NOT EXISTS `maintenance_mapbattery` (
`batt_mapid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `batt_serialno` varchar(15) NOT NULL,
  `installedon` date NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `maintenance_mapbattery`
 ADD PRIMARY KEY (`batt_mapid`);

ALTER TABLE `maintenance_mapbattery`
MODIFY `batt_mapid` int(11) NOT NULL AUTO_INCREMENT;
DELIMITER ;

DELIMITER $$
DROP TABLE IF EXISTS `maintenance_maptyre`;
CREATE TABLE IF NOT EXISTS `maintenance_maptyre` (
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
  `isdeleted` tinyint(1) NOT NULL
);


ALTER TABLE `maintenance_maptyre`
 ADD PRIMARY KEY (`tyremapid`);

ALTER TABLE `maintenance_maptyre`
MODIFY `tyremapid` int(11) NOT NULL AUTO_INCREMENT;

DELIMITER ;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (343, NOW(), 'Sahil','new maintenance table for tyre battery serialno');
