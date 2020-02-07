INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied) 
VALUES ('562', '2018-05-21 20:30:18', 'Sanjeet Shukla', 'Added Columns in route and dailyreport table', '0');

ALTER TABLE `dailyreport` ADD `trip_count` INT NOT NULL AFTER `email_count`; 

ALTER TABLE `route` ADD `routetype` BOOLEAN NOT NULL AFTER `isregister`; 


UPDATE dbpatches SET isapplied=1 WHERE patchid = 562;




