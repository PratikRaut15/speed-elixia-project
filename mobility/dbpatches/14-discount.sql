ALTER TABLE `servicecall` ADD `dis_id` INT NOT NULL ;

ALTER TABLE `servicecall` ADD `is_web` INT NOT NULL ;

CREATE TABLE IF NOT EXISTS `discount` (
  `dis_id` int(11) NOT NULL AUTO_INCREMENT,
  `dis_amt` int(11) NOT NULL,
  `dis_code` text NOT NULL,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `is_mass` int(11) NOT NULL,
  `dis_category` int(11) NOT NULL,
  `branchid` varchar(255) NOT NULL,
  `clientid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expiry` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`dis_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `discount_type` (
  `dtype_id` int(11) NOT NULL AUTO_INCREMENT,
  `dt_name` varchar(255) NOT NULL,
  `customerno` int(11) NOT NULL,
  PRIMARY KEY (`dtype_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `discount_type`
--
 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 14, NOW(), 'vishwanath','discount');