
-- Insert SQL here.

ALTER TABLE batch ADD starttime varchar(20) NOT NULL AFTER workkey;
ALTER TABLE batch ADD dummybatch VARCHAR(15) NOT NULL AFTER starttime;
ALTER TABLE `batch` CHANGE `starttime` `starttime` VARCHAR( 35 ) NOT NULL ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 152, NOW(), 'Shrikanth Suryawanshi','Probity Chnages');
