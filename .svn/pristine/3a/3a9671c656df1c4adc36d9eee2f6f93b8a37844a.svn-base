

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'462', '2017-02-13 15:20:40', 'Shrikant Suryawanshi', 'Modify Report Master Table', '0'
);


ALTER TABLE `reportMaster` ADD `customerno` INT NOT NULL DEFAULT '0' AFTER `is_warehouse`;

INSERT INTO `speed`.`reportMaster` (`reportId`, `reportName`, `is_warehouse`, `customerno`, `isdeleted`) VALUES (NULL, 'Temperature Min Max Summary Report', '0', '131', '0');


UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 462;
