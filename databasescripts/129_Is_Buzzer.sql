-- Insert SQL here.

ALTER TABLE `customer` ADD `use_buzzer` TINYINT NOT NULL DEFAULT '0' AFTER `use_hierarchy` ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 129, NOW(), 'Shrikanth Suryawanshi','Is Buzzer');
