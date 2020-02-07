CREATE TABLE `dbpatches` (
  `patchid` int(11) NOT NULL,
  `patchdate` datetime NOT NULL,
  `appliedby` varchar(20) NOT NULL,
  `patchdesc` varchar(255) NOT NULL,
  PRIMARY KEY (`patchid`)
);

-- Insert SQL here.
ALTER TABLE `user` ADD `oauthuserid` VARCHAR(50) NOT NULL AFTER `password`;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 1, NOW(), 'Mrudang Vora','Additional Field for users registereing with Facebook');
