ALTER TABLE `forgot_password_request` CHANGE `otp` `otp` VARCHAR(150) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (11, NOW(), 'Ganesh Papde','alter forgot password request');

