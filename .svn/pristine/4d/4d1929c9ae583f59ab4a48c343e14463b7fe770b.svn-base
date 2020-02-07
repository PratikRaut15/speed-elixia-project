-- Insert SQL here.

ALTER TABLE `unit` ADD `issue_type` TINYINT(1) NOT NULL DEFAULT '0' AFTER `alterremark`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 200, NOW(), 'Ganesh','inactive issue types-customer/elixia');
