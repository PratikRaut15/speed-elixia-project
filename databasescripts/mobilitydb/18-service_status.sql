-- Insert SQL here.

alter table  `service_call` add column service_status tinyint(1) default 0 after trackieid;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 18, NOW(), 'akhil','service_status in service call table');



