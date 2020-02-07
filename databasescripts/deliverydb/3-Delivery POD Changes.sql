-- Insert SQL here.

ALTER TABLE master_orders ADD sales_type VARCHAR(35) NOT NULL AFTER customerno;
ALTER TABLE master_orders ADD ref_number VARCHAR(35) NOT NULL AFTER sales_type;

ALTER TABLE master_shipment ADD transporter_name VARCHAR(50) NOT NULL AFTER tracking_number;
ALTER TABLE master_shipment ADD lr_no VARCHAR(25) NOT NULL AFTER transporter_name;
ALTER TABLE master_shipment ADD lr_date DATETIME NOT NULL  AFTER lr_no;

ALTER TABLE master_billing_address ADD soldcode varchar(25) NOT NULL AFTER phone;
ALTER TABLE master_shipping_address ADD shipcode varchar(25) NOT NULL AFTER phone;
ALTER TABLE master_orders ADD production_details text NOT NULL AFTER status;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 3, NOW(), 'Shrikanth Suryawanshi','Delivery POD Changes');
