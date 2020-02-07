-- Insert SQL here.

ALTER TABLE unit add temp1_intv datetime NOT NULL after type_value;
ALTER TABLE unit add temp2_intv datetime NOT NULL after temp1_intv;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (271, NOW(), 'Shrikant Suryawanshi','Alter Unit Table');
