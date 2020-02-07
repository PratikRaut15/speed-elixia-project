-- Insert SQL here.

CREATE TABLE  `insurance_company` (
`id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 250 ) NOT NULL
) ENGINE = MYISAM ;

INSERT INTO `insurance_company` (`id`, `name`) VALUES (NULL, 'HSBC India'), (NULL, 'Bajaj Allianz'), (NULL, 'ICICI Lombard'), (NULL, 'United India Insurance Co.'), (NULL, 'The New India Assurance Co.');

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 70, NOW(), 'Ajay Tripathi','insurance company table');
