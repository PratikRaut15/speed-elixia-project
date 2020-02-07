DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_vehtypetransporter_mapping`$$
CREATE PROCEDURE `insert_vehtypetransporter_mapping`( 
	IN transporterid INT
	, IN vehtypeid INT
	, IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT

)
BEGIN
	INSERT INTO vehtypetransmapping 
				(
					transporterid
					, vehicletypeid
					, customerno
					, created_on
					, updated_on
                    , created_by
                    , updated_by
				) 
	VALUES 		(
					transporterid
					, vehtypeid
					, customerno
					, todaysdate
					, todaysdate
                    , userid
                    , userid
				);

END$$
DELIMITER ;
