INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('502', '2017-05-16 11:29:00', 'Arvind Thakur', 'Invoice Task', '0');

DROP table `proforma_invoice`;

CREATE TABLE IF NOT EXISTS `proforma_invoice` (
`pi_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `invoiceno` varchar(40) NOT NULL,
  `customerno` int(11) NOT NULL,
  `ledgerid` int(11) NOT NULL,
  `clientname` varchar(40) NOT NULL,
  `inv_date` date NOT NULL,
  `invtype` TINYINT(2),
  `inv_amt` float NOT NULL,
  `status` varchar(40) NOT NULL,
  `tax` varchar(40) NOT NULL,
  `tax_amt` float NOT NULL,
  `paymentdate` date NOT NULL,
  `tds_amt` float NOT NULL,
  `inv_expiry` date NOT NULL,
  `comment` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `product_id` int(11) DEFAULT '0',
  `is_taxed` TINYINT(1) DEFAULT '0',
  `approved` TINYINT(1) DEFAULT '0',
  `finaldate` DATE
);


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_proforma_invoice`$$
CREATE PROCEDURE `insert_proforma_invoice`( 
     IN invnoParam VARCHAR(40)
    ,IN customernoParam INT(11)
    ,IN ledgeridParam INT(11)
    ,IN customercompanyParam VARCHAR(40)
    ,IN invdateParam DATE
    ,IN invtypeParam TINYINT(2)
    ,IN grandtotalParam float
    ,IN statusParam VARCHAR(40)
    ,IN taxnameParam VARCHAR(40)
    ,IN taxParam float
    ,IN duedateParam DATE
    ,IN commentParam VARCHAR(50)
    ,IN finaldateParam DATE
    ,IN poParam INT(11)
    ,IN productidParam INT(11)
    ,IN description1Param VARCHAR(255)
    ,IN description2Param VARCHAR(255)
    ,IN description3Param VARCHAR(255)
    ,IN description4Param VARCHAR(255)
    ,IN description5Param VARCHAR(255)
    ,IN descriptionOther1Param VARCHAR(255)
    ,IN descriptionOther2Param VARCHAR(255)
    ,IN descriptionOther3Param VARCHAR(255)
    ,IN descriptionOther4Param VARCHAR(255)
    ,IN descriptionOther5Param VARCHAR(255)
    ,IN quantity1Param INT(5)
    ,IN price1Param INT(8)
    ,IN quantity2Param INT(5)
    ,IN price2Param INT(8)
    ,IN quantity3Param INT(5)
    ,IN price3Param INT(8)
    ,IN quantity4Param INT(5)
    ,IN price4Param INT(8)
    ,IN quantity5Param INT(5)
    ,IN price5Param INT(8)
    ,IN quantityOther1Param INT(5)
    ,IN priceOther1Param INT(8)
    ,IN quantityOther2Param INT(5)
    ,IN priceOther2Param INT(8)
    ,IN quantityOther3Param INT(5)
    ,IN priceOther3Param INT(8)
    ,IN quantityOther4Param INT(5)
    ,IN priceOther4Param INT(8)
    ,IN quantityOther5Param INT(5)
    ,IN priceOther5Param INT(8)
    ,OUT isexecutedOut TINYINT(1)
    ,OUT pi_idOut INT(11)
)
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

		SET isexecutedOut = 0;
        
        
        START TRANSACTION;
        BEGIN

            INSERT INTO proforma_invoice (`invoiceno`,
                `customerno`,
                `ledgerid`,
                `clientname`,
                `inv_date`,
                `invtype`,
                `inv_amt`,
                `status`,
                `tax`,
                `tax_amt`,
                `inv_expiry`,
                `comment`,
                `product_id`,
                `is_taxed`,
                `approved`,
                `finaldate`)
            VALUES (invnoParam
                ,customernoParam
                ,ledgeridParam
                ,customercompanyParam
                ,invdateParam
                ,invtypeParam
                ,grandtotalParam
                ,statusParam
                ,taxnameParam
                ,taxParam
                ,duedateParam
                ,commentParam
                ,productidParam
                ,0
                ,0
                ,finaldateParam);

			SELECT  LAST_INSERT_ID() 
			INTO    pi_idOut;

            INSERT INTO proforma_details(`pi_id`,
                    `po`,
                    `description1`,
                    `description2`,
                    `description3`,
                    `description4`,
                    `description5`,
                    `descriptionOther1`,
                    `descriptionOther2`,
                    `descriptionOther3`,
                    `descriptionOther4`,
                    `descriptionOther5`,
                    `quantity1`,
                    `price1`,
                    `quantity2`,
                    `price2`,
                    `quantity3`,
                    `price3`,
                    `quantity4`,
                    `price4`,
                    `quantity5`,
                    `price5`,
                    `quantityOther1`,
                    `priceOther1`,
                    `quantityOther2`,
                    `priceOther2`,
                    `quantityOther3`,
                    `priceOther3`,
                    `quantityOther4`,
                    `priceOther4`,
                    `quantityOther5`,
                    `priceOther5`) 
            VALUES( pi_idOut,
                    poParam,
                    description1Param,
                    description2Param,
                    description3Param,
                    description4Param,
                    description5Param,
                    descriptionOther1Param,
                    descriptionOther2Param,
                    descriptionOther3Param,
                    descriptionOther4Param,
                    descriptionOther5Param,
                    quantity1Param,
                    price1Param,
                    quantity2Param,
                    price2Param,
                    quantity3Param,
                    price3Param,
                    quantity4Param,
                    price4Param,
                    quantity5Param,
                    price5Param,
                    quantityOther1Param,
                    priceOther1Param,
                    quantityOther2Param,
                    priceOther2Param,
                    quantityOther3Param,
                    priceOther3Param,
                    quantityOther4Param,
                    priceOther4Param,
                    quantityOther5Param,
                    priceOther5Param);

            SET isexecutedOut = 1;
        END;
        COMMIT;

END$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `proforma_details`(
    `pi_id` INT(11) PRIMARY KEY AUTO_INCREMENT
    ,po INT(11)
    ,misc1quantity INT(5)
    ,misc1price INT(8)
    ,misc2quantity INT(5)
    ,misc2price INT(8)
    ,description1 VARCHAR(255)
    ,description2 VARCHAR(255)
    ,description3 VARCHAR(255)
    ,description4 VARCHAR(255)
    ,description5 VARCHAR(255)
    ,descriptionOther1 VARCHAR(255)
    ,descriptionOther2 VARCHAR(255)
    ,descriptionOther3 VARCHAR(255)
    ,descriptionOther4 VARCHAR(255)
    ,descriptionOther5 VARCHAR(255)
    ,misc1 tinyint(1)
    ,misc1text VARCHAR(255)
    ,misc2 tinyint(1)
    ,misc2text VARCHAR(255)
    ,quantity1 INT(5)
    ,price1 INT(8)
    ,quantity2 INT(5)
    ,price2 INT(8)
    ,quantity3 INT(5)
    ,price3 INT(8)
    ,quantity4 INT(5)
    ,price4 INT(8)
    ,quantity5 INT(5)
    ,price5 INT(8)
    ,quantityOther1 INT(5)
    ,priceOther1 INT(8)
    ,quantityOther2 INT(5)
    ,priceOther2 INT(8)
    ,quantityOther3 INT(5)
    ,priceOther3 INT(8)
    ,quantityOther4 INT(5)
    ,priceOther4 INT(8)
    ,quantityOther5 INT(5)
    ,priceOther5 INT(8));


alter table `invoice`
add column `proforma_id` INT(11);


CREATE TABLE IF NOT EXISTS `proforma_vehicle_mapping` (
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT,
	`pi_id` INT(11),
	`vehicleid` INT(11),
	`customerno` INT(11)
	`created_by` INT(11),
	`created_on` DATETIME,
	`isdeleted` TINYINT(1) DEFAULT 0
);

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 495;