
INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 21, NOW(), 'Sanjeet Shukla','secondary sales attendance table Alter lat lng column');

ALTER TABLE `attendance` CHANGE `lat` `lat` DECIMAL(9,6) NOT NULL, CHANGE `lng` `lng` DECIMAL(9,6) NOT NULL;

