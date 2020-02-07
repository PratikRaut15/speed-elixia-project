-- Insert SQL here.

ALTER TABLE `user` ADD `chkpushandroid` TINYINT( 1 ) NOT NULL ,
ADD `chkmanpushandroid` TINYINT( 1 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 154, NOW(), 'Sanket Sheth','Push Android');
