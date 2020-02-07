DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_proposed_indent`$$
CREATE PROCEDURE `delete_proposed_indent`( 
	IN propindentid int
    , IN remarkParam varchar(250)
    , IN todaysdate DATETIME
    , IN userid INT
    
)
BEGIN
DECLARE indentid INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
     BEGIN
       -- ERROR
     ROLLBACK;
	 END;
   
    START TRANSACTION;
    BEGIN
    
    SELECT proposedindentid
    INTO indentid
    FROM indent
    WHERE proposedindentid = propindentid
    AND isdeleted = 0;
    
    IF(indentid IS NULL) THEN
	UPDATE 	proposed_indent
	SET 	isdeleted = 1
			,remark =  COALESCE(remarkParam, remark)
			,updated_on = todaysdate
            ,updated_by = userid
	WHERE 	proposedindentid = propindentid;
    
	CALL delete_pit_mapping(NULL,propindentid,todaysdate,userid);
    CALL delete_sku_mapping(NULL,propindentid,todaysdate,userid);
    
	END IF;
    
	COMMIT;
    END;
	
END$$
DELIMITER ;
