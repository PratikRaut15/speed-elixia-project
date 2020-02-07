-- Insert SQL here.

-- Successful. Add the Patch to the Applied Patches table.

CREATE TABLE IF NOT EXISTS `apicalls` (
  `callid` int(11) NOT NULL AUTO_INCREMENT,
  `payload` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`callid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;


 INSERT INTO `dbpatches` (  `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (  NOW(), 'Chirag Jain','Some Fancy Patch');
