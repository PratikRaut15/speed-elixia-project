


DROP procedure IF EXISTS `get_customField_customer`;
DELIMITER $$
CREATE  PROCEDURE `get_customField_customer`(
    IN customernoParam INT
)
BEGIN

    SELECT  customtype.`name`
            , customfield.customname
    FROM    customfield
    INNER JOIN 	customer ON customer.customerno = customfield.customerno
    INNER JOIN 	customtype ON customtype.id = customfield.custom_id
    WHERE   customfield.usecustom = 1 
    AND     customer.customerno = customernoParam;


END$$
DELIMITER ;