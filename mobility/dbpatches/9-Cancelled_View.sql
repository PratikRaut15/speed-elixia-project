-- Insert SQL here.

ALTER TABLE `servicecall` ADD `cancelled_view` BOOL NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 9, NOW(), 'Sanket Sheth','Cancelled_View');