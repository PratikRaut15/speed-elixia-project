-- Insert SQL here.

CREATE TABLE  `insurance` (
`insuranceid` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`value` INT( 10 ) NOT NULL ,
`premium` INT( 10 ) NOT NULL ,
`start_date` DATE NOT NULL ,
`end_date` DATE NOT NULL ,
`amount` INT( 10 ) NOT NULL ,
`notes` VARCHAR( 250 ) NOT NULL ,
`companyid` INT( 5 ) NOT NULL ,
`claim_place` VARCHAR( 250 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
PRIMARY KEY (  `insuranceid` )
) ENGINE = MYISAM ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 78, NOW(), 'Ajay Tripathi','insurance table');
