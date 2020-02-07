-- Insert SQL here.

alter table master_orders add quantity varchar(25) NOT NULL AFTER production_details;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 15, NOW(), 'Shrikanth Suryawanshi','Add Quantity Column in Masters Orders');
