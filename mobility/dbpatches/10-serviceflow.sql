-- Insert SQL here.

UPDATE `serviceflow` SET `name` = 'CHANGED' WHERE `serviceflow`.`serviceflowid` =8 AND `serviceflow`.`name` = 'CHANGING' LIMIT 1 ;

ALTER TABLE `communicationhistory` CHANGE `message` `message` VARCHAR( 800 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 10, NOW(), 'vishu','Service Flow');