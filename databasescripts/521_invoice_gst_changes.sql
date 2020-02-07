INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('521', '2017-07-08 14:30:00','Arvind Thakur','invoice GST changes', '0');


ALTER TABLE `unit`
ADD COLUMN `consignee_id` INT(11);

ALTER TABLE `customer`
ADD COLUMN `device_disc` INT(11) DEFAULT 0,
ADD COLUMN `subsc_disc` INT(11) DEFAULT 0,
ADD COLUMN `soft_disc` INT(11) DEFAULT 0;


CREATE TABLE IF NOT EXISTS `unitconsignee` (
    `consid` INT(11) NOT NULL primary key auto_increment,
    `consigneename` VARCHAR(50) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `phone` VARCHAR(15) NOT NULL,
    `customerno` INT(11) NOT NULL,
    `created_by` INT(11) NOT NULL,
    `created_on` DATETIME NOT NULL,
    `updated_by` INT(11) NOT NULL,
    `updated_on` DATETIME NOT NULL,
    `isdeleted` TINYINT(1) NOT NULL DEFAULT '0'
);


ALTER TABLE `invoice` 
ADD COLUMN `cgst` INT(11) DEFAULT 0,
ADD COLUMN `sgst` INT(11) DEFAULT 0,
ADD COLUMN `igst` INT(11) DEFAULT 0
ADD COLUMN `misc` VARCHAR;


ALTER TABLE `otherinvoices`
ADD COLUMN `ledgerid` INT(11) AFTER `customerno`;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_veh_mapping`$$
CREATE PROCEDURE `get_ledger_veh_mapping`( 
    IN ledger_veh_mapidparam INT
    , IN customernoparam INT
    , IN ledgeridparam INT
    , IN vehiclenoparam VARCHAR(20)
)
BEGIN

    IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
     SET ledger_veh_mapidparam = NULL;
    END IF;

    IF(customernoparam = '' OR customernoparam = '0') THEN
     SET customernoparam = NULL;
    END IF;

    IF(vehiclenoparam = '' OR vehiclenoparam = '0') THEN
     SET vehiclenoparam = NULL;
    END IF;

    IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
     SET ledgeridparam = NULL;
    END IF;

    SELECT 	l.ledger_veh_mapid
            ,l.ledgerid
            ,l.vehicleid
            ,l.customerno
            ,v.vehicleno
            ,l.createdby
            ,l.createdon
            ,l.updatedby
            ,l.updatedon
            ,v.uid
    FROM    ledger_veh_mapping as l
    INNER JOIN vehicle as v ON l.vehicleid = v.vehicleid
    INNER JOIN unit as u ON u.vehicleid = v.vehicleid
    INNER JOIN devices as d ON d.uid = u.uid
    WHERE (l.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
    AND     (l.customerno = customernoparam OR customernoparam IS NULL)
    AND     (v.customerno = customernoparam OR customernoparam IS NULL)
    AND     (l.ledgerid = ledgeridparam OR ledgeridparam IS NULL)
    AND     (v.vehicleno LIKE CONCAT('%', vehiclenoparam, '%') OR vehiclenoparam IS NULL)
    AND     l.isdeleted = 0
    ORDER BY v.vehicleno ASC;

END$$
DELIMITER ;


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


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 521;