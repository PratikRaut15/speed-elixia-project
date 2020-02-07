DELIMITER $$
DROP PROCEDURE IF EXISTS `get_comqueue_message`$$
CREATE PROCEDURE `get_comqueue_message`(
    IN cqIdParam INT,
    IN customerNoParam INT
)
BEGIN

    IF(cqIdParam = 0) THEN
        SET cqIdParam = NULL;
    END IF;

    IF(customerNoParam = 0) THEN
        SET customerNoParam = NULL;
    END IF;


    SELECT
            message,
            timeadded
    FROM    comqueue c
    WHERE   (c.cqid = cqIdParam OR cqIdParam IS NULL)
    AND     (c.customerno = customerNoParam OR customerNoParam IS NULL);
    
    

END$$
DELIMITER ;

CALL get_comqueue_message(194233268,742);
