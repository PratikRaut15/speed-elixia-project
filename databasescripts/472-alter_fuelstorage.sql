INSERT INTO `speed`.`dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied`) VALUES ('472', '2017-02-28 17:16:02', 'Ganesh','fuelstorrage table alter', '0');

ALTER TABLE `fuelstorrage` ADD `chequeno` VARCHAR(50) NOT NULL AFTER `ofasnumber`, ADD `chequeamt` DECIMAL(10,2) NOT NULL AFTER `chequeno`, ADD `chequedate` DATE NOT NULL AFTER `chequeamt`, ADD `tdsamt` DECIMAL(10,2) NOT NULL AFTER `chequedate`;

UPDATE  dbpatches
SET     patchdate = '2017-02-28 17:00:00'
        ,isapplied =1
WHERE   patchid = 472;
