DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicletype_count`$$
CREATE PROCEDURE `get_vehicletype_count`(
	IN custno INT
	,IN transid INT
    	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
IF(transid = '' OR transid = 0) THEN
		SET transid = NULL;
	END IF;
       
	SELECT 	vtm.vehicletypeid
            ,vtm.vehiclecount
            ,vt.vehiclecode
            ,vt.volume
            ,vt.weight
            ,vtm.transporterid
            FROM vehtypetransmapping vtm
            INNER JOIN vehicletype vt ON vtm.vehicletypeid = vt.vehicletypeid
    	WHERE 	(vtm.customerno = custno OR custno IS NULL)
    	AND (vtm.transporterid = transid OR transid IS NULL) order by vt.weight DESC;

END$$
DELIMITER ;
