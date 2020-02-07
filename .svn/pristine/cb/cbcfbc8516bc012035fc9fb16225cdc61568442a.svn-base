-- Insert SQL here.

ALTER TABLE master_shipping_address add flat varchar(35) AFTER shipcode;
ALTER TABLE master_shipping_address add building varchar(100) AFTER flat;

ALTER TABLE master_shipping_address_dummy add flat varchar(35) AFTER shipcode;
ALTER TABLE master_shipping_address_dummy add building varchar(100) AFTER flat;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 8, NOW(), 'Shrikanth Suryawanshi','Building and flat addition');
