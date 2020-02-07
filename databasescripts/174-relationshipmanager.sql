-- Insert SQL here.
create table relationship_manager(
rid int(11) NOT NULL AUTO_INCREMENT Primary KEY,
manager_name varchar(100) NOT NULL,
manager_mobile varchar(15) NOT NULL,
manager_email varchar(60) NOT NULL,
isdeleted tinyint(1)
);

ALTER TABLE customer add rel_manager varchar(5) NOT NULL AFTER use_advanced_alert;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 174, NOW(), 'Shrikanth Suryawanshi','Relationship Manager');
