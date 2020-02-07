INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'609', '2018-09-07 15:34:11', 'Shrikant Suryawanshi', 'Nestle - Warehouse Clubbing', '0'
);



CREATE TABLE IF NOT EXISTS `warehouseClubMppinig` (
  mapId int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  parentId INT NOT NULL,
  childId INT NOT NULL,
  customerNo INT NOT NULL,
  createdBy INT NOT NULL,
  createdOn DATETIME,
  updatedBy INT NOT NULL,
  updatedOn DATETIME,
  isdeleted tinyint(1) NOT NULL DEFAULT 0
) ;

INSERT INTO warehouseClubMppinig(parentId,childId,customerNo)
VALUES
(12121,12124,473),
(12175,12176,473),
(12295,12182,473),
(12183,12184,473),
(12294,12301,473)
;



UPDATE  dbpatches
SET     updatedOn = '2018-09-07 20:00:11',isapplied = 1
WHERE   patchid = 609;
