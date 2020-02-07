-- Insert SQL here.

ALTER TABLE `trackee` ADD `pushform` BOOL NOT NULL AFTER `pushservicelist` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 19, NOW(), 'Sanket Sheth','Push Form');