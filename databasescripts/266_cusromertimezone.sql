-- Insert SQL here.

alter table customer add timezone int(5) NOT NULL AFTER unit_msp;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (266, NOW(), 'Shrikant Suryawanshi','Customer Timezone Addition');
