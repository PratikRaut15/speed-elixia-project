INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'493', '2017-04-15 17:00:00', 'Arvind Thakur', 'modify sp_ticket_details table', '0'
);

ALTER TABLE `sp_ticket_details`
ADD COLUMN `noteid` INT(11) DEFAULT 0;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 493;