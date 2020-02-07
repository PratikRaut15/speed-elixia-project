-- Insert SQL here.

ALTER TABLE `devices` CHANGE `swv` `swv` VARCHAR(15) NOT NULL;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 183, NOW(), 'Shrikanth Suryawasnhi','Software version column change');
