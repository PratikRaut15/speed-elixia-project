INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('14', '2019-01-25 19:30:00', 'Yash Kanakia','Item Master CRUD', '0');


DROP TABLE IF EXISTS `item_master`;
CREATE TABLE `item_master`(
imId INT PRIMARY KEY AUTO_INCREMENT,
item_name VARCHAR(100),
description VARCHAR(200),
hsn_code VARCHAR(50),
is_deleted tinyInt,
createdBy INT,
createdOn datetime,
updatedBy INT,
updatedOn datetime);

DELIMITER $$
DROP procedure IF EXISTS `insert_item_master`$$
CREATE PROCEDURE `insert_item_master`(

     IN itemNameParam VARCHAR(100)
    ,IN descriptionParam VARCHAR(200)
    ,IN hsnCodeParam VARCHAR(50)
    ,IN teamidParam INT(11)
    ,IN todayParam datetime
    ,OUT isExecustedOut INT
    ,OUT itemIdOut INT

)
BEGIN
DECLARE item_nameExists VARCHAR(100);
DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

        ROLLBACK;

        /*

        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

        SELECT @full_error;

        */

    END;

SET isExecustedOut = 0;
SET itemIdOut= 0;

SELECT item_name INTO item_nameExists from item_master where item_name = itemNameParam;

IF item_nameExists IS NULL THEN
INSERT INTO item_master (`item_name`, `description`, `hsn_code`, `is_deleted`,`createdBy`,`createdOn`) 

VALUES (itemNameParam,descriptionParam,hsnCodeParam,0,teamidParam,todayParam);

SET isExecustedOut = 1;
SET itemIdOut= LAST_INSERT_ID();

END IF;



END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetch_item_master`$$
CREATE PROCEDURE `fetch_item_master`(

    IN itemIdParam VARCHAR(20)

)
BEGIN
	IF(itemIdParam=0 OR itemIdParam IS NULL) THEN
		SET itemIdParam = NULL;
	END IF;
    
	SELECT im.* FROM item_master im
	WHERE imId = itemIdParam OR itemIdParam IS NULL;

END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `update_item_master`$$
CREATE PROCEDURE `update_item_master`(
	 IN itemIdParam INT
	,IN itemNameParam VARCHAR(100)
    ,IN descriptionParam VARCHAR(200)
    ,IN hsnCodeParam VARCHAR(50)
    ,IN teamidParam INT(11)
    ,IN todayParam datetime
    ,OUT isExecustedOut INT

)
BEGIN
DECLARE item_nameExists VARCHAR(100);
DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

        ROLLBACK;

        /*

        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

        SELECT @full_error;

        */

    END;

SET isExecustedOut = 0;

SELECT item_name INTO item_nameExists from item_master where item_name = itemNameParam and imId <> itemIdParam;

IF item_nameExists IS NULL THEN

UPDATE item_master 
SET 
    `item_name` = itemNameParam,
    `description` = descriptionParam,
    `hsn_code` = hsnCodeParam,
    `updatedBy` = teamidParam,
    `updatedOn` = todayParam
WHERE
    imId = itemIdParam;

SET isExecustedOut = 1;

END IF;



END$$

DELIMITER ;

DROP TABLE IF EXISTS `itemMasterDetails`;
CREATE TABLE `itemMasterDetails`(
im_detailsId INT PRIMARY KEY AUTO_INCREMENT,
customerno INT);

/*INSERT INTO `itemMasterDetails` (`customerno`)
SELECT DISTINCT customerno FROM speed.customer;*/

DELIMITER $$
DROP procedure IF EXISTS `insert_column_item_masterDetails`$$
CREATE PROCEDURE `insert_column_item_masterDetails`(

     IN itemNameParam VARCHAR(100)

)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

        ROLLBACK;

        /*

        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

        SELECT @full_error;

        */

    END;
    
		SET @STMT = CONCAT(" ALTER TABLE `itemMasterDetails` add column ", itemNameParam, " varchar(100) not null DEFAULT 0");
		PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S;


END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `update_column_item_masterDetails`$$
CREATE PROCEDURE `update_column_item_masterDetails`(

     IN newNameParam VARCHAR(100),
     IN oldNameParam VARCHAR(100)

)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

        ROLLBACK;

        

      /*  GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

        SELECT @full_error; */

        

    END;

		SET @STMT = CONCAT(" ALTER TABLE `itemMasterDetails` CHANGE COLUMN ", oldNameParam," ", newNameParam ," varchar(100)");
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S;


END$$

DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `fetch_item_master_details`$$
CREATE PROCEDURE `fetch_item_master_details`(
IN customerNoParam INT)
BEGIN

SELECT imd.* FROM itemMasterDetails imd
WHERE imd.customerno = customerNoParam;
END$$

DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `update_item_master_details`$$
CREATE PROCEDURE `update_item_master_details`(

     IN columnNameParam VARCHAR(100),
     IN valueParam INT,
     IN customerNoParam INT,
     OUT isExecutedOut INT

)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

        ROLLBACK;

        

      /*  GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

        SELECT @full_error; */

        

    END;
		SET isExecutedOut = 0;
        
		SET @STMT = CONCAT(" UPDATE `itemMasterDetails` SET ", columnNameParam,"=", valueParam ," WHERE customerno=",customerNoParam);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S;
		
        SET isExecutedOut = 1;

END$$

DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS insert_details_ItemMaster $$
CREATE TRIGGER `insert_details_ItemMaster` AFTER INSERT ON `customer`
FOR EACH ROW BEGIN
BEGIN
INSERT INTO itemMasterDetails
SET 
customerno = NEW.customerno;
END;
END $$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2019-01-25 19:30:00'
        ,isapplied =1
WHERE   patchid = 14;