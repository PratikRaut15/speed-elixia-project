-- Insert SQL here.

ALTER TABLE `client` ADD `password` VARCHAR(150) NOT NULL , 
ADD `userkey` VARCHAR(50) NOT NULL , 
ADD `otp_number` VARCHAR(150) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 11, NOW(), 'Ganesh','Client table alter add 3 columns');



