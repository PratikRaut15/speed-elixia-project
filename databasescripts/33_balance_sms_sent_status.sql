-- Insert SQL here.

ALTER TABLE `customer`  ADD `sms_balance_alert` TINYINT(1) NOT NULL DEFAULT '0';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 33, NOW(), 'Ajay Tripathi','sms_balance_alert');
