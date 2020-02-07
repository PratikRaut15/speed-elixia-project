-- Insert SQL here.

ALTER TABLE `delivery`.`master_shipment` ADD INDEX (`orderid`) ;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 19, NOW(), 'Akhil VL','indexed orderid in master shipping');
