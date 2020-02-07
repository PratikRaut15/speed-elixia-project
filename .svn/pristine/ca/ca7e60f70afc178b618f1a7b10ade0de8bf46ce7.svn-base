-- Insert SQL here.
INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied` ) 
 VALUES ( 398, NOW(), 'Sanket Sheth','Nomens Changed','0');


UPDATE `nomens` SET `name` = 'Vertical Chiller' WHERE `nomens`.`nid` =4;
UPDATE `nomens` SET `name` = 'Vertical Chiller Veg' WHERE `nomens`.`nid` =7;
UPDATE `nomens` SET `name` = 'Deep Freezer Non Veg' WHERE `nomens`.`nid` =5;
UPDATE `nomens` SET `name` = 'Deep Freezer Veg' WHERE `nomens`.`nid` =6;

-- Successful. Add the Patch to the Applied Patches table.


UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 398;
