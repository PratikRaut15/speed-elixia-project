-- Insert SQL here.

ALTER TABLE `communicationhistory` CHANGE `message` `message` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
ALTER TABLE `communicationqueue` CHANGE `message` `message` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 12, NOW(), 'Sanket Sheth','Varchar to Text');