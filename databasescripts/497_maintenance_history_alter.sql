INSERT INTO `speed`.`dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied`) VALUES ('497', '2017-04-25 13:07:19', 'Ganesh Papde', 'Transaction Rollback', '0');


ALTER TABLE `maintenance_history`  ADD `is_rollback` TINYINT(2) NOT NULL DEFAULT '0'  AFTER `is_cancelled`;



UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 497;

