-- Insert SQL here.

ALTER TABLE `checklist_active_data` ADD `sqlite_timestamp` DATETIME NOT NULL ;
ALTER TABLE `payment` ADD `sqlite_timestamp` DATETIME NOT NULL ;
ALTER TABLE `servicemanage` ADD `sqlite_timestamp` DATETIME NOT NULL ;
ALTER TABLE `feedback_result` ADD `sqlite_timestamp` DATETIME NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 30, NOW(), 'Sanket Sheth','SQLITE Timestamp');