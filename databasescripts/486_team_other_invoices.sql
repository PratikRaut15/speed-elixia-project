INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'486', '2017-03-21 18:24:00', 'Arvind Thakur', 'team other invoices', '0'
);


create table otherinvoices(
    `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
    `customerno` INT(11),
    `description` VARCHAR(100),
    `amount` INT(11),
    `due_on` DATETIME,
    `pay_expected_date` DATE);

ALTER TABLE `bucket` CHANGE `purposeid` `purposeid` INT(4) NOT NULL COMMENT '1-Register,2-Repair,3-Remove,4-Replace,5-Reinstall';

ALTER TABLE `bucket` CHANGE `status` `status` INT(4) NOT NULL COMMENT '1-Reschedule,2-Success,3-Unsuccess,4-FEAssigned,5-Cancel,0-Fresh';


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 486;