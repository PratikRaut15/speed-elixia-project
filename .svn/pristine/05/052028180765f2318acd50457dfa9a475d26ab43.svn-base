-- Insert SQL here.

ALTER TABLE `dailyreport` ADD `daily_date` DATE NOT NULL AFTER `weekend_first_odometer`; 
ALTER TABLE `temp_compliance` ADD `mute_count_1` INT( 11 ) NOT NULL AFTER `max_range_1` ;
ALTER TABLE `temp_compliance` ADD `mute_count_2` INT( 11 ) NOT NULL AFTER `max_range_2` ;
ALTER TABLE `temp_compliance` ADD `mute_count_3` INT( 11 ) NOT NULL AFTER `max_range_3` ;
ALTER TABLE `temp_compliance` ADD `mute_count_4` INT( 11 ) NOT NULL AFTER `max_range_4` ;
ALTER TABLE `temp_compliance` CHANGE `mute_count_1` `mute_count_1` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `temp_compliance` CHANGE `mute_count_2` `mute_count_2` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `temp_compliance` CHANGE `mute_count_3` `mute_count_3` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `temp_compliance` CHANGE `mute_count_4` `mute_count_4` INT( 11 ) NOT NULL DEFAULT '0';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 368, NOW(), 'Sanket Sheth','Daily Date');
