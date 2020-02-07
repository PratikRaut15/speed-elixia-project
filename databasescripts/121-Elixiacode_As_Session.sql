-- Insert SQL here.
ALTER TABLE `elixiacode` ADD `startdate` DATETIME NOT NULL AFTER `datecreated`;
ALTER TABLE `elixiacode` ADD `menuoption` INT( 11 ) NOT NULL AFTER `userid` ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 121, NOW(), 'Shrikanth Suryawanshi','Elixiacode As Session');
