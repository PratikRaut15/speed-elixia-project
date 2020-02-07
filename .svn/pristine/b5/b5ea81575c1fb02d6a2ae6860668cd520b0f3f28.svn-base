-- Insert SQL here.

ALTER TABLE `client` ADD `is_member` TINYINT(1) NOT NULL DEFAULT '0' , 
ADD `membership_code` VARCHAR(50) default NULL , 
ADD `amount` float default NULL , 
ADD `member_validity` DATE default NULL ;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 17, NOW(), 'ganesh','Client Membership columns added');



