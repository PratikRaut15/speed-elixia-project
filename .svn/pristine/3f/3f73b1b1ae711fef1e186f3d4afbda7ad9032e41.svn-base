-- Insert SQL here.

CREATE TABLE `smstrack` (`id` INT(11) NOT NULL AUTO_INCREMENT, `name` VARCHAR(50) NOT NULL, `customer_no` VARCHAR(50) NOT NULL, `phoneno` VARCHAR(50) NOT NULL, `uid` INT(11) NOT NULL, `isdeleted` TINYINT(1) NOT NULL, `timestamp` DATETIME NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 46, NOW(), 'Ajay Tripathi','Sms Track Table');
