-- Insert SQL here.

alter table `client` add column is_otp_verified tinyint(1) default 0;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 12, NOW(), 'Akhil','Client table altered');



