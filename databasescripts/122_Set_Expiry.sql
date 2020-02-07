-- Insert SQL here.

CREATE TABLE `valert` (
  `valertid` int(11) NOT NULL AUTO_INCREMENT,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `puc_expiry` datetime NOT NULL,
  `puc_sms_email` tinyint(1) NOT NULL,
  `reg_expiry` datetime NOT NULL,
  `reg_sms_email` tinyint(1) NOT NULL,
  `insurance_expiry` datetime NOT NULL,
  `insurance_sms_email` tinyint(1) NOT NULL,
  `other1_expiry` datetime NOT NULL,
  `other1_sns_email` tinytext NOT NULL,
  `other2_expiry` datetime NOT NULL,
  `other2_sms_email` tinyint(1) NOT NULL,
  `other3_expiry` datetime NOT NULL,
  `other3_sms_email` tinyint(1) NOT NULL,
  PRIMARY KEY (`valertid`)
) ENGINE=MyISAM ;

ALTER TABLE `valert` ADD `timestamp` DATETIME NOT NULL AFTER `other3_sms_email` ;
ALTER TABLE `valert` CHANGE `other1_sns_email` `other1_sms_email` TINYTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 122, NOW(), 'Shrikanth Suryawanshi','Set Expity');
