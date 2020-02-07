/*
    Name          - insert_into_ticketusermapping
    Description   - insert new records in ticket_user_mapping
    Parameters    - ticketid,userid,username

    Module    - SPEED
    Sample Call   -CALL insert_into_ticketusermapping('430',1043',Sameer Chogale')

    Created by    - Manasvi Thakur
    Created on    - 22-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS insert_into_ticketusermapping$$
CREATE PROCEDURE insert_into_ticketusermapping (
IN ticketid INT(50),
IN userid INT(200),
IN username VARCHAR(500)
)
BEGIN
    DECLARE  istDateTime INT;
    DECLARE  serverTime INT;
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
 ROLLBACK;
    /*  
       GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
       @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
       SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
       SELECT @full_error;
*/       
    INSERT INTO ticket_user_mapping 
    (
        ticketid,
        userid,
        username,
        updatedOn
    )
    VALUES (
    ticketid,
    userid,
    username,
    @istDateTime
    );
END $$
DELIMITER ;

-- CALL insert_into_ticketusermapping('430','1043','Sameer Chogale')