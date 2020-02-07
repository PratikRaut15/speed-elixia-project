INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'620', '2018-09-29 12:30:00', 'Sanjeet Shukla', 'Created Setting Table for Secondary Sales', '0'
);


CREATE TABLE `secondarySettings` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`customerNo` int(10) NOT NULL,
`isDashboard` int(10) NOT NULL,
`isSecSales` int(10) NOT NULL,
`isPrimarySales` int(10) NOT NULL,
`isCatelog` int(10) NOT NULL,

`isSrList` int(10) NOT NULL,
`isDeliveryDateTime` int(10) NOT NULL,
 `createdBy` int(10) NOT NULL,
 `createdOn` datetime NOT NULL,
 `updatedBy` int(11) NOT NULL,
 `updatedOn` datetime NOT NULL,
 `isDeleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


insert into `secondarySettings` (customerNo, isDashboard,isSecSales, isPrimarySales,isCatelog,isSrList,isDeliveryDateTime)
    values('193','1','1','0','0','0','1');
insert into `secondarySettings` (customerNo, isDashboard,isSecSales, isPrimarySales,isCatelog,isSrList,isDeliveryDateTime)    
    values('170','1','1','1','1','1','0');


UPDATE  dbpatches
SET     updatedOn = NOW(),isapplied = 1
WHERE   patchid = 620;
