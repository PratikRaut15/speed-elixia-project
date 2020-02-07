-- Insert SQL here.

ALTER TABLE `area_master` ADD INDEX (`zone_id`);
ALTER TABLE `area_master` ADD INDEX (`area_id`);

ALTER TABLE `master_shipping_address` add index (`orderid`);
ALTER TABLE `master_shipping_address_dummy` add index (`orderid`);

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 12, NOW(), 'Akhil VL','zoneid, areaid, orderid indexing in area_mater and master_shipping_address');
