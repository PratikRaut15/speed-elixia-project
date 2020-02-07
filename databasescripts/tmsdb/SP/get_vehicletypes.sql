DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicletypes`$$
CREATE PROCEDURE `get_vehicletypes`(
	IN custno INT
	,IN vehtypeid INT
	,IN sku_typeid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(vehtypeid = '' OR vehtypeid = 0) THEN
		SET vehtypeid = NULL;
	END IF;
IF(sku_typeid = '' OR sku_typeid = 0) THEN
		SET sku_typeid = NULL;
	END IF;
	SELECT 	vehicletypeid
			, vehiclecode
            , vehicledescription
	    , v.skutypeid
	    , st.type
            , volume
			, weight
			, v.customerno
			, v.created_on
			, v.updated_on
   FROM vehicletype as v
   INNER JOIN skutypes as st ON st.tid = v.skutypeid
   WHERE (v.customerno = custno OR custno IS NULL)
   AND (v.vehicletypeid = vehtypeid OR vehtypeid IS NULL)
   AND (v.skutypeid = sku_typeid OR sku_typeid IS NULL)
   AND	v.isdeleted = 0 order by ispreferred DESC, volume DESC;	
END$$
DELIMITER ;

