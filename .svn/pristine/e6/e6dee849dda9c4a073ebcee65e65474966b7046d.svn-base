

-- Insert SQL here.

CREATE TABLE `batch` (
`batchid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`vehicleid` VARCHAR( 15 ) NOT NULL ,
`customerno` VARCHAR( 11 ) NOT NULL ,
`batchno` VARCHAR( 20 ) NOT NULL ,
`workkey` VARCHAR( 10 ) NOT NULL ,
`addedon` DATETIME NOT NULL ,
`updatedon` DATETIME NOT NULL ,
`isdeleted` TINYINT( 1 ) NOT NULL
) ENGINE = MYISAM ;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 134, NOW(), 'Shrikanth Suryawanshi','Batch Number');