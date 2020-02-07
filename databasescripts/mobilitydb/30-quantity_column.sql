-- Insert SQL here.

alter table  `service_ordered` add column quantity int(5) default 1 after serviceid; 

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 30, NOW(), 'Akhil','quantity column in service ordered');



