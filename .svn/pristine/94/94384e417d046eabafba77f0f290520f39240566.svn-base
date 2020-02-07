-- Successful. Add the Patch to the Applied Patches table.

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_tyretype`
--

CREATE TABLE IF NOT EXISTS `maintenance_tyretype` (
`tyreid` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
)

ALTER TABLE `maintenance_tyretype`
 ADD PRIMARY KEY (`tyreid`);

INSERT INTO `maintenance_tyretype` (`tyreid`, `type`) VALUES
(1, 'Right Front'),
(2, 'Right Back'),
(3, 'Left Front'),
(4, 'Left Back'),
(5, 'Stepney');

ALTER TABLE `maintenance_tyretype`
MODIFY `tyreid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;


-- --------------------------------------------------------

--
-- Table structure for table `maintenance_maptyre`
--

CREATE TABLE IF NOT EXISTS `maintenance_maptyre` (
`tyremapid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `tyreid` int(11) NOT NULL,
  `serialno` varchar(15) NOT NULL
) 


ALTER TABLE `maintenance_maptyre`
 ADD PRIMARY KEY (`tyremapid`);


--
-- AUTO_INCREMENT for table `maintenance_maptyre`
--
ALTER TABLE `maintenance_maptyre`
MODIFY `tyremapid` int(11) NOT NULL AUTO_INCREMENT,


-- --------------------------------------------------------

--
-- Table structure for table `maintenance_mapbattery`
--

CREATE TABLE IF NOT EXISTS `maintenance_mapbattery` (
`batt_mapid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `batt_serialno` varchar(15) NOT NULL
)


ALTER TABLE `maintenance_mapbattery`
 ADD PRIMARY KEY (`batt_mapid`);



--
-- AUTO_INCREMENT for table `maintenance_mapbattery`
--
ALTER TABLE `maintenance_mapbattery`
MODIFY `batt_mapid` int(11) NOT NULL AUTO_INCREMENT

-- Successful. Add the Patch to the Applied Patches table.
 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 291, NOW(), 'Sahil Gandhi','new_tables_maintenance');
