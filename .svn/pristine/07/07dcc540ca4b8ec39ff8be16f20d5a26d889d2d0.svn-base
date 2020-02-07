-- Insert SQL here.

UPDATE unit SET tempsen1 = 1 WHERE analog1_sen = 1 AND analog2_sen = 0 AND analog3_sen = 0 AND analog4_sen = 0;
UPDATE unit SET tempsen1 = 2 WHERE analog1_sen = 0 AND analog2_sen = 1 AND analog3_sen = 0 AND analog4_sen = 0;
UPDATE unit SET tempsen2 = 2 WHERE analog1_sen = 1 AND analog2_sen = 1 AND analog3_sen = 0 AND analog4_sen = 0;

ALTER TABLE `unit`
  DROP `analog1_sen`,
  DROP `analog2_sen`,
  DROP `analog3_sen`,
  DROP `analog4_sen`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 233, NOW(), 'Sanket Sheth','Temp Sen Major Fix');
