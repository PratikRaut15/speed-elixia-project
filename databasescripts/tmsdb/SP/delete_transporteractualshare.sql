DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_transporteractualshare`$$
CREATE PROCEDURE `delete_transporteractualshare`( 
    IN transid INT
    , IN actshareidparam INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
        UPDATE `transporter_actualshare`
        SET     isdeleted = 1
                , `updated_on` = todaysdate
                , `updated_by`= userid
        WHERE  	(actshareid = actshareidparam OR actshareidparam IS NULL)
        AND 	(transporterid = transid OR transid IS NULL)
        AND     customerno = custno
        AND 	isdeleted = 0;
END$$
DELIMITER ;
