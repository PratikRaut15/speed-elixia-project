DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_vehicle`$$
CREATE  PROCEDURE `insert_vehicle`(
    IN transporterid INT
    , IN vehicletypeid INT
    , IN vehicleno VARCHAR(20)
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentvehicleid INT
    )
BEGIN
    INSERT INTO vehicle(
                            transporterid
                            , vehicletypeid
                            , vehicleno
                            , customerno
                            , created_on
                            , updated_on
                            , created_by
                            , updated_by
                        )
    VALUES (
                transporterid
                , vehicletypeid
                , vehicleno
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
            );
    SET currentvehicleid = LAST_INSERT_ID();
END$$
DELIMITER ;
