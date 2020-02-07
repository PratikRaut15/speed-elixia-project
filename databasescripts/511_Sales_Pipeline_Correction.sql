-- Insert SQL here.

ALTER TABLE `sales_pipeline` DROP `qtnno`, DROP `qtndate`, DROP `pono`, DROP `podate`, DROP `no_of_devices`, DROP `device_type`; 
ALTER TABLE `sales_pipeline_history` DROP `qtnno`, DROP `qtndate`, DROP `pono`, DROP `podate`, DROP `no_of_devices`, DROP `device_type`; 

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 511, NOW(), 'Sanket Sheth','Sales Pipeline Correction');
