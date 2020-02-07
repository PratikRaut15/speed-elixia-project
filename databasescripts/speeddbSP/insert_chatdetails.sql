/*
    Name          - insert_chatdetails
    Description   - Get chat detail of a client for freshchat
    Parameters    - useridParam,restoreidParam,dateParam,custnoParam

    Module    - SPEED
    Sample Call   -CALL insert_chatdetails('7431','e7adc488-6d0f-4e7c-991d-789979538eb0', '2017-12-26 13:00:43',503)

    Created by    - Yash Kanakia
    Created on    - 26-12-2017
    Change details  -
    1)  Updated by  -
        Updated on  -
        Reason     -
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS insert_chatdetails$$
CREATE PROCEDURE `insert_chatdetails`(
    IN useridParam INT,
    IN restoreidParam varchar(50),
	IN dateParam datetime,
	IN custnoParam int(11)
)
BEGIN
DECLARE externalCount int;
 BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;
SET externalCount =0;
IF(useridParam = 0) THEN
	SET useridParam = NULL;
END IF;
    IF(useridParam IS NOT NULL) THEN

        SELECT COUNT(external_id) INTO externalCount from chatdetails where external_id = useridParam;
        SET externalCount = externalCount;
        IF(externalCount = 0) THEN

            BEGIN
                INSERT INTO chatdetails(
					customerno,
                    external_id,
                    restore_id,
					created_on,
					updated_on
                )VALUES(
				    custnoParam,
                    useridParam,
                    restoreidParam,
				    dateParam,
				    dateParam
                );

            END;
		ELSE
        BEGIN
				UPDATE chatdetails
                SET
					restore_id = restoreidParam,
                    updated_on = dateParam
				WHERE external_id = useridParam;
        END;
        END IF;
    END IF;
	COMMIT;
END$$
DELIMITER ;
