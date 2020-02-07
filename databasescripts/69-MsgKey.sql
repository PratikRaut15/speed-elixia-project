-- Insert SQL here.
ALTER TABLE `customer` ADD `use_msgkey` BOOL NOT NULL ;
UPDATE `customer` SET `use_msgkey` = '1' WHERE `customer`.`customerno` =15;

ALTER TABLE `customer` ADD `use_geolocation` BOOL NOT NULL ;
UPDATE `customer` SET `use_geolocation` = '1' WHERE `customer`.`customerno` =33;
UPDATE `customer` SET `use_geolocation` = '1' WHERE `customer`.`customerno` =43;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 69, NOW(), 'Sanket Sheth','MsgKey');
