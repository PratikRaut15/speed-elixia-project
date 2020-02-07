
RENAME TABLE `freeze` TO `freezelog`;
ALTER TABLE `freezelog` CHANGE `is_android` `is_api` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `immobiliserlog` CHANGE `is_android` `is_api` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `buzzerlog` CHANGE `is_android` `is_api` TINYINT(1) NOT NULL DEFAULT '0';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (312, NOW(), 'ganesh','altertables');

