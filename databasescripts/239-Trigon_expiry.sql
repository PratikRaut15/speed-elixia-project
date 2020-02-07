-- Insert SQL here.


Update valert SET other1_expiry='0000-00-00 00:00:00' where other1_expiry='1900-01-00 00:00:00';
Update valert SET other2_expiry='0000-00-00 00:00:00' where other2_expiry='1900-01-00 00:00:00';
Update valert SET other3_expiry='0000-00-00 00:00:00' where other3_expiry='1900-01-00 00:00:00';
Update valert SET puc_expiry='0000-00-00 00:00:00' where puc_expiry='1900-01-00 00:00:00';
Update valert SET insurance_expiry='0000-00-00 00:00:00' where insurance_expiry='1900-01-00 00:00:00';
Update valert SET reg_expiry='0000-00-00 00:00:00' where reg_expiry='1900-01-00 00:00:00';




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 239, NOW(), 'Shrikant Suryawanshi','Trigon Expiry');
