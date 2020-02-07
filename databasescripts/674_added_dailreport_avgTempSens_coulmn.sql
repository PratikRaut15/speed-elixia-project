INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'674', '2019-02-02 14:15:00', 'Manasvi Thakur','Add new coulmn in dailyreport table', '0');

ALTER TABLE `dailyreport`  
ADD `avg_temp_sens1` DECIMAL(5,2) NOT NULL DEFAULT '0'  AFTER `trip_count`,  
ADD `avg_temp_sens2` DECIMAL(5,2) NOT NULL DEFAULT '0'  AFTER `avg_temp_sens1`,  
ADD `avg_temp_sens3` DECIMAL(5,2) NOT NULL DEFAULT '0'  AFTER `avg_temp_sens2`,  
ADD `avg_temp_sens4` DECIMAL(5,2) NOT NULL DEFAULT '0'  AFTER `avg_temp_sens3`;




UPDATE  dbpatches
SET     updatedOn = now()
        ,isapplied = 1
WHERE   patchid = 674;