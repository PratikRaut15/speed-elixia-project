DELIMITER $$
DROP procedure IF EXISTS `create_invoice`$$

CREATE PROCEDURE `create_invoice`(
	IN inv_rem_idParam INT,
    IN todayDateParam DATE,
    IN todayDateTimeParam DATETIME,
    IN invExpiryParam DATE,
    Out isexecutedOut INT,
    OUT invIdOut INT
)
BEGIN
	DECLARE ledgerNoVar INT;
	DECLARE customerNoVar INT;
	DECLARE invIdVar INT;
	DECLARE invNoVar VARCHAR(50);
	DECLARE cgstVar, sgstVar, igstVar,invAmtVar,taxAmtVar,totalAmtVar FLOAT;
	DECLARE customerNameVar,productNameVar VARCHAR(100);
	DECLARE prodIdVar, invTypeVar INT;
	DECLARE descriptionVar,descVar VARCHAR (350);
	DECLARE prodChar,subChar VARCHAR (3);
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
		SELECT max(invoiceid) into invIdVar from speed.invoice;
        SET invIdVar = invIdVar + 1;
        
        SELECT customerno,ledgerno INTO customerNoVar,ledgerNoVar from invoice_reminders WHERE inv_rem_id = inv_rem_idParam;
        
        SELECT customerCompany into customerNameVar FROM customer where customerno = customerNoVar;

		SELECT i.productId,i.contract_type,p.name,i.inv_amt Into prodIdVar,invTypeVar,productNameVar,invAmtVar
			FROM invoice_reminders i
            LEFT JOIN speed.product p ON p.id = i.productId 
            WHERE inv_rem_id = inv_rem_idParam;
            
        SET descriptionVar = productNameVar;
        
        CASE WHEN prodIdVar = 3
			THEN SET prodChar = 'EE';
		WHEN prodIdVar = 11
			THEN SET prodChar = 'EC';
		END CASE;
        
        CASE WHEN invTypeVar = 1
			THEN 
				SET subChar = 'O';
				SET descriptionVar = CONCAT(descriptionVar,' Development charges');
		WHEN invTypeVar = 2
			THEN SET subChar = 'A';
            SET descriptionVar = CONCAT(descriptionVar,' AMC');
		WHEN invTypeVar = 3
			THEN SET subChar = 'S';
            SET descriptionVar = CONCAT(descriptionVar,' Subscription charges');
        END CASE;
        
        SET invNoVar = CONCAT(prodChar,subChar,customerNoVar,ledgerNoVar,invIdVar);
        
        CALL calculate_taxes(ledgerNoVar,invAmtVar,@cgstOut,@sgstOut,@igstOut);
        SELECT @cgstOut,@sgstOut,@igstOut into cgstVar, sgstVar, igstVar;
        
        SET taxAmtVar = cgstVar + sgstVar + igstVar;
		SET totalAmtVar = invAmtVar + taxAmtVar;
        
        INSERT INTO `speed`.`invoice`
			(`invoiceno`,`customerno`,`ledgerid`,`clientname`,`inv_date`,`inv_amt`,
			`status`,`pending_amt`,`tax`,`tax_amt`,`paid_amt`,`tds_amt`,`unpaid_amt`,`inv_expiry`,
			`comment`,`isdeleted`,`product_id`,`customername`
            ,`start_date`,`end_date`,
			`timestamp`,`is_mail_sent`,`quantity`,`cgst`,`sgst`,`igst`)
			
			SELECT invNoVar,`customerno`,`ledgerno`,customerNameVar,`todayDateParam`,totalAmtVar,
			'Pending',totalAmtVar,4,0,0,0,0,invExpiryParam,
			descriptionVar,0,`productId`,customerNameVar
            ,`start_date`,`end_date`,
			todayDateTimeParam,0,1,cgstVar,sgstVar,igstVar
			FROM elixiatech.invoice_reminders WHERE inv_rem_id = inv_rem_idParam;
        
		SET  isexecutedOut = 1;
        UPDATE `elixiatech`.`invoice_reminders` 
			SET invoiceid = invIdVar
		WHERE inv_rem_id = inv_rem_idParam;
        SET invIdOut = invIdVar;
        select invIdOut;
		END;
		COMMIT;
END$$

DELIMITER ;


