-- Insert SQL here.

ALTER TABLE master_orders ADD delivery_date date NOT NULL AFTER updated_on;
ALTER TABLE master_orders_dummy ADD delivery_date date NOT NULL AFTER updated_on;

ALTER TABLE master_orders ADD iscanceled tinyint(1) NOT NULL AFTER fenceid;
ALTER TABLE master_orders_dummy ADD iscanceled tinyint(1) NOT NULL AFTER fenceid;

ALTER TABLE master_shipping_address ADD street varchar(150) NOT NULL AFTER address_main;
ALTER TABLE master_shipping_address_dummy ADD street varchar(150) NOT NULL AFTER address_main;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 6, NOW(), 'Shrikanth Suryawanshi','Delivery localbaniya delivery date');
