

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_mdlz_dump_data`$$
CREATE PROCEDURE `delete_mdlz_dump_data`( 
    IN daysParam INT
    , IN todaysdate DATE
    , OUT isExecuted TINYINT(2)
)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET isExecuted = 0;
        /* 
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
       */
    END;

    IF (daysParam = 0) THEN
        SET daysParam = NULL;
    END IF;

    SET isExecuted = 0;
   
    START TRANSACTION;
    
        DELETE FROM `mdlzRealTimeDump` 
        WHERE   ((DATE(lastupdated) < todaysdate - INTERVAL daysParam DAY) AND daysParam IS NOT NULL)
        OR      (shipmentno IS NOT NULL OR shipmentno != '');
             

        SET isExecuted = 1;
    
    COMMIT;
	
END$$
DELIMITER ;
