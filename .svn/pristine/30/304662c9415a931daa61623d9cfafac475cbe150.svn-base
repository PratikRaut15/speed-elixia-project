-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `maintenance_history` (
  `hist_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `meter_reading` int(11) NOT NULL,
  `dealerid` int(11) NOT NULL,
  `notes` varchar(250) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`hist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 80, NOW(), 'Ajay Tripathi','maintenance table');
