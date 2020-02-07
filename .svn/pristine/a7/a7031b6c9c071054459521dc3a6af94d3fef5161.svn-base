INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES ('557', '2018-04-25 19:10:18', 'Sanjeet Shukla', 'Added Columns lat, lng in Attendance Table and companysellingprince', '0');

ALTER TABLE `style` ADD `companysellingprice` DECIMAL(11,2) NOT NULL AFTER `retailprice`;

ALTER TABLE `attendance` ADD `lat` DECIMAL(9,3) NOT NULL AFTER `onoff`, ADD `lng` DECIMAL(9,3) NOT NULL AFTER `lat`;


UPDATE dbpatches SET isapplied=1 WHERE patchid = 557;




