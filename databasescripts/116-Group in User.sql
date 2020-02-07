-- Insert SQL here.

ALTER TABLE `user` CHANGE `groupid` `groupid` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 116, NOW(), 'Sanket Sheth','Group in User');
