INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'483', '2017-03-16 14:42:00', 'Ganesh Papde','alter tripdetails or tripdetails history', '0'
);

ALTER TABLE `tripdetail_history`  ADD `etarrivaldate` DATE NOT NULL  AFTER `is_tripend`,  ADD `materialtype` TINYINT(5) NOT NULL  AFTER `etarrivaldate`;
ALTER TABLE `tripdetails`  ADD `etarrivaldate` DATE NOT NULL  AFTER `is_tripend`,  ADD `materialtype` TINYINT(5) NOT NULL  AFTER `etarrivaldate`;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 483;