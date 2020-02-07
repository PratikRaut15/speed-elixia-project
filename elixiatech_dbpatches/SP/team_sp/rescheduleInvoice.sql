USE `elixiatech`;
DROP procedure IF EXISTS `rescheduleInvoice`;
DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `rescheduleInvoice`(
	IN rescheduleDateParam DATETIME,
    IN invoiceIdParam INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			/*ROLLBACK;
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
			@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
			SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
			SELECT @full_error;  
			SET isexecutedOut = 0;*/
		END;
       
		START TRANSACTION;
		BEGIN
			UPDATE invoice_reminders 
            SET reminder_date = rescheduleDateParam
            WHERE inv_rem_id = invoiceIdParam;
		END;
		COMMIT;
END$$
DELIMITER ;