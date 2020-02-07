-- Insert SQL here.
ALTER TABLE maintenance add tax int(11) NOT NULL AFTER invoice_amount;

ALTER TABLE maintenance_history add tax int(11) NOT NULL AFTER invoice_amount;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 173, NOW(), 'Shrikanth Suryawanshi','Tax Addition In Maintenance');
