ALTER TABLE `orderrequest` 
ADD `vehicletypeid` INT(11) NOT NULL AFTER `AWBno`,
ADD `weightid` INT(11) NOT NULL AFTER `vehicletypeid`,
ADD `packingrequired` TINYINT(1) NOT NULL AFTER `weightid`,
ADD `insurancerequired` TINYINT(1) NOT NULL AFTER `packingrequired`;


 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (18, NOW(), 'Ganesh','Alter orderrequest tables');
