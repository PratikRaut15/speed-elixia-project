CREATE TABLE IF NOT EXISTS `leftoverdetails_history` (
`hid` int(11) NOT NULL,
`leftoverid` int(11) NOT NULL,
  `factoryid` int(11) NOT NULL,
  `depotid` int(11) NOT NULL,
  `weight` decimal(6,2) NOT NULL,
  `volume` decimal(6,2) NOT NULL,
  `daterequired` date NOT NULL,
  `isProcessed` tinyint(1) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);


ALTER TABLE `leftoverdetails_history`
 ADD PRIMARY KEY (`hid`), ADD KEY `index_customerno` (`customerno`), ADD KEY `index_factoryid` (`factoryid`);
 
 ALTER TABLE `leftoverdetails_history`
MODIFY `hid` int(11) NOT NULL AUTO_INCREMENT;




DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_unprocessed_leftoverdetails_history`$$
CREATE PROCEDURE `insert_unprocessed_leftoverdetails_history`(
        IN custno INT
        , IN daterequiredparam DATE
)
BEGIN

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    
    START TRANSACTION;
    
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;

    INSERT INTO leftoverdetails_history(
    leftoverid,
    factoryid,
    depotid,
    weight,
    volume,
    daterequired,
    isProcessed,
    customerno,
    created_on,
    updated_on,
    created_by,
    updated_by,
    isdeleted
    )
     SELECT 	
	leftoverid,
    factoryid,
    depotid,
    weight,
    volume,
    daterequired,
    isProcessed,
    customerno,
    created_on,
    updated_on,
    created_by,
    updated_by,
    isdeleted
	FROM    leftoverdetails 
    WHERE (customerno = custno OR custno IS NULL)
    AND ((daterequired < daterequiredparam) OR daterequiredparam IS NULL)
    AND isProcessed = 0
    AND   isdeleted = 0;

	call delete_unprocessed_leftoverdetails(custno,daterequiredparam);

	COMMIT;
    
END$$
DELIMITER ;





DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_unprocessed_leftoverdetails`$$
CREATE PROCEDURE `delete_unprocessed_leftoverdetails`(
        IN custno INT
        , IN daterequiredparam DATE
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;

    DELETE FROM leftoverdetails 
    WHERE (customerno = custno OR custno IS NULL)
    AND ((daterequired < daterequiredparam) OR daterequiredparam IS NULL)
    AND   isProcessed = 0
    AND   isdeleted = 0;
END$$
DELIMITER ;




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 13, NOW(), 'Shrikant Suryawanshi','Insert Unprocessed Leftover Details History');
