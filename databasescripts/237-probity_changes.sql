-- Insert SQL here.


update probity_master set workkey_name='Padgha - BR Road' , customerno=15, work_master='static_33.sqlite' where pmid=1;
UPDATE `speed`.`probity_master` SET `workkey_name` = 'Padgha - Arora Cinema', `work_master` = 'static_34.sqlite', `customerno` = '15' WHERE `probity_master`.`pmid` = 21;
UPDATE `speed`.`probity_master` SET `work_master` = 'static_35.sqlite' WHERE `probity_master`.`pmid` = 24;
UPDATE `speed`.`probity_master` SET `work_master` = 'static_36.sqlite' WHERE `probity_master`.`pmid` = 26;
UPDATE `speed`.`probity_master` SET `work_master` = 'static_37.sqlite' WHERE `probity_master`.`pmid` = 28;
UPDATE `speed`.`probity_master` SET `work_master` = 'static_38.sqlite' WHERE `probity_master`.`pmid` = 30;
UPDATE `speed`.`probity_master` SET `work_master` = 'static_39.sqlite' WHERE `probity_master`.`pmid` = 29;
UPDATE `speed`.`probity_master` SET `work_master` = 'static_41.sqlite' WHERE `probity_master`.`pmid` = 25;
UPDATE `speed`.`probity_master` SET `work_master` = 'static_42.sqlite' WHERE `probity_master`.`pmid` = 23;
delete from `speed`.`probity_master` where pmid in (10);




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 237, NOW(), 'Akhil VL','probity updates');
