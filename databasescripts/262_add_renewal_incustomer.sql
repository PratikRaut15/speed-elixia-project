-- Insert SQL here.

ALTER TABLE `customer`  ADD `renewal` INT(4) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (262, NOW(), 'Sahil','add renewal in customer');
