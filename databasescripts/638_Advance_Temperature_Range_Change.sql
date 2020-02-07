INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'638', '2018-11-17 19:45:00', 'Arvind Thakur','Advance Temperature Range Change', '0');

ALTER TABLE eventalerts
ADD temp_sms tinyint(1) DEFAULT 0 AFTER temp4,
ADD temp2_sms tinyint(1) DEFAULT 0 AFTER temp_sms,
ADD temp3_sms tinyint(1) DEFAULT 0 AFTER temp2_sms,
ADD temp4_sms tinyint(1) DEFAULT 0 AFTER temp3_sms,
ADD temp_email tinyint(1) DEFAULT 0 AFTER temp4_sms,
ADD temp2_email tinyint(1) DEFAULT 0 AFTER temp_email,
ADD temp3_email tinyint(1) DEFAULT 0 AFTER temp2_email,
ADD temp4_email tinyint(1) DEFAULT 0 AFTER temp3_email;


UPDATE  dbpatches
SET     patchdate = '2018-11-17 19:45:00'
        ,isapplied = 1
WHERE   patchid = 638;
