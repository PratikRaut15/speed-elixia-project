-- Insert SQL here.
alter table unit add column doorsensor tinyint(1) default 0 after is_ac_opp;
alter table unit add column is_door_opp tinyint(1) default 0 after doorsensor;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 177, NOW(), 'Akhil','alter unit table');
