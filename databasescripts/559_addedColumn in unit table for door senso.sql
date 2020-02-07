INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied) 
VALUES ('559', '2018-05-07 19:30:18', 'Sanjeet Shukla', 'addedColumn in unit table for door sensor- Reema Transport', '0');

ALTER TABLE `unit` ADD `door_digitalio` INT NOT NULL AFTER `digitalio`, ADD `isDoorExt` BOOLEAN NOT NULL AFTER `door_digitalio`;


UPDATE dbpatches SET isapplied=1 WHERE patchid = 559;




