DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_transporter`$$
CREATE PROCEDURE `delete_transporter`(
	IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
     BEGIN
       -- ERROR
     ROLLBACK;
   END;
   
    START TRANSACTION;
    BEGIN


	UPDATE transporter 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE transporterid = tranid;
    
	CALL delete_transportershare(NULL, tranid, todaysdate, userid);    
	CALL delete_vehtypetransporter_mapping(NULL, tranid, todaysdate, userid);
    
    
	COMMIT;
    END;
END$$
DELIMITER ;
