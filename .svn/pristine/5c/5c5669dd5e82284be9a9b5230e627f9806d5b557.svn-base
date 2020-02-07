-- Insert SQL here.

ALTER TABLE `unit` ADD `sellingprice` INT NOT NULL ;

ALTER TABLE `vehicle` ADD `subscription_cost` INT NOT NULL ,
ADD `subscription_period` INT NOT NULL ;

UPDATE vehicle SET subscription_period = (SELECT renewal FROM customer WHERE customer.customerno = vehicle.customerno);
UPDATE vehicle SET subscription_cost = (SELECT unit_msp FROM customer WHERE customer.customerno = vehicle.customerno);
UPDATE unit SET sellingprice = (SELECT unitprice FROM customer WHERE customer.customerno = unit.customerno);
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 502, NOW(), 'Sanket Sheth','Account Rework');
