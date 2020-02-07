DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_login_history`$$
CREATE PROCEDURE `insert_login_history`(
    IN pageMasterIdParam int,
    IN loginTypeParam tinyint,
    IN custno INT,
    IN todaysdate DATETIME,
    IN userid INT,
    OUT logHistoryId int
)
BEGIN


    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        ROLLBACK;
    END;
    START TRANSACTION;

    IF(custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(pageMasterIdParam = 0) THEN
        SET pageMasterIdParam = NULL;
    END IF;


        BEGIN
            INSERT INTO login_history_details(
                page_master_id,
                `type`,
                customerno,
                created_on,
                created_by
            )VALUES(
                pageMasterIdParam,
                loginTypeParam,
                custno,
                todaysdate,
                userid
            );
        END;

        SET logHistoryId = LAST_INSERT_ID();
    COMMIT;
END$$
DELIMITER ;
