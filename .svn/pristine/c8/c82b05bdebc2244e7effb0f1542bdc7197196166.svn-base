-- Insert SQL here.
ALTER TABLE `description`
  DROP `reg_date`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 85, NOW(), 'Ajay Tripathi','remove reg date field');
