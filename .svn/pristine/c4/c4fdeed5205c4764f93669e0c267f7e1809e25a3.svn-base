CREATE TABLE IF NOT EXISTS `maintenance_tyre_repair_type` (
`tyrerepairid` int(11) NOT NULL,
  `repairtype` varchar(80) NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdno` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

INSERT INTO `maintenance_tyre_repair_type` (`tyrerepairid`, `repairtype`, `customerno`, `createdby`, `createdno`, `updatedby`, `updatedon`, `isdeleted`) VALUES
(1, 'New', 0, 1022, '2015-12-09 00:00:00', 1022, '2015-12-09 00:00:00', 0),
(2, 'Remoulding', 0, 1022, '2015-12-09 00:00:00', 1022, '2015-12-05 00:00:00', 0),
(3, 'Repair/Puncture', 0, 1022, '2015-12-05 00:00:00', 1022, '2015-12-05 00:00:00', 0);

ALTER TABLE `maintenance_tyre_repair_type`
 ADD PRIMARY KEY (`tyrerepairid`);

ALTER TABLE `maintenance_tyre_repair_type`
MODIFY `tyrerepairid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

CREATE TABLE IF NOT EXISTS `maintenance_tyre_repair_mapping` (
`tyrerepair_mapid` int(11) NOT NULL,
  `tyrerepairid` int(11) NOT NULL,
  `maintenanceid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `maintenance_tyre_repair_mapping`
 ADD PRIMARY KEY (`tyrerepair_mapid`);

ALTER TABLE `maintenance_tyre_repair_mapping`
MODIFY `tyrerepair_mapid` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (334, NOW(), 'Sahil','new tables for tyres in maintenance');



