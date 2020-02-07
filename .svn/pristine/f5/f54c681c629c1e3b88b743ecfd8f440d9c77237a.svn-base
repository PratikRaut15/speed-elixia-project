INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'535', '2017-12-12 14:34:11', 'Yash Kanakia', 'Fresh Chat Plugin', '0'
);

DROP TABLE IF EXISTS chatdetails;
CREATE TABLE `chatdetails` (
  `chatDetailsId` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `external_id` INT DEFAULT NULL,
  `restore_id` varchar(50) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
   PRIMARY KEY (chatDetailsId)
);

/*
    Name          - fetch_chatdetails
    Description   - Fetch chat detail of a client for freshchat
    Parameters    - useridParam

    Module    		- SPEED
    Sample Call   - CALL fetch_chatdetails('7431')

    Created by    - Yash Kanakia
    Created on    - 26-12-2017
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS fetch_chatdetails$$
CREATE PROCEDURE `fetch_chatdetails`(IN `useridParam` INT)
BEGIN
              
SELECT 	chatDetailsId
		,customerno
		,external_id
		,restore_id
FROM 	chatdetails 
WHERE 	external_id = useridParam;
                    
END$$
DELIMITER ;


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

UPDATE dbpatches SET isapplied = 1, patchdate = '2017-12-14 11:00:00' where patchid = 535;