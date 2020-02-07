-- Insert SQL here.


alter table `master_orders` add column areaid int(11) default null after fenceid;
alter table `master_orders_dummy` add column areaid int(11) default null after fenceid;


-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 11, NOW(), 'Akhil VL','areaid in masters order');
