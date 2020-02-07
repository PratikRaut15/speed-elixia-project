-- Insert SQL here.
CREATE TABLE IF NOT EXISTS `android_version` (`version` varchar(100) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `android_version` (`version`)VALUES('V1');

UPDATE `customfield` SET `name`='digital';


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 54, NOW(), 'Ajay Tripathi','version table');
