DELIMITER $$
		DROP PROCEDURE IF EXISTS `editInvoiceReminder`$$
		CREATE PROCEDURE `editInvoiceReminder`(
			IN invIdParam INT,
		    IN customerNoParam INT,
		    IN ledgerNoParam INT,
		    IN productParam INT,
		    IN invAmtParam FLOAT,
		    IN invDescParam VARCHAR(255),
		    OUT isexecutedOut INT
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
			    SET 
						customerno = customerNoParam,
						ledgerno = ledgerNoParam,
						productId = productParam,
				inv_amt = invAmtParam,
				inv_desc = invDescParam
					WHERE inv_rem_id = invIdParam;

				END;
				COMMIT;
			SET isexecutedOut = 1;
		END