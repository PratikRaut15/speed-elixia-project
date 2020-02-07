DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_leftover_sku_mapping`$$
CREATE  PROCEDURE `insert_leftover_sku_mapping`( 
    IN leftoverid INT
    , IN skuid INT
    , IN no_of_units INT
    , IN totalWeight DECIMAL(6,2)
    , IN totalVolume DECIMAL(6,2)
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT current_leftover_sku_mappingid INT
)
BEGIN
    IF (no_of_units != 0 &&  totalWeight != 0 && totalVolume != 0) THEN
        INSERT INTO leftover_sku_mapping( 
                                        leftoverid
                                        , skuid
                                        , no_of_units
                                        , totalWeight
                                        , totalVolume
                                        , customerno
                                        , created_on
                                        , updated_on 
                                        , created_by
                                        , updated_by
                                    )
        VALUES ( 
                    leftoverid
                    , skuid
                    , no_of_units
                    , totalWeight
                    , totalVolume
                    , customerno
                    , todaysdate
                    , todaysdate
                    , userid
                    , userid
                );
  
        SET current_leftover_sku_mappingid = LAST_INSERT_ID();
    END IF;
END$$
DELIMITER ;
