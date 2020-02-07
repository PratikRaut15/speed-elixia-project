-- Insert SQL here.

ALTER TABLE unit ADD remark TEXT NOT NULL after teamid;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 155, NOW(), 'Shrikanth Suryawanshi','Add Remark To Unit');