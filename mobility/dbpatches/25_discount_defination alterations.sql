ALTER TABLE `discount` ADD `defination` TEXT NOT NULL AFTER `ispercent`;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 25, NOW(), 'vishu','discount defination');