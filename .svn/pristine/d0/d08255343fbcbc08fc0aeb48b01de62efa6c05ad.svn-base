-- Insert SQL here.

CREATE TABLE  `role` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`role` VARCHAR( 225 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;

INSERT INTO  `role` (
`id` ,
`role`
)
VALUES (
NULL ,  'Master'
), (
NULL ,  'State Head'
), (
NULL ,  'District Head'
), (
NULL ,  'City Head'
), (
NULL ,  'Administrator'
), (
NULL ,  'Elixir'
), (
NULL ,  'Tracker'
), (
NULL ,  'Branch Head'
);

ALTER TABLE  `user` ADD  `roleid` INT( 11 ) NOT NULL AFTER  `role`;
ALTER TABLE  `user` ADD  `active_branchid` INT( 11 ) NOT NULL AFTER  `groupid`;
ALTER TABLE  `vehicle` ADD  `branchid` INT( 11 ) NOT NULL AFTER  `groupid`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 62, NOW(), 'Ajay Tripathi','Role Table');
