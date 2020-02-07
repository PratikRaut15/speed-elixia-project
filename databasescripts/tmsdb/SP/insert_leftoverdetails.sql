DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_leftoverdetails`$$
CREATE PROCEDURE `insert_leftoverdetails`( 
     IN factoryid int
    , IN depotid int
    , IN weight float(6,2)
    , IN volume float(6,2)
    , IN daterequired DATETIME
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentleftoverid INT
)
BEGIN
  
    INSERT INTO leftoverdetails ( 
                            factoryid
                            , depotid
                            , weight
                            , volume
                            , daterequired
                            , customerno
                            , created_on
                            , updated_on
                            , created_by
                            , updated_by
                        ) 
    VALUES  ( 
                factoryid
                , depotid
                , weight
                , volume
                , daterequired
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
            );
  
    SET currentleftoverid = LAST_INSERT_ID();
END$$
DELIMITER ;
