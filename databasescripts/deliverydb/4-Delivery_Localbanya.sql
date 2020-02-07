-- Insert SQL here.

alter table master_orders add slot int(11) NOT NULL After updated_on;
alter table master_orders add lat varchar(20) NOT NULL After slot;
alter table master_orders add longi varchar(20) NOT NULL After lat;
alter table master_orders add accuracy varchar(20) NOT NULL After longi;
alter table master_orders add fenceid varchar(20) NOT NULL After accuracy;
alter table master_orders add flag tinyint(1) NOT NULL After fenceid;


alter table dbpatches add flag tinyint(1) NOT NULL After patchdesc;
alter table master_billing_address add flag tinyint(1) NOT NULL After soldcode;
alter table master_discount add flag tinyint(1) NOT NULL After amount;
alter table master_history add flag tinyint(1) NOT NULL After timestamp;
alter table master_item add flag tinyint(1) NOT NULL After total;
alter table master_paymentdetails add flag tinyint(1) NOT NULL After bank_ref_number;
alter table master_paymentmethod add flag tinyint(1) NOT NULL After gateway;
alter table master_reason add flag tinyint(1) NOT NULL After timestamp;
alter table master_shipment add flag tinyint(1) NOT NULL After route;
alter table master_status add flag tinyint(1) NOT NULL After timestamp;
alter table master_taxes add flag tinyint(1) NOT NULL After amount;

alter table master_shipping_address add landmark varchar(150) NOT NULL After shipcode;
alter table master_shipping_address add area varchar(50) NOT NULL After landmark;
alter table master_shipping_address add address_main varchar(250) NOT NULL after area;
alter table master_shipping_address add flag tinyint(1) NOT NULL after address_main;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 4, NOW(), 'Shrikanth Suryawanshi','Delivery POD Changes');
