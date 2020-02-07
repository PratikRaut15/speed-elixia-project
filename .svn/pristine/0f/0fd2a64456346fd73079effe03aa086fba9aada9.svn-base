-- Insert SQL here.

CREATE TABLE `bucket` ( `bucketid` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `apt_date` DATE NOT NULL , `customerno` INT(11) NOT NULL , `created_by` INT(11) NOT NULL , `priority` INT(4) NOT NULL , `vehicleid` INT(11) NOT NULL , `location` VARCHAR(50) NOT NULL , `timeslotid` INT(4) NOT NULL , `purposeid` INT(4) NOT NULL , `details` VARCHAR(100) NOT NULL , `coordinatorid` INT(11) NOT NULL , `fe_id` INT(11) NOT NULL , `is_rescheduled` BOOLEAN NOT NULL , `reschedule_date` DATE NOT NULL , `unitid` INT(11) NOT NULL , `simcardid` INT(11) NOT NULL , `status` INT(4) NOT NULL , `is_compliance` INT(4) NOT NULL , `is_problem_of` INT(4) NOT NULL , `reasonid` INT(11) NOT NULL , `remarks` VARCHAR(100) NOT NULL , `create_timestamp` DATETIME NOT NULL , `reschedule_timestamp` DATETIME NOT NULL , `task_completion_timestamp` DATETIME NOT NULL , `fe_assigned_timestamp` DATETIME NOT NULL , `cancelled_timestamp` DATETIME NOT NULL) ENGINE = InnoDB; 

CREATE TABLE `nc_reason` ( `reasonid` INT(11) NOT NULL AUTO_INCREMENT , `reason` VARCHAR(100) NOT NULL , PRIMARY KEY (`reasonid`)) ENGINE = InnoDB;

INSERT INTO `nc_reason` (`reasonid`, `reason`) VALUES (NULL, 'Vehicle not available'), (NULL, 'Field Engineer was late');
INSERT INTO `nc_reason` (`reasonid`, `reason`) VALUES (NULL, 'Driver rejected duty'), (NULL, 'Vehicle Battery not connected');
INSERT INTO `nc_reason` (`reasonid`, `reason`) VALUES (NULL, 'Material not available with Field Engineer'), (NULL, 'Previous job not complete');

ALTER TABLE `bucket` ADD `cancellation_reason` VARCHAR(50) NOT NULL AFTER `cancelled_timestamp`; 
ALTER TABLE `bucket` ADD `vehicleno` VARCHAR(10) NOT NULL AFTER `cancellation_reason`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 394, NOW(), 'Sanket Sheth','Bucket');
