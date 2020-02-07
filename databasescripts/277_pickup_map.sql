-- Insert SQL here.
CREATE TABLE IF NOT EXISTS `pickup_map` (
`mapid` int(11) NOT NULL,
  `customerno` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `entrytime` datetime DEFAULT NULL,
  `mapdate` date DEFAULT NULL,
  `pickupboy` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  `slotid` int(5) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `pickup_map`
 ADD PRIMARY KEY (`mapid`);

ALTER TABLE `pickup_map`
MODIFY `mapid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 277, NOW(), 'Shrikant','Pickup map For pickup module');
