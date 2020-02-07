DELIMITER $$
		DROP PROCEDURE IF EXISTS `deleteInvoiceReminder`$$
		CREATE PROCEDURE `deleteInvoiceReminder`(
			IN invRemIdParam INT,
		    OUT isexecutedOut INT
		)
		BEGIN
			BEGIN
			DECLARE EXIT HANDLER FOR SQLEXCEPTION
				BEGIN
					ROLLBACK;
					GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
					@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
					SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
					SELECT @full_error; 
					SET isexecutedOut = 0;
				END;
		       
				START TRANSACTION;
				BEGIN
					UPDATE invoice_reminders SET isdeleted = 1 WHERE inv_rem_id = invRemIdParam;
				END;
				COMMIT;
			SET isexecutedOut = 1;
			END;
		END;