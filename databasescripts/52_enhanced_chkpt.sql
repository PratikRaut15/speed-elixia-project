-- Insert SQL here.

CREATE TABLE `enh_checkpoint` (`enh_checkpoint` INT(11) NOT NULL AUTO_INCREMENT, `checkpointid` INT(11) NOT NULL, `vehicleid` INT(11) NOT NULL, `com_details` VARCHAR(100) NOT NULL, `com_type` TINYINT(1) NOT NULL, `userid` INT(11) NOT NULL, `customerno` INT(11) NOT NULL, `timestamp` DATETIME NOT NULL, `isdeleted` TINYINT(1) NOT NULL, PRIMARY KEY (`enh_checkpoint`)) ENGINE = MyISAM;

ALTER TABLE  `comhistory` ADD  `enh_checkpointid` INT( 11 ) NOT NULL AFTER  `comtype`;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 52, NOW(), 'Ajay Tripathi','enhanced checkpt');
