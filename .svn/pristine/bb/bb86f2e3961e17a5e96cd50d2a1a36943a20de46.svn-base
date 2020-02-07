INSERT INTO `dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('647', '2019-01-02 15:29:00','Arvind Thakur','temperature colour task', '0');

CREATE TABLE tempRangeColourMapping (
trcmid INT(11) PRIMARY KEY AUTO_INCREMENT ,
vehicleid INT(11) NOT NULL,
temp1_range1_start DECIMAL(5,2),
temp1_range1_end DECIMAL(5,2),
temp1_range1_color VARCHAR(7),
temp1_range2_start DECIMAL(5,2),
temp1_range2_end DECIMAL(5,2),
temp1_range2_color VARCHAR(7),
temp1_range3_start DECIMAL(5,2),
temp1_range3_end DECIMAL(5,2),
temp1_range3_color VARCHAR(7),
temp1_range4_start DECIMAL(5,2),
temp1_range4_end DECIMAL(5,2),
temp1_range4_color VARCHAR(7),
temp2_range1_start DECIMAL(5,2),
temp2_range1_end DECIMAL(5,2),
temp2_range1_color VARCHAR(7),
temp2_range2_start DECIMAL(5,2),
temp2_range2_end DECIMAL(5,2),
temp2_range2_color VARCHAR(7),
temp2_range3_start DECIMAL(5,2),
temp2_range3_end DECIMAL(5,2),
temp2_range3_color VARCHAR(7),
temp2_range4_start DECIMAL(5,2),
temp2_range4_end DECIMAL(5,2),
temp2_range4_color VARCHAR(7),
temp3_range1_start DECIMAL(5,2),
temp3_range1_end DECIMAL(5,2),
temp3_range1_color VARCHAR(7),
temp3_range2_start DECIMAL(5,2),
temp3_range2_end DECIMAL(5,2),
temp3_range2_color VARCHAR(7),
temp3_range3_start DECIMAL(5,2),
temp3_range3_end DECIMAL(5,2),
temp3_range3_color VARCHAR(7),
temp3_range4_start DECIMAL(5,2),
temp3_range4_end DECIMAL(5,2),
temp3_range4_color VARCHAR(7),
temp4_range1_start DECIMAL(5,2),
temp4_range1_end DECIMAL(5,2),
temp4_range1_color VARCHAR(7),
temp4_range2_start DECIMAL(5,2),
temp4_range2_end DECIMAL(5,2),
temp4_range2_color VARCHAR(7),
temp4_range3_start DECIMAL(5,2),
temp4_range3_end DECIMAL(5,2),
temp4_range3_color VARCHAR(7),
temp4_range4_start DECIMAL(5,2),
temp4_range4_end DECIMAL(5,2),
temp4_range4_color VARCHAR(7),
customerno INT(11) NOT NULL ,
created_by INT,
created_on DATETIME,
updated_by INT,
updated_on DATETIME,
isdeleted TINYINT(1) DEFAULT 0
);


INSERT INTO tempRangeColourMapping (vehicleid
,customerno
,temp1_range1_start
,temp1_range1_end
,temp1_range1_color
,temp1_range2_start
,temp1_range2_end
,temp1_range2_color
,temp1_range3_start
,temp1_range3_end
,temp1_range3_color)
SELECT  vehicleid
    ,customerno
,'12.00'
, '18.00'
, '#00FF00'
, '18.00'
, '22.00'
, '#FFFF00'
, '22.00'
, '25.00'
, '#FFA500'
FROM vehicle
WHERE customerno = 664
and kind = 'Warehouse';

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 647;
