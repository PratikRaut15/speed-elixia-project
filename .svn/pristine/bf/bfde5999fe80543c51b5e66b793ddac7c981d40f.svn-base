-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `route` (
  `routeid` int(11) NOT NULL AUTO_INCREMENT,
  `routename` varchar(100) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`routeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 11, NOW(), 'Ajay Tripathi','Route Table');