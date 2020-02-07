-- Insert SQL here.


ALTER TABLE `unit` CHANGE `remark` `remark` INT( 4 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 161, NOW(), 'Shrikanth Suryawanshi','Remark Alter');
