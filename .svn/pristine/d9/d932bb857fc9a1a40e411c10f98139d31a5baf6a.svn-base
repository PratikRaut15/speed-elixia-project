-- Insert SQL here.

update geotest as a 
inner join checkpoint as b on b.cgeolat=a.lat and b.cgeolong=a.long and a.customerno=b.customerno 
set a.checkpointid=b.checkpointid
where a.lat!=0 and a.long!=0 and (a.checkpointid=0 or a.checkpointid is null) and b.isdeleted=0

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 148, NOW(), 'Akhil VL','update geotest table');
