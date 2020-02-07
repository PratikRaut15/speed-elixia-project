-- Insert SQL here.
ALTER TABLE trip_alert ADD mail_status tinyint(1) default 0;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 170, NOW(), 'Akhil','alert trip table');
