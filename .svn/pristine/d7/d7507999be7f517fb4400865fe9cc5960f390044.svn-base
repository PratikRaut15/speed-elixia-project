DELIMITER $$
DROP PROCEDURE IF EXISTS update_pit_mapping$$
CREATE PROCEDURE `update_pit_mapping`( 
	IN pitmapid INT
    , IN actual_vehtypeid INT
    , IN vehicleno varchar(20)
    , IN drivermobileno varchar(12)
    , IN isAccepted tinyint(1)
    , IN remarksParam varchar(250)
    , IN isAutoRejectedParam tinyint(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF(isAutoRejectedParam = '' OR isAutoRejectedParam = 0) THEN
		SET isAutoRejectedParam = 0;
    END IF;
	UPDATE 	proposed_indent_transporter_mapping
	SET 	actual_vehicletypeid = actual_vehtypeid
            , vehicleno = vehicleno
			, drivermobileno = drivermobileno
            , isAccepted = isAccepted
            , remarks =  COALESCE(remarksParam, remarks)
			, updated_on = todaysdate 
            , updated_by = userid
            , isAutoRejected = isAutoRejectedParam
	WHERE 	pitmappingid = pitmapid;

END$$
DELIMITER ;