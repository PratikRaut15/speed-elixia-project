DELIMITER $$
DROP PROCEDURE IF EXISTS update_transporter$$
CREATE PROCEDURE `update_transporter`( 
	IN transportercode VARCHAR(20)
	, IN transportername VARCHAR (50)
    , IN transportermail VARCHAR (150)
    , IN transportermobileno VARCHAR (50)
	, IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE transporter
    SET  transportercode = transportercode
		, transportername = transportername
        , transportermail = transportermail
		, transportermobileno = transportermobileno
        , updated_on = todaysdate
		, updated_by = userid
	WHERE transporterid = tranid;
END$$
DELIMITER ;