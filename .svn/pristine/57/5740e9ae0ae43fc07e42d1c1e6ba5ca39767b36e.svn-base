-- Insert SQL here.
ALTER TABLE `discount_master` CHANGE `percentage` `percentage` FLOAT( 5, 2 ) NULL DEFAULT '0.00';
alter table `service_call` add column discountid  int(11) default null after client_add_id;



-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 22, NOW(), 'akhil','table alteration');



