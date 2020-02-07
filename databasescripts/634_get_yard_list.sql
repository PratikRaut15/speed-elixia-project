INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'634', '2018-11-13 19:30:00', 'Manasvi Thakur','To get yard list', '0');
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_yard_list`$$
CREATE  PROCEDURE `get_yard_list`(
	IN custnoparam INT
	,IN tripIdparam INT
    ,OUT checkpointparam INT
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
    END IF;
	    SELECT checkpointid,cname
	    FROM checkpoint AS ch LEFT JOIN tripdetails AS t ON  t.returnYard = ch.checkpointId 
	    WHERE (ch.customerno = custnoparam OR custnoparam IS NULL)
	    AND ch.isdeleted = 0;


END$$
DELIMITER ;

ALTER TABLE `tripdetails`  ADD `returnYard` INT(11) NOT NULL DEFAULT '0' ;

UPDATE  dbpatches
SET     patchdate = '2018-11-13 19:30:00'
        ,isapplied =1
WHERE   patchid = 634;