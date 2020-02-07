DELIMITER $$
DROP PROCEDURE IF EXISTS update_routecheckpoint$$
CREATE PROCEDURE `update_routecheckpoint`(
	IN routechkptid INT
    , IN routemasterid INT
	, IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE routecheckpoint
    SET  routemasterid = routemasterid
		, fromlocationid = fromlocationid
        , tolocationid = tolocationid
        , distance = distance
        , updated_on = todaysdate
        , updated_by = userid
	WHERE routecheckpointid = routechkptid;
END$$
DELIMITER ;