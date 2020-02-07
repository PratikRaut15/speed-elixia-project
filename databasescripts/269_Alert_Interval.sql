-- Insert SQL here.

ALTER TABLE user add tempinterval varchar(10) NOT NULL AFTER towing_mail;
ALTER TABLE user add igninterval varchar(10) NOT NULL AFTER tempinterval;
ALTER TABLE user add speedinterval varchar(10) NOT NULL AFTER igninterval;
ALTER TABLE user add acinterval varchar(10) NOT NULL AFTER speedinterval;
ALTER TABLE user add doorinterval varchar(10) NOT NULL AFTER acinterval;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (269, NOW(), 'Shrikant Suryawanshi','Alert INterval');
