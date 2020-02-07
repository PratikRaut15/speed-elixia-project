
INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 22, NOW(), 'Sanjeet Shukla','updated Image name');

UPDATE `style` SET `productimage` = '1kgTubeIce.jpg' WHERE `style`.`styleid` = 178;
UPDATE `style` SET `productimage` = '10KgTubeIce.jpg' WHERE `style`.`styleid` = 180;


