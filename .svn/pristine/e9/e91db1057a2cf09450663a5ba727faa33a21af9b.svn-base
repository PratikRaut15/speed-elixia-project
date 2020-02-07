
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'458', '2016-02-09 12:12:00', 'Shrikant', 'Change temperature type to decimal from int', '0'
);


ALTER TABLE `vehicle` 
CHANGE `temp1_min` `temp1_min` DECIMAL(5,2) NOT NULL, 
CHANGE `temp1_max` `temp1_max` DECIMAL(5,2) NOT NULL, 
CHANGE `temp2_min` `temp2_min` DECIMAL(5,2) NOT NULL, 
CHANGE `temp2_max` `temp2_max` DECIMAL(5,2) NOT NULL, 
CHANGE `temp3_min` `temp3_min` DECIMAL(5,2) NOT NULL, 
CHANGE `temp3_max` `temp3_max` DECIMAL(5,2) NOT NULL, 
CHANGE `temp4_min` `temp4_min` DECIMAL(5,2) NOT NULL, 
CHANGE `temp4_max` `temp4_max` DECIMAL(5,2) NOT NULL;


UPDATE 	dbpatches 
SET 	patchdate = 'NOW()'
	, isapplied =1 
WHERE 	patchid = 458;
