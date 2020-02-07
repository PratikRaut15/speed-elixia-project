-- Insert SQL here.

ALTER TABLE `devices` ADD `installdate` DATE NOT NULL AFTER `satv` ;

ALTER TABLE `devices`
  DROP `contract`,
  DROP `rate`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 29, NOW(), 'Sanket Sheth','Team Changes 2');
