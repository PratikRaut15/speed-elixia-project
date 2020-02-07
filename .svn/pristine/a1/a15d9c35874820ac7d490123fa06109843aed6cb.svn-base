DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transporter`$$
CREATE PROCEDURE `insert_transporter`( 
	IN transportercode VARCHAR (20)
	, IN transportername VARCHAR (50)
    , IN transportermail VARCHAR (150)
    , IN transportermobileno VARCHAR (50)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currenttransporterid INT
	)
BEGIN
	INSERT INTO transporter(
							transportercode
                            , transportername
                            , transportermail
							, transportermobileno
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				transportercode
                , transportername
                , transportermail
				, transportermobileno
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currenttransporterid = LAST_INSERT_ID();

END$$
DELIMITER ;
