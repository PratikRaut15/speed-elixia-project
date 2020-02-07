INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'601', '2018-08-23 20:10:00', 'Shrikant Suryawanshi', 'Expediters API Changes', '0');



create table expeditorsTripDetails(
    expId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tripid INT NOT NULL,
    referenceNumber VARCHAR(50),
    shipmentId VARCHAR(100),
    shipmentInitiated TINYINT DEFAULT 0,
    shipmentCompleted TINYINT DEFAULT 0,
    customerNo INT NOT NULL,
    createdBy INT NOT NULL,
    createdOn DATETIME DEFAULT NULL,
    updatedBy INT NOT NULL,
    updatedOn DATETIME DEFAULT NULL,
    isDeleted TINYINT NOT NULL
);

ALTER TABLE `expeditorsTripDetails` CHANGE `shipmentId` `shipmentId` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;



UPDATE  dbpatches
SET     updatedOn = '2018-08-23 20:10:00',isapplied = 1
WHERE   patchid = 601;



