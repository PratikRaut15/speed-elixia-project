INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'621', '2018-10-05 12:00:00', 'Sanjeet Shukla', 'Added deliveryDateTime in secondary_order table', '0'
);

ALTER TABLE `secondary_order` ADD `deliveryDateTime` DATETIME NOT NULL AFTER `reason`;

UPDATE  dbpatches
SET     updatedOn = NOW(),isapplied = 1
WHERE   patchid = 621;
