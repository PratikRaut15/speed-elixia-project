INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('617', '2018-09-26 17:30:00', 'Yash Kanakia', 'Simcard Purchase', '0');


DROP procedure IF EXISTS `purchase_sim`;

DELIMITER $$

CREATE PROCEDURE `purchase_sim`(
     IN simcardnoParam varchar(20)
    ,IN vendorIdParam INT
	,IN commentsParam varchar(20)
    ,IN teamIdParam INT
    ,IN todayParam datetime)
BEGIN
DECLARE simid INT;

BEGIN

            ROLLBACK;

			/*

            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

            SELECT @full_error;

            SET isexecutedOut = 0;

            */

    END;

    INSERT INTO simcard (simcardno,vendorid,trans_statusid,customerno,comments)
    VALUES (simcardnoParam,vendorIdParam,11,1,commentsParam);

    SET simid = last_insert_id();

    INSERT INTO trans_history (customerno ,simcard_id,teamid, type, trans_time, statusid, transaction, simcardno, invoiceno, expirydate, comments)
	VALUES (1, simid, teamIdParam, 1,todayParam, 11, 'New Purchase','','','',commentsParam);

END$$

DELIMITER ;


UPDATE `dbpatches`
SET isapplied = 1
WHERE patchid=617
