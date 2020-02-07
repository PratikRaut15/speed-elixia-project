-- Insert SQL here.

ALTER TABLE customer ADD use_immobiliser  TINYINT(1) NOT NULL AFTER use_buzzer;

ALTER TABLE unit ADD is_mobiliser TINYINT(1) NOT NULL AFTER analog4_sen;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 157, NOW(), 'Shrikanth Suryawanshi','Add Remark To Unit');
