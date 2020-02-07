INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('526', '2017-07-31 11:19:00','Ganesh Papde','Add Old odometer', '0');

ALTER TABLE `fuelcron_alertlog` ADD `old_odometer` INT(15) NOT NULL AFTER `old_fuel_balance`;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 526;
