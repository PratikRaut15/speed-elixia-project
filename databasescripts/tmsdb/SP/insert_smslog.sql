DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_smslog`$$
CREATE PROCEDURE `insert_smslog`(
IN mobilenoparam VARCHAR(10)
, IN messageparam VARCHAR(250)
, IN responseparam VARCHAR(250)
, IN proposedindentidparam INT
, IN smssentparam TINYINT
, IN customernoparam INT
, IN todaysdate DATETIME
, OUT smsid INT
)
BEGIN

INSERT INTO smslog (
					mobileno
                    , message
                    , response
                    , proposedindentid
                    , customerno
                    , issmssent
                    , inserted_datetime
                    ) 
		VALUES (
					mobilenoparam
					, messageparam
					, responseparam
					, proposedindentidparam
                    , customernoparam
                    , smssentparam
					, todaysdate
                );
SET smsid = LAST_INSERT_ID();
END$$
DELIMITER ;
