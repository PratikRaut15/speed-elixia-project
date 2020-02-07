-- Insert SQL here.

alter table `client_address` add column area varchar(105) default null after pincode;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 28, NOW(), 'Akhil','Area column in client_address');



