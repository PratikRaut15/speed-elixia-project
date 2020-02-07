ALTER TABLE `payment` ADD `reason` INT NOT NULL AFTER `branch` ,
ADD `is_web` INT NOT NULL AFTER `reason` ;

ALTER TABLE `payment` CHANGE `reason` `reason` TEXT NOT NULL ;


 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 28, NOW(), 'vishu','payment alterations');