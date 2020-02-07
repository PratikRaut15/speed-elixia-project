-- Insert SQL here.

ALTER TABLE tax add emailalert VARCHAR(25) NOT NULL AFTER customerno;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 199, NOW(), 'Shrikant Suryawanshi','email alert in tax');
