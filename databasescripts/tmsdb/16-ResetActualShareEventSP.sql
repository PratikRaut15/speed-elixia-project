SET GLOBAL event_scheduler = ON;
DELIMITER $$
DROP EVENT IF EXISTS reset_transporteractualshare$$
DROP EVENT IF EXISTS event_reset_transporteractualshare$$
CREATE EVENT `event_reset_transporteractualshare`
ON SCHEDULE EVERY 1 MONTH
STARTS '2016-01-01 04:00:00'
DO BEGIN
    CALL eventsp_ResetActualShare();
END$$

DELIMITER $$
DROP PROCEDURE IF EXISTS eventsp_ResetActualShare$$
CREATE PROCEDURE `eventsp_ResetActualShare`(
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		-- ERROR
		ROLLBACK;
	END;
	START TRANSACTION;
	UPDATE  transporter_actualshare 
	SET     shared_weight = 0
			,total_weight = 0;
	
	UPDATE transporter_actualshare tas 
	INNER JOIN transportershare ts 
    ON     ts.transporterid = tas.transporterid
	AND    ts.factoryid = tas.factoryid
	AND    ts.zoneid    =    tas.zoneid
	AND    ts.customerno = tas.customerno
	AND    ts.isdeleted = 0
	SET    tas.actualpercent = ts.sharepercent
	WHERE  tas.actualpercent != ts.sharepercent;
	COMMIT;
END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (16, NOW(), 'Mrudang Vora','reset actual share on monthly basis');

