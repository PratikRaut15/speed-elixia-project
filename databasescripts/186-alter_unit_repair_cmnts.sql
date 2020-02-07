-- Insert SQL here.

ALTER TABLE `unit` ADD `comments_repair` VARCHAR(50) NOT NULL AFTER `comments`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 186, NOW(), 'Ganesh Papde','add coloumn repairer comments');
