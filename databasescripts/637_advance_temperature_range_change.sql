INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'637', '2018-11-14 19:45:00', 'Arvind Thakur','Advance Temperature Range Change', '0');


TRUNCATE TABLE tempalertsmsrange;

RENAME TABLE tempalertsmsrange TO advancetempalertrange;

ALTER TABLE advancetempalertrange 
DROP temp1_mute,
DROP temp2_mute,
DROP temp3_mute,
DROP temp4_mute;


ALTER TABLE `advancetempalertrange` 
    CHANGE temp1_min temp1_min_sms decimal(5,2),
    CHANGE temp1_max temp1_max_sms decimal(5,2),
    CHANGE temp2_min temp2_min_sms decimal(5,2),
    CHANGE temp2_max temp2_max_sms decimal(5,2),
    CHANGE temp3_min temp3_min_sms decimal(5,2),
    CHANGE temp3_max temp3_max_sms decimal(5,2),
    CHANGE temp4_min temp4_min_sms decimal(5,2),
    CHANGE temp4_max temp4_max_sms decimal(5,2);

ALTER TABLE advancetempalertrange 
ADD temp1_min_email decimal(5,2) AFTER temp4_max_sms,
ADD temp1_max_email decimal(5,2) AFTER temp1_min_email,
ADD temp2_min_email decimal(5,2) AFTER temp1_max_email,
ADD temp2_max_email decimal(5,2) AFTER temp2_min_email,
ADD temp3_min_email decimal(5,2) AFTER temp2_max_email,
ADD temp3_max_email decimal(5,2) AFTER temp3_min_email,
ADD temp4_min_email decimal(5,2) AFTER temp3_max_email,
ADD temp4_max_email decimal(5,2) AFTER temp4_min_email;



UPDATE  dbpatches
SET     patchdate = '2018-11-14 19:45:00'
        ,isapplied = 1
WHERE   patchid = 637;