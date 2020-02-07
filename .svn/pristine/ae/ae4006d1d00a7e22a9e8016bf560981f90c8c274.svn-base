-- Insert SQL here.

alter table service_call add column client_add_id int(11) not null after service_status;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 21, NOW(), 'Akhil','Added address id in service_call');



