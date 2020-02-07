-- Insert SQL here.

alter table `batch` add column last_fid int(11) default null;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 236, NOW(), 'Akhil VL','last insert id in batch table');
