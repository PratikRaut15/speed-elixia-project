DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factory_officials`$$
CREATE PROCEDURE `get_factory_officials`(
	IN custno INT,
    IN factoryidparam INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
    END IF;
    
    SELECT 
		f.factoryid
        , tm.userid
        , u.realname
        , u.username
        , u.email
        , u.phone
    FROM factory as f
    INNER JOIN tmsmapping as tm on tm.tmsid = f.factoryid and tm.role = 'factoryofficial'
    INNER JOIN speed.user as u on u.userid = tm.userid 
    WHERE (f.customerno = custno OR custno IS NULL)
    AND   (f.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND   u.isdeleted = 0
    AND   tm.isdeleted = 0
    AND   f.isdeleted = 0;
    
    
END$$
DELIMITER ;
