-- Insert SQL here.

ALTER TABLE `cash_memo` ADD `start_date` DATE NOT NULL ,
ADD `end_date` DATE NOT NULL ;

ALTER TABLE `cash_memo` DROP `address` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 510, NOW(), 'Sanket Sheth','Accounts_Correction');
