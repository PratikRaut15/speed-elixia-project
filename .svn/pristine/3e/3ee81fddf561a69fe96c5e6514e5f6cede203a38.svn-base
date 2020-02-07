-- Insert SQL here.

ALTER TABLE fuelstorrage add endingkm varchar(25) NOT NULL AFTER openingkm;
ALTER TABLE fuelstorrage add average varchar(25)NOT NULL AFTER endingkm;

ALTER TABLE `vehicle`  ADD `registration_date` DATETIME NOT NULL  AFTER `submission_date`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 234, NOW(), 'Shrikant Suryawanshi','Ending km and registration date');
