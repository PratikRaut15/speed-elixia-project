
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'410', '2016-09-09 15:00:01', 'Mrudang Vora', 'Manual update of speed & temp limits for 116 & 167', '0');

Update vehicle SET overspeed_limit = 60, temp1_min = 15, temp1_max = 24, temp2_min = 15, temp2_max = 24 where customerno  = 116 and isdeleted = 0;
Update vehicle SET overspeed_limit = 70 where customerno  = 167 and isdeleted = 0;


-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 410;
