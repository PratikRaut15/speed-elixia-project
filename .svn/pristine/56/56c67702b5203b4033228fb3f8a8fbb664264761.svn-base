-- Insert SQL here.

ALTER TABLE `elixiacode` ADD `ecodeemail` VARCHAR( 50 ) NOT NULL AFTER `userid` ,
ADD `ecodesms` INT( 15 ) NOT NULL AFTER `ecodeemail` ;

ALTER TABLE `elixiacode` CHANGE `ecodesms` `ecodesms` VARCHAR( 15 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 124, NOW(), 'Shrikanth Suryawanshi','View Client Code');
