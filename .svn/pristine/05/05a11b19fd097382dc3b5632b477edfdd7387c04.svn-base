ALTER TABLE `invoice` ADD COLUMN `comment` VARCHAR(50) NOT NULL AFTER `inv_expiry`;



-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 381


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_po`$$
CREATE PROCEDURE `get_po`( 
IN customernoparam INT
,IN poidparam VARCHAR(11)
)

BEGIN
IF(customernoparam = '' OR customernoparam = '0') THEN
 SET customernoparam = NULL;
END IF;
IF(poidparam = '') THEN
 SET poidparam = NULL;
END IF;
SELECT
	poid
    ,pono
    ,podate
    ,poamount
    ,poexpiry
    ,description
    ,customerno
    ,createdby
    ,createdon
    ,updatedby
    ,updatedon
FROM po
WHERE 
(customerno = customernoparam OR customernoparam IS NULL)
AND (poid = poidparam OR poidparam IS NULL)
AND isdeleted = 0
;
END$$
DELIMITER ;;
