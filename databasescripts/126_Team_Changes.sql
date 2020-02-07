-- Insert SQL here.

INSERT INTO `trans_status` (
`id` ,
`status` ,
`type`
)
VALUES (
NULL , 'Bad-Allotted', '0'
);

INSERT INTO `trans_status` (
`id` ,
`status` ,
`type`
)
VALUES (
NULL , 'Bad-Allotted', '1'
);

ALTER TABLE `trans_status` CHANGE `id` `id` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 126, NOW(), 'Sanket Sheth','Team_Changes');
