DELIMITER $$
DROP TRIGGER IF EXISTS before_delete_from_ticketusermapping$$
CREATE TRIGGER before_delete_from_ticketusermapping BEFORE DELETE ON delete_from_ticketusermapping
FOR EACH ROW 
BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
 BEGIN
 	INSERT INTO ticket_user_mapping_history
 				(
 					mapid,
 					ticketid,
 					userid,
 					username,
 					email,
 					is_deleted,
 					updatedOn
 				)
 				VALUES (
 					OLD.mapid,
 					OLD.ticketid,
 					OLD.userid,
 					OLD.username,
 					OLD.email,
 					OLD.is_deleted,
 					@serverTime
 					);
 	END;
END$$
DELIMITER ;
