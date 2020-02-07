-- Insert SQL here.

ALTER TABLE `customer` DROP `customeradd1` ,
DROP `customeradd2` ,
DROP `customercity` ,
DROP `customerstate` ,
DROP `customerzip` ,
DROP `customerphone` ,
DROP `customercell` ,
DROP `customeremail` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 136, NOW(), 'Sanket Sheth','Remove Unwanted Fields');
