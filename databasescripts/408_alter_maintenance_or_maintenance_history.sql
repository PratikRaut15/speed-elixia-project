-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'408', '2016-09-03 17:05:01', 'Ganesh Papde', 'alter tyre - parts table in decimal', '0');


-- Insert SQL here.


ALTER TABLE `maintenance_parts` CHANGE `qty` `qty` DECIMAL(6,2) NOT NULL;
ALTER TABLE `maintenance_tasks` CHANGE `qty` `qty` DECIMAL(6,2) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 408;
