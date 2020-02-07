-- Insert SQL here.

ALTER TABLE `servicecall` ADD `iscard` BOOL NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 5, NOW(), 'Sanket Sheth','Is_Card');