-- Insert SQL here.



CREATE TABLE IF NOT EXISTS `routeman` (
  `rmid` int(11) NOT NULL AUTO_INCREMENT,
  `routeid` int(11) NOT NULL,
  `checkpointid` int(11) NOT NULL,
  `sequence` int(3) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` int(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`rmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 12, NOW(), 'Ajay Tripathi','Route Manage Table');