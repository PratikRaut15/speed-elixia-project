INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('513', '2017-06-19 16:56:00','Arvind Thakur','Sales Pipeline Correction', '0');

-- Insert SQL here.

ALTER TABLE `sales_pipeline` 
DROP `qtnno`, 
DROP `qtndate`, 
DROP `pono`, 
DROP `podate`, 
DROP `no_of_devices`, 
DROP `device_type`; 

ALTER TABLE `sales_pipeline_history` 
DROP `qtnno`, 
DROP `qtndate`, 
DROP `pono`, 
DROP `podate`, 
DROP `no_of_devices`, 
DROP `device_type`; 

ALTER TABLE `sales_pipeline` 
ADD COLUMN `loss_reason` VARCHAR(150) AFTER `stageid`;

ALTER TABLE `sales_pipeline_history` 
ADD COLUMN `loss_reason` VARCHAR(150) AFTER `stageid`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 513;

