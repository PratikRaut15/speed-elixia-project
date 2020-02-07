INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'396', '2016-06-20 16:00:01', 'Shrikant Suryawanshi', 'Allotment History', '0'
);

create Table allotment_history(
allothistid int PRIMARY KEY AUTO_INCREMENT,
assignerid int, 
receverid int, 
unitid int, 
simid int, 
stage varchar(50), 
comments varchar(250), 
insertedby int, 
insertedon datetime
);


-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 396;


