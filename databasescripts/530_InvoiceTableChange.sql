INSERT INTO speed.dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied) 
VALUES ('530', '2017-10-06 10:40:18', 'Sanket Sheth', 'Invoice table change - add bad debt', '0');

ALTER TABLE `invoice` ADD `is_baddebt` BOOLEAN NOT NULL AFTER `proforma_id`; 

UPDATE speed.dbpatches SET isapplied=1 WHERE patchid = 530;