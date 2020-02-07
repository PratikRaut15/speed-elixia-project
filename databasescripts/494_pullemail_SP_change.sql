INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'494', '2017-04-19 17:29:00', 'Arvind Thakur', 'change in pullemail SP', '0'
);



DELIMITER $$
DROP PROCEDURE IF EXISTS pullemail$$
CREATE PROCEDURE pullemail(
    IN customernoParam INT(11)
)

BEGIN

    SELECT  `eid`
            ,`email_id` 
    FROM    `report_email_list`
    WHERE   `customerno` IN (customernoParam,0);

END$$
DELIMITER ;



UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 494;