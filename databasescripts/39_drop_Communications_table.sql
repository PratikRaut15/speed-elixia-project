-- Insert SQL here.
DROP TABLE  `communicationhistory`;
DROP TABLE  `communicationqueue`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 39, NOW(), 'Ajay Tripathi','Drop Communication Queue & Communication History');
