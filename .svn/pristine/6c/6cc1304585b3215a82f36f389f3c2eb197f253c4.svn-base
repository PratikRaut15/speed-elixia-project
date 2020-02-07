DELIMITER $$
DROP PROCEDURE IF EXISTS `get_multiple_factory_officials`$$
CREATE PROCEDURE `get_multiple_factory_officials`(
	IN custno INT,
    IN factoryidlist VARCHAR(100)
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(factoryidlist = '') THEN
		SET factoryidlist = NULL;
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
    AND   (find_in_set(f.factoryid, factoryidlist) OR factoryidlist IS NULL)
    AND   u.isdeleted = 0
    AND   tm.isdeleted = 0
    AND   f.isdeleted = 0;
    
END$$
DELIMITER ;
