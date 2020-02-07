-- Insert SQL here.

ALTER TABLE `maintenance` ADD `timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `vehicleid` ,
ADD `userid` INT NOT NULL AFTER `timestamp` ,
ADD `customerno` INT NOT NULL AFTER `userid` ;

ALTER TABLE `maintenance` ADD `roleid` INT NOT NULL AFTER `customerno`;

ALTER TABLE `capitalization` ADD `userid` INT NOT NULL AFTER `vehicleid` ,
ADD `timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `userid` ,
ADD `customerno` INT NOT NULL AFTER `timestamp` ;

DROP TABLE `maintenance_history` ;



CREATE TABLE IF NOT EXISTS `maintenance_history` (
  `hist_id` int(11) NOT NULL AUTO_INCREMENT,
  `maintananceid` int(11) NOT NULL,
  `maintenance_date` date NOT NULL,
  `meter_reading` int(10) NOT NULL,
  `vehicle_in_date` date NOT NULL,
  `vehicle_out_date` date NOT NULL,
  `dealer_id` int(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_no` varchar(250) NOT NULL,
  `invoice_amount` varchar(250) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `notes` varchar(250) NOT NULL,
  `amount_quote` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `tyre_type` tinyint(1) NOT NULL,
  `category` tinyint(1) NOT NULL,
  `statusid` tinyint(1) NOT NULL,
  PRIMARY KEY (`hist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;





-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 87, NOW(), 'vishwanath','alter maintanance capitalisation');
