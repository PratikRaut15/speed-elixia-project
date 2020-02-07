
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'457', '2016-02-08 12:12:00', 'Ganesh', 'sales flow table alter', '0'
);


ALTER TABLE `sales_pipeline` ADD `device_cost` DECIMAL(11,2) NOT NULL AFTER `qtndate`, ADD `subscription_cost` DECIMAL(11,2) NOT NULL AFTER `device_cost`;
ALTER TABLE `sales_pipeline_history` ADD `device_cost` DECIMAL(11,2) NOT NULL AFTER `qtndate`, ADD `subscription_cost` DECIMAL(11,2) NOT NULL AFTER `device_cost`;


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 457;
