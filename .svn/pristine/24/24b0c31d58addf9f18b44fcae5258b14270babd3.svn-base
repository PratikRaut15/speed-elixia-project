-- Insert SQL here.
ALTER TABLE `voucher` ADD `voucherdate` DATE NOT NULL AFTER `claimdate`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 197, NOW(), 'Ganesh Papde','alter voucher table');
