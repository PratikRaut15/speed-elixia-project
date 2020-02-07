-- Insert SQL here.

CREATE TABLE  `maintenance_status` (
`id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 225 ) NOT NULL
) ENGINE = MYISAM ;

INSERT INTO  `maintenance_status` (
`id` ,
`name`
)
VALUES (
NULL ,  'Sent For Approval'
), (
NULL ,  'Approved'
), (
NULL ,  'Rejected'
), (
NULL ,  'Complete'
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 74, NOW(), 'Ajay Tripathi','status table');
