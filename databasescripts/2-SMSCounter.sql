-- Insert SQL here.

ALTER TABLE `customer` ADD `totalsms` INT( 11 ) NOT NULL ,
ADD `smsleft` INT( 11 ) NOT NULL ;

ALTER TABLE `customer`
  DROP `notes`,
  DROP `agreedby`,
  DROP `agreeddate`;

ALTER TABLE `communicationqueue` ADD `customerno` INT( 11 ) NOT NULL ;
ALTER TABLE `communicationhistory` ADD `customerno` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 2, NOW(), 'Sanket Sheth','SMS Counter');
