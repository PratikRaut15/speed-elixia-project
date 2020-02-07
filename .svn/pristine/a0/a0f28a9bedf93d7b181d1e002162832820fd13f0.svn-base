-- Insert SQL here.
alter table accessory_map add amount INT(11) NOT NULL AFTER cost;
alter table accessory_map add quantity INT(11) NOT NULL AFTER amount;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 210, NOW(), 'Shrikanth Suryawanshi','accessory_map changes');
