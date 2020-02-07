-- Insert SQL here.

Create TABLE remarks(
id int(5) NOT NULL AUTO_INCREMENT Primary Key,
name Text NOT NULL
);

ALTER TABLE unit ADD alterremark TEXT NOT NULL after remark;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 158, NOW(), 'Shrikanth Suryawanshi','Remarks');
