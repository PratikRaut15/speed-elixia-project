DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_tax_invoice`$$
CREATE PROCEDURE `insert_tax_invoice`( 
     IN invnoParam VARCHAR(40)
    ,IN customernoParam INT(11)
    ,IN ledgeridParam INT(11)
    ,IN inv_dateParam DATE
    ,IN inv_amtParam float
    ,IN statusParam VARCHAR(40)
    ,IN pending_amtParam float
    ,IN taxnameParam VARCHAR(40)
    ,IN cgstParam float
    ,IN sgstParam float
    ,IN igstParam float
    ,IN duedateParam DATE
    ,IN quantityParam INT(11)
    ,IN todaysdateParam DATETIME
    ,IN productidParam INT(11)
    ,IN startdateParam DATE
    ,IN enddateParam DATE
    ,IN miscParam VARCHAR(255)
    ,IN uidlistParam VARCHAR(255)
    ,OUT isexecutedOut TINYINT(1)
)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
          /*  GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;  */
            SET isexecutedOut = 0;
        END;

		SET isexecutedOut = 0;
        
        START TRANSACTION;
        BEGIN

            INSERT INTO invoice (`invoiceno`,
                `customerno`,
                `ledgerid`,
                `inv_date`,
                `inv_amt`,
                `status`,
                `pending_amt`,
                `tax`,
                `cgst`,
                `sgst`,
                `igst`,
                `inv_expiry`,
                `product_id`,
                `quantity`,
                `timestamp`,
                `start_date`,
                `end_date`,
                `is_mail_sent`,
                `misc`)
            VALUES (invnoParam
                ,customernoParam
                ,ledgeridParam
                ,inv_dateParam
                ,inv_amtParam
                ,statusParam
                ,pending_amtParam
                ,taxnameParam
                ,cgstParam
                ,sgstParam
                ,igstParam
                ,duedateParam
                ,productidParam
                ,quantityParam
                ,todaysdateParam
                ,startdateParam
                ,enddateParam
                ,1
                ,miscParam);

            UPDATE  `devices`
            SET     `device_invoiceno` = invnoParam
                    ,`inv_generatedate` = inv_dateParam
                    ,`start_date` = startdateParam
                    ,`end_date` = enddateParam
                    ,`expirydate` = duedateParam
            WHERE   find_in_set(`uid`,uidlistParam);

            SET isexecutedOut = 1;
        END;
        COMMIT;

END$$
DELIMITER ;