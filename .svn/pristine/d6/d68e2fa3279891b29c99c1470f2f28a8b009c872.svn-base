-- Insert SQL here.


alter table zone_master add column start_lat varchar(55) default "19.06" after zonename;
alter table zone_master add column start_long varchar(55) default "72.89" after start_lat;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 17, NOW(), 'Akhil VL','Add start lat-long column in zone_master');
