-- Insert SQL here.

ALTER TABLE `payment` DROP `exp_date`;

ALTER TABLE  `payment` CHANGE  `cardno`  `ifsc_code` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE  `card_name`  `bank_name` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE  `maintenance` ADD  `isdeleted` TINYINT NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 94, NOW(), 'AJAY TRIPATHI','payment alter');
