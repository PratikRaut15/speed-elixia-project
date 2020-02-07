-- Insert SQL here.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'371', '2016-03-08 11:38:30', 'Shrikant Suryawanshi', 'User-Vehicle Specific custom role', '0'
);



Create Table vehicleusermapping(
vehmapid int NOT NULL PRIMARY KEY AUTO_INCREMENT,
vehicleid int NOT NULL,
groupis int NOT NULL,
userid int NOT NULL,
customerno int NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
created_by int NOT NULL,
updated_by int NOT NULL,
isdeleted tinyint DEFAULT '0'
);


INSERT INTO `speed`.`role` (
`id` ,
`role` ,
`parentroleid` ,
`moduleid` ,
`customerno` ,
`created_by` ,
`updated_by` ,
`created_on` ,
`updated_on` ,
`isdeleted` ,
`sequenceno`
)
VALUES (
NULL , 'Custom', '0', '0', '0', '0', '0', '', '', '0', ''
);

[ Edit ] [ Create PHP Code ]



-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 371;

