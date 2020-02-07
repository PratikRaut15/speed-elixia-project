

-- table alteration SQL here.

alter table fuelstorrage modify column fuel float(5,2);
alter table vehicle modify column fuel_balance float(5,2);



-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 131, NOW(), 'akhil vl','fuel into float');