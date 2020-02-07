UPDATE maintenance_rules SET isdeleted = 1 WHERE conditionid = 3 AND customerno = 118;


INSERT INTO `maintenance_conditions` (`conditionid`, `transactiontypeid`, `conditionname`, `customerno`, `created_by`, `updated_by`, `created_on`, `updated_on`, `isdeleted`) VALUES (NULL, '1', 'Amount', '118', '491', '491', '2016-02-11 00:00:00', '2016-02-11 00:00:00', '0'), (NULL, '2', 'Amount', '118', '491', '491', '2016-02-11 00:00:00', '2016-02-11 00:00:00', '0');


UPDATE  maintenance_rules SET sequenceno = 3 WHERE ruleid = 1 AND customerno = 118;

UPDATE  maintenance_rules SET sequenceno = 4 WHERE ruleid = 2 AND customerno = 118;

UPDATE  maintenance_rules SET sequenceno = 5 WHERE ruleid = 3 AND customerno = 118;

UPDATE  maintenance_rules SET sequenceno = 3 WHERE ruleid = 4 AND customerno = 118;

UPDATE  maintenance_rules SET sequenceno = 4 WHERE ruleid = 5 AND customerno = 118;

UPDATE  maintenance_rules SET sequenceno = 5 WHERE ruleid = 6 AND customerno = 118;


INSERT INTO `maintenance_rules` (`ruleid`, `conditionid`, `minval`, `maxval`, `sequenceno`, `approverid`, `customerno`, `created_by`, `updated_by`, `created_on`, `updated_on`, `isdeleted`) VALUES (NULL, '3', '0', '2500', '1', '19', '118', '491', '491', '2016-02-11 00:00:00', '2016-02-11 00:00:00', '0'), (NULL, '3', '2501', '1000000', '2', '18', '118', '491', '491', '2016-02-11 00:00:00', '2016-02-11 00:00:00', '0'), (NULL, '21', '0', '2500', '1', '19', '118', '491', '491', '2016-02-11 00:00:00', '2016-02-11 00:00:00', '0'), (NULL, '21', '2501', '1000000', '2', '18', '118', '491', '491', '2016-02-11 00:00:00', '2016-02-11 00:00:00', '0'),
(NULL, '22', '0', '2500', '1', '19', '118', '491', '491', '2016-02-11 00:00:00', '2016-02-11 00:00:00', '0'), (NULL, '22', '2501', '1000000', '2', '18', '118', '491', '491', '2016-02-11 00:00:00', '2016-02-11 00:00:00', '0');

ALTER TABLE `maintenance`  ADD `is_sentfinalpayment` TINYINT(1) NOT NULL  AFTER `payment_approval_note`;

ALTER TABLE `maintenance_history`  ADD `is_sentfinalpayment` TINYINT(1) NOT NULL  AFTER `payment_approval_note`;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (355, NOW(), 'Sahil','trigon approval conditions changes');
