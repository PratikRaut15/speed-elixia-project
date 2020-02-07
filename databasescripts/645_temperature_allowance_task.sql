INSERT INTO `dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('645', '2018-12-29 11:29:00','Arvind Thakur','temperature allowance task', '0');

ALTER TABLE vehicle
ADD COLUMN temp1_allowance DECIMAL(5,2)	DEFAULT 0 AFTER `temp1_max`,
ADD COLUMN temp2_allowance DECIMAL(5,2)	DEFAULT 0 AFTER `temp2_max`,
ADD COLUMN temp3_allowance DECIMAL(5,2)	DEFAULT 0 AFTER `temp3_max`,
ADD COLUMN temp4_allowance DECIMAL(5,2)	DEFAULT 0 AFTER `temp4_max`;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 645;

