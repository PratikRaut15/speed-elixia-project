/*
    Name			-	get_toggleswitch_trips
    Description 	-	To get trips depending on toggle switch for MCGM
    Parameters		-	vehicleidparam - Unique id of vehicle
                        , custnoparam - Customer number 
    Module			-	VTS / MCGM Elixiaspeed Web
    Sub-Modules 	- 	
    Sample Call		-	CALL get_toggleswitch_trips(6310, 18, '2016-03-04 00:00', '2016-03-04 23:59', 2079);
						CALL get_toggleswitch_trips(0, 288, '0000-00-00 00:00', '0000-00-00 00:00', 0);
    Created by		-	Mrudang
    Created on		- 	28 March, 2016
    Change details 	-	
    1) 	Updated by	- 	Mrudang
		Updated	on	- 	04 April, 2016
        Reason		-	Added groupidparam to filter by groups
    2) 	Updated by	- 	Mrudang
		Updated	on	- 	07 Apr, 2016
        Reason		-	Added condition to display all the records till date
	3) 	Updated by	- 	
		Updated	on	- 	
        Reason		-	
	
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_toggleswitch_trips`$$
CREATE PROCEDURE `get_toggleswitch_trips`(
   IN vehicleidparam INT
   ,IN custnoparam INT
   , IN startdateparam DATETIME
   , IN enddateparam DATETIME
   , IN groupidparam INT
)
BEGIN
	IF(vehicleidparam = 0) THEN
		SET vehicleidparam = NULL;
	END IF;
    
    IF(groupidparam = 0) THEN
		SET groupidparam = NULL;
	END IF;
    
    IF(startdateparam = '0000-00-00 00:00:00' OR enddateparam = '0000-00-00 00:00:00') THEN
		SET startdateparam = NULL;
        SET enddateparam = NULL;
	END IF;
    SELECT 		ts.id
				, ts.uid
                , ts.vehicleid
                , ts.starttime
                , ts.endtime
                , ts.startlat
                , ts.startlong
                , ts.endlat
                , ts.endlong
                , veh.vehicleno
                , dev.deviceid
	FROM 		toggle_switch ts
    INNER JOIN	vehicle veh ON veh.vehicleid = ts.vehicleid
    INNER JOIN	devices dev ON dev.uid = ts.uid
    WHERE 		ts.customerno = custnoparam
    AND			(ts.vehicleid = vehicleidparam OR vehicleidparam IS NULL)
    AND 		(veh.groupid = groupidparam OR groupidparam IS NULL)
    AND 		((ts.starttime BETWEEN startdateparam AND enddateparam) OR (startdateparam IS NULL AND enddateparam IS NULL))
	ORDER BY	ts.starttime DESC;

END $$
DELIMITER ;
