-- Insert SQL here.

alter table ignitionalert add column ignontime datetime;
ALTER TABLE `ignitionalert` CHANGE `ignontime` `ignontime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 195, NOW(), 'Akhil VL','ignition-on time in ignition alert');
