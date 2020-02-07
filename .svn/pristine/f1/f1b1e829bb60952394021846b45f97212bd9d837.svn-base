-- Insert SQL here.

ALTER TABLE `unit` DROP `digitalio_sen` ;
ALTER TABLE `devices` DROP `phone` ;
ALTER TABLE `checkpoint` DROP `vehicleid` ;
ALTER TABLE `vehicle` CHANGE `kind` `kind` VARCHAR( 11 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Car';
ALTER TABLE `driver` CHANGE `driverphone` `driverphone` VARCHAR( 11 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '8888888888';
INSERT INTO `vendor` (`id`, `vendorname`) VALUES (NULL, 'Vodafone');
ALTER TABLE `unit` ADD `teamid` INT( 11 ) NOT NULL ;
ALTER TABLE `simcard` ADD `teamid` INT( 11 ) NOT NULL ;
INSERT INTO `trans_status` (
`id` ,
`status` ,
`type`
)
VALUES (
18 , 'Allotted to Team', '0'
), (
19 , 'Allotted to Team', '1'
);

ALTER TABLE `trans_history` ADD `allot_teamid` INT( 11 ) NOT NULL ;

ALTER TABLE `customer` ADD `use_heirarchy` TINYINT( 1 ) NOT NULL ;

ALTER TABLE `customer` CHANGE `use_heirarchy` `use_hierarchy` TINYINT( 1 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 119, NOW(), 'Sanket Sheth','Refine DB');
