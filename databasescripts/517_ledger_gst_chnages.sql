INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('517', '2017-06-28 17:04:00','Arvind Thakur','Ledger GST changes', '0');


CREATE TABLE `state_gst_code`(
    `codeid` INT PRIMARY KEY,
    `state` VARCHAR(50),
    `state_code` VARCHAR(10),
    `isdeleted` TINYINT(2) DEFAULT 0);

INSERT INTO `state_gst_code`(`state`,`codeid`,`state_code`)
VALUES  ('Jammu and Kashmir',1,'JK'),
        ('Himachal Pradesh',02,'HP'),
        ('Punjab',03,'PB'),
        ('Chandigarh',04,'CH'),
        ('Uttarakhand',05,'UT'),
        ('Haryana',06,'HR'),
        ('Delhi',07,'DL'),
        ('Rajasthan',08,'RJ'),
        ('Uttar Pradesh',09,'UP'),
        ('Bihar',10,'BH'),
        ('Sikkim',11,'SK'),
        ('Arunachal Pradesh',12,'AR'),
        ('Nagaland',13,'NL'),
        ('Manipur',14,'MN'),
        ('Mizoram',15,'MI'),
        ('Tripura',16,'TR'),
        ('Meghalaya',17,'ME'),
        ('Assam',18,'AS'),
        ('West Bengal',19,'WB'),
        ('Jharkhand',20,'JH'),
        ('Odisha',21,'OR'),
        ('Chattisgarh',22,'CT'),
        ('Madhya Pradesh',23,'MP'),
        ('Gujarat',24,'GJ'),
        ('Daman and Diu',25,'DD'),
        ('Dadra and Nagar Haveli',26,'DN'),
        ('Maharashtra',27,'MH'),
        ('Andhra Pradesh',28,'AP'),
        ('Karnataka',29,'KA'),
        ('Goa',30,'GA'),
        ('Lakshadweep Islands',31,'LD'),
        ('Kerala',32,'KL'),
        ('Tamil Nadu',33,'TN'),
        ('Pondicherry',34,'PY'),
        ('Andaman and Nicobar Islands',35,'AN'),
        ('Telangana',36,'TS'),
        ('Andhra Pradesh New',37,'AD');

ALTER TABLE `ledger`
ADD COLUMN `gst_no` VARCHAR(30) AFTER `pan_no`,
ADD COLUMN `state_code` INT(11) DEFAULT 27;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger`$$
CREATE PROCEDURE `get_ledger`(
    IN ledgeridparam INT
    , IN ledgernameparam VARCHAR(100)
)
BEGIN
    IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
            SET ledgeridparam = NULL;
    END IF;

    IF(ledgernameparam = '' OR ledgernameparam = '0') THEN
            SET ledgernameparam = NULL;
    END IF;

    SELECT  l.ledgerid
            ,l.ledgername
            ,l.address1
            ,l.address2
            ,l.address3
            ,l.state_code
            ,l.email
            ,l.phone
            ,l.pan_no
            ,l.gst_no
            ,l.cst_no
            ,l.st_no
            ,l.vat_no
            ,l.createdby
            ,l.createdon
            ,l.updatedby
            ,l.updatedon
            ,sgc.`state`
    FROM    ledger AS l
    INNER JOIN state_gst_code sgc ON sgc.codeid = l.state_code
    WHERE   (l.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
    AND     (TRIM(l.ledgername) = TRIM(ledgernameparam) OR ledgernameparam IS NULL)
    AND     l.isdeleted = 0
    ORDER BY l.ledgerid DESC;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_ledger`$$
CREATE PROCEDURE `update_ledger`( 
      IN ledgeridParam INT
    , IN ledgernameParam VARCHAR(100)
    , IN address1Param VARCHAR(100)
    , IN address2Param VARCHAR(100)
    , IN address3Param VARCHAR(100)
    , IN stateParam VARCHAR(100)
    , IN emailParam VARCHAR(40)
    , IN phoneParam VARCHAR(20)
    , IN pan_noParam VARCHAR(30)
    , IN gst_noParam VARCHAR(30)
    , IN cst_noParam VARCHAR(30)
    , IN st_noParam VARCHAR(30)
    , IN vat_noParam VARCHAR(30)
    , IN updatedbyParam INT
    , IN updatedonParam DATETIME
    , OUT isexecutedOut TINYINT(2)
)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
           /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;   */
            SET isexecutedOut = 0;
	END;
    BEGIN  

        SET isexecutedOut = 0;

        START TRANSACTION;	 
        BEGIN

            UPDATE  ledger 
            SET     ledgername = ledgernameParam
                    , address1 = address1Param 
                    , address2 = address2Param
                    , address3 = address3Param
                    , state_code = stateParam
                    , email = emailParam
                    , phone = phoneParam 
                    , pan_no = pan_noParam
                    , gst_no = gst_noParam
                    , cst_no = cst_noParam
                    , st_no = st_noParam
                    , vat_no = vat_noParam
                    , updatedby = updatedbyParam
                    , updatedon = updatedonParam
            WHERE   ledgerid = ledgeridParam 
            AND     isdeleted = 0;

            SET     isexecutedOut = 1;
            
        END;
        COMMIT; 
    
    END;
                
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger`$$
CREATE PROCEDURE `insert_ledger`( 
    IN ledgernameParam VARCHAR(100)
    , IN address1Param VARCHAR(100)
    , IN address2Param VARCHAR(100)
    , IN address3Param VARCHAR(100)
    , IN stateParam VARCHAR(100)
    , IN emailParam VARCHAR(40)
    , IN phoneParam VARCHAR(20)
    , IN pan_noParam VARCHAR(30)
    , IN gst_noParam VARCHAR(30)
    , IN cst_noParam VARCHAR(30)
    , IN st_noParam VARCHAR(30)
    , IN vat_noParam VARCHAR(30)
    , IN createdbyParam INT(11)
    , IN createdonParam DATETIME
    , IN updatedbyParam INT(11)
    , IN updatedonParam DATETIME
    , OUT isexecutedOut TINYINT(2)
    , OUT lastinsertidOut  INT(11)
)
BEGIN
    

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
			/* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
	END;
    BEGIN  

        SET isexecutedOut = 0;

        START TRANSACTION;	 
        BEGIN
            INSERT INTO ledger(ledgername
                , address1 
                , address2 
                , address3
                , state_code
                , email 
                , phone 
                , pan_no 
                , gst_no
                , cst_no 
                , st_no 
                , vat_no 
                , createdby 
                , createdon 
                , updatedby 
                , updatedon) 
            VALUES(ledgernameParam 
                , address1Param 
                , address2Param 
                , address3Param
                , stateParam
                , emailParam 
                , phoneParam 
                , pan_noParam 
                , gst_noParam
                , cst_noParam 
                , st_noParam 
                , vat_noParam 
                , createdbyParam 
                , createdonParam 
                , updatedbyParam 
                , updatedonParam);

            SELECT  LAST_INSERT_ID()
            INTO    lastinsertidOut; 

            SET isexecutedOut = 1;

        END;
        COMMIT; 
    
    END;
    
END$$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 517;