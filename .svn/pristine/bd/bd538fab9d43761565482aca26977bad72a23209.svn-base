DELIMITER $$
DROP PROCEDURE IF EXISTS update_routemaster$$
CREATE PROCEDURE `update_routemaster`(
	IN rtmasterid INT
    , IN routename VARCHAR(20)
	, IN routedescription VARCHAR(20)
	, IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
    , IN travellingtime INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE routemaster
    SET  routename = routename
		,routedescription = routedescription
		, fromlocationid = fromlocationid
        , tolocationid = tolocationid
        , distance = distance
        , travellingtime = travellingtime
		, updated_on = todaysdate
        , updated_by = userid
	WHERE routemasterid = rtmasterid;
END$$
DELIMITER ;