-- Insert SQL here.


INSERT INTO `speed`.`probity_master` ( `pmid` ,`workkey` ,`workkey_name` ,`work_master` ,`customerno`) VALUES ('44', '694', 'Powai-Jaishankar', 'static_64.sqlite', '21');

INSERT INTO `speed`.`probity_master` ( `pmid` ,`workkey` ,`workkey_name` ,`work_master` ,`customerno`) VALUES ('45', '1027', 'Andheri Kurla road', 'static_65.sqlite', '21');


INSERT INTO `speed`.`probity_master` ( `pmid` ,`workkey` ,`workkey_name` ,`work_master` ,`customerno`) VALUES ('46', '732', 'Powai - Aurora cinema', 'static_66.sqlite', '21');


INSERT INTO `speed`.`probity_master` ( `pmid` ,`workkey` ,`workkey_name` ,`work_master` ,`customerno`) VALUES ('47', '1034', 'Powai - Dp road', 'static_67.sqlite', '21');




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( '368', NOW(), 'Ganesh','probity master insert records');

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 368;

