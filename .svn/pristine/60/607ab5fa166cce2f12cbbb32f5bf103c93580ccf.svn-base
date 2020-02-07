-- Insert SQL here.

INSERT INTO `maintenance_status` (
`id` ,
`name`
)
VALUES (
NULL , 'History'
);

UPDATE `maintenance_status` SET `name` = 'Complete (History)' WHERE `maintenance_status`.`id` =6;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 93, NOW(), 'Sanket Sheth','History_Status_ID');
