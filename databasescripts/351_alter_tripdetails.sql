ALTER TABLE `tripdetails` CHANGE `routename` `routename` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `tripdetail_history` CHANGE `routename` `routename` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (351, NOW(), 'Ganesh Papde','alter tripdetails or triphistory table for routename size');
