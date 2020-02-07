-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `stoppage_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `is_chk_sms` tinyint(1) NOT NULL,
  `is_trans_sms` tinyint(1) NOT NULL,
  `is_chk_email` tinyint(1) NOT NULL,
  `is_trans_email` tinyint(1) NOT NULL,
  `chkmins` int(11) NOT NULL,
  `transmins` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `checkpointmanage` ADD `timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE `stoppage_alerts` ADD `alert_sent` BOOL NOT NULL ;

ALTER TABLE `vehicle` ADD `stoppage_odometer` INT( 11 ) NOT NULL ;
UPDATE vehicle SET stoppage_odometer = odometer;

ALTER TABLE `vehicle` ADD `stoppage_transit_time` DATETIME NOT NULL ;

ALTER TABLE `comqueue` ADD `userid` INT( 11 ) NOT NULL ;

ALTER TABLE `stoppage_alerts` DROP `alert_sent` ;

ALTER TABLE `vehicle` ADD `alert_sent` TINYINT( 1 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 97, NOW(), 'Sanket Sheth','Stoppage_Alerts');
