-- Insert SQL here.
ALTER TABLE `description`
  DROP `reg_no`;

ALTER TABLE  `tax` ADD  `reg_no` VARCHAR( 225 ) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 89, NOW(), 'Ajay Tripathi','remove reg no. field');
