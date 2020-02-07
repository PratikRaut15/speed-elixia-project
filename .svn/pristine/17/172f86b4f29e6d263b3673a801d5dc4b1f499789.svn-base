-- Insert SQL here.

ALTER TABLE relationship_manager MODIFY COLUMN isdeleted tinyint(1) DEFAULT 0 NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 176, NOW(), 'Shreekant Suryawanshi','Relationship Manager Modification');
