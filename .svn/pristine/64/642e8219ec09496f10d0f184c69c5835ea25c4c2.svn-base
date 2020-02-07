-- Insert SQL here.



CREATE TABLE IF NOT EXISTS `groupman` (
  `gmid` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` int(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`gmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 17, NOW(), 'Ajay Tripathi','Group Manage Table');