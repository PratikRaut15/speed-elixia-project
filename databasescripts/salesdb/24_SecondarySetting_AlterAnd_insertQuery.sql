INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc)
VALUES ('24', '2018-01-31 14:30:00', 'Sanjeet Shukla', 'Added isCustomerGroup column in secondarySetting table');

ALTER TABLE `secondarySettings` ADD `isCustomerGroup` INT NOT NULL DEFAULT '0' AFTER `isDeadStock`;
UPDATE secondarySettings SET isCustomerGroup = '1' WHERE id = 1 and customerNo = 193;



