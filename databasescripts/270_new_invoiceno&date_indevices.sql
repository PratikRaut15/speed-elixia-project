ALTER TABLE `devices`  ADD `device_invoiceno` VARCHAR(50) NOT NULL  AFTER `satv`;
ALTER TABLE `devices`  ADD `inv_generatedate` DATE NOT NULL  AFTER `device_invoiceno`;


 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (270, NOW(), 'Sahil','new invoice no.&date in devices table');
