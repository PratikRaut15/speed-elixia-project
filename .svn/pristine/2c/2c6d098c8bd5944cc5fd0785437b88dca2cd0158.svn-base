-- Insert SQL here.

UPDATE checkpoint SET cadd1 = CONCAT(cadd1,",",cadd2,",",cadd3,",",ccity,",",cstate,",",czip);

ALTER TABLE `checkpoint` DROP `cadd2` ,
DROP `cadd3` ,
DROP `ccity` ,
DROP `cstate` ,
DROP `czip` ;

ALTER TABLE `checkpoint` CHANGE `cadd1` `cadd` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 26, NOW(), 'Sanket Sheth','Improving Checkpoints');
