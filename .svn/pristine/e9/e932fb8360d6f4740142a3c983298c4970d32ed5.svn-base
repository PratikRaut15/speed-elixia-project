-- Insert SQL here.

alter table customer add column use_routing tinyint(1) default 0 after 	use_fuel_sensor;
update customer set use_routing=1 where customerno=151;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 229, NOW(), 'Akhil','added use_routing in customer table');
