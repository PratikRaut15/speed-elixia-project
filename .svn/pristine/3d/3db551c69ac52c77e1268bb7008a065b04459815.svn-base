-- Insert SQL here.

ALTER TABLE `master_orders` ADD INDEX (`delivery_date`);

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 10, NOW(), 'Akhil VL','indexed delivery_date');
