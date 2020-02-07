-- Insert SQL here.

ALTER TABLE `voucher_payment` ADD `timestamp` DATETIME NOT NULL AFTER `done_by`;
ALTER TABLE `voucher_payment` ADD `advpaid` TINYINT(1) NOT NULL DEFAULT '0' AFTER `timestamp`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 196, NOW(), 'Ganesh Papde','alter payment voucher table');
