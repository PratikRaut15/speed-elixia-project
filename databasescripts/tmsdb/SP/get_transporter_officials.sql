DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporter_officials`$$
CREATE PROCEDURE `get_transporter_officials`(
	IN custno INT,
    IN transporteridparam INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(transporteridparam = '' OR transporteridparam = 0) THEN
		SET transporteridparam = NULL;
    END IF;
    
    SELECT 
		t.transporterid
        , tm.userid
        , u.realname
        , u.username
        , u.email
        , u.phone
    FROM transporter as t
    INNER JOIN tmsmapping as tm on tm.tmsid = t.transporterid and tm.role = 'transporter'
    INNER JOIN speed.user as u on u.userid = tm.userid 
    WHERE (t.customerno = custno OR custno IS NULL)
    AND   (t.transporterid = transporteridparam OR transporteridparam IS NULL)
    AND   u.isdeleted = 0
    AND   tm.isdeleted = 0
    AND   t.isdeleted = 0;
    
    
END$$
DELIMITER ;
