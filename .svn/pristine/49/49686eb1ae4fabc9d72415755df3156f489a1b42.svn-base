-- Insert SQL here.

ALTER TABLE `trans_history` ADD `vehicleid` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 289, NOW(), 'Sanket Sheth','Vehicle ID In Trans History');
