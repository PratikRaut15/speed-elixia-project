-- Insert SQL here.

alter table client_address add column temp_address text default null after cityid;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 29, NOW(), 'Akhil','temp_address client_address');



