DELIMITER $$
DROP PROCEDURE IF EXISTS `get_yard_list`$$
CREATE  PROCEDURE `get_yard_list`(
	IN custnoparam INT
	,IN tripIdparam INT
    ,OUT checkpoi\pntaram INT
	)
BEGIN
		DECLARE varcheckpointid int;
	IF(custnoparam = '' OR custnoparam = 0) THEN
		SET custnoparam = NULL;
	END IF;
	  SET checkpointparam = 0;

 	IF(tripIdparam != '' OR tripIdparam != 0) THEN	
		SELECT returnYard INTO varcheckpointid
		FROM tripdetails 
		WHERE customerno = custnoparam  
		AND tripId = tripIdparam; 
          
	END IF;
    IF(varcheckpointid != '' OR varcheckpointid!= 0)THEN
    SET checkpointparam = varcheckpointid;
    select checkpointparam;
    END IF;
	    SELECT checkpointid,cname
	    FROM checkpoint AS ch LEFT JOIN tripdetails AS t ON  t.returnYard = ch.checkpointId 
	    WHERE (ch.customerno = custnoparam OR custnoparam IS NULL)
	    AND ch.isdeleted = 0;


END$$
DELIMITER ;