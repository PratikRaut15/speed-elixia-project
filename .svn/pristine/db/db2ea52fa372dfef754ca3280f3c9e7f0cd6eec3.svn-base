-- Insert SQL here.

ALTER TABLE `client_address` ADD INDEX ( `clientid` ) ;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 31, NOW(), 'Akhil','indexed clientid in address table');



