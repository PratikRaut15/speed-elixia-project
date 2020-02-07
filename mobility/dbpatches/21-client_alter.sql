ALTER TABLE `client` ADD `first_visit` DATETIME NOT NULL AFTER `maincontact` ,
ADD `last_visit` DATETIME NOT NULL AFTER `first_visit`;

ALTER TABLE `client` ADD `has_visit` BOOL NOT NULL AFTER `iscall` ;


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 21, NOW(), 'Sanket Sheth','client alter');