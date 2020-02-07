ALTER TABLE `orderreturnhistory` CHANGE `orderreturnid` `orderid` INT(11) NULL DEFAULT NULL;

ALTER TABLE `user` ADD `gcmid` TEXT NOT NULL AFTER `device_id`;

ALTER TABLE `orderrequest` ADD `AWBno` VARCHAR(15) NOT NULL AFTER `trackingstatusid`;

ALTER TABLE `orderreturn` ADD `AWBno` VARCHAR(15) NOT NULL AFTER `trackingstatusid`;

ALTER TABLE `orderrequesthistory` ADD `AWBno` VARCHAR(15) NOT NULL AFTER `trackingstatusid`;

ALTER TABLE `orderreturnhistory` ADD `AWBno` VARCHAR(15) NOT NULL AFTER `trackingstatusid`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (8, NOW(), 'Shrikant Suryawanshi','OrderReturnHistoryChange');
