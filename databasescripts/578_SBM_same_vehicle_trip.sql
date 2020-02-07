INSERT INTO speed.dbpatches (
patchid ,
patchdate ,
appliedby ,
patchdesc ,
isapplied
)
VALUES (
'578', '2018-07-17 15:04:15', 'Arvind Thakur', 'SBM triplrmapping changes', '0'
);



ALTER TABLE `triplrmapping`
ADD COLUMN `orderno` VARCHAR(20) NOT NULL AFTER `lrno`;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_trip_lr_mapping`$$
CREATE  PROCEDURE `insert_trip_lr_mapping`(
    IN tripIdParam INT
    , IN lrNoParam VARCHAR(25)
    , IN triplognoParam VARCHAR(20)
    , IN lrDateTimeParam DATETIME
    , IN consignorIdParam INT
    , IN consigneeIdParam INT
    , IN customerNoParam INT
    , IN userIdParam INT
    , IN todaysDate DATETIME
    )
BEGIN




        INSERT INTO triplrmapping(
            tripid
            , lrno
            , orderno
            , lrdatetime
            , lrconsignorid
            , lrconsigneeid
            , customerno
            , created_by
            , created_on
        )
        VALUES (
            tripIdParam
            , lrNoParam
            , triplognoParam
            , lrDateTimeParam
            , consignorIdParam
            , consigneeIdParam
            , customerNoParam
            , userIdParam
            , todaysDate
        );


END$$
DELIMITER ;