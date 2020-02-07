-- Insert SQL here.

CREATE TABLE `alerthistory` (`id` INT(11) NOT NULL AUTO_INCREMENT, `statusid` INT(11) NOT NULL, `statustype` TINYINT(1) NOT NULL, `message` TEXT NOT NULL, `vehicleid` INT(11) NOT NULL, `checkpointid` INT(11) NOT NULL, `fenceid` INT(11) NOT NULL, `customerno` INT(11) NOT NULL, `timestamp` DATETIME NOT NULL,
  PRIMARY KEY (`id`)) ENGINE = MyISAM;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 31, NOW(), 'Ajay Tripathi','Alert History');