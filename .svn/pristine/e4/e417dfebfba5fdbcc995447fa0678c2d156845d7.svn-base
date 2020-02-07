-- Insert SQL here.

ALTER TABLE `user`  ADD `start_alert` TIME NOT NULL,  ADD `stop_alert` TIME NOT NULL DEFAULT '23:59:59';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 27, NOW(), 'Ajay Tripathi','Alerts Time Based');
