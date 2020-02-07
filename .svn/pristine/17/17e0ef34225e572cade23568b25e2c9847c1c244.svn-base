-- Insert SQL here.

ALTER TABLE `user`  ADD `summarypdf` TINYINT(1) NOT NULL AFTER `gensetcsv`,  ADD `summarycsv` TINYINT(1) NOT NULL AFTER `summarypdf`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 48, NOW(), 'Ajay Tripathi','summary column in user table');
