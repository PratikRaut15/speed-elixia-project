INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'488', '2017-04-03 12:24:00', 'Arvind Thakur', 'team other invoices table changed', '0'
);

alter table `otherinvoices`
add column `tax_type` TINYINT(2),
add column `inv_type` TINYINT(2),
add column `tax_amount` INT(11),
add column `remark` VARCHAR(100),
add column `created_timestamp` DATETIME; 

alter table `otherinvoices` MODIFY `due_on` DATE;



UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 488;
