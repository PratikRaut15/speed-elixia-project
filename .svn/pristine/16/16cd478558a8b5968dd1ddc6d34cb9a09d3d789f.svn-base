-- Insert SQL here.

ALTER TABLE `client` DROP `flatno`, DROP `building`, DROP `society`, DROP `landmark`, DROP `cityid`, DROP `locationid`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 19, NOW(), 'Ganesh','Drop column address from client');



