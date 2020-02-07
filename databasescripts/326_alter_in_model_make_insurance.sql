ALTER TABLE `make`  ADD `customerno` INT(11) NOT NULL ,  ADD `isdeleted` TINYINT(1) NOT NULL ;

ALTER TABLE `make`  ADD `userid` INT(11) NOT NULL  AFTER `customerno`,  ADD `timestamp` DATETIME NOT NULL  AFTER `userid`;

ALTER TABLE `model`  ADD `customerno` INT(11) NOT NULL ,  ADD `userid` INT(11) NOT NULL ,  ADD `timestamp` DATETIME NOT NULL ,  ADD `isdeleted` TINYINT(1) NOT NULL ;

ALTER TABLE `insurance_company`  ADD `customerno` INT(11) NOT NULL ,  ADD `userid` INT(11) NOT NULL ,  ADD `timestamp` DATETIME NOT NULL ,  ADD `isdeleted` TINYINT(1) NOT NULL ;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (326, NOW(), 'Sahil','alter_in_model_make_insurance');
