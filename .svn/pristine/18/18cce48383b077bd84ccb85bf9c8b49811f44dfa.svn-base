DELIMITER $$
DROP PROCEDURE IF EXISTS `get_mapped_depots`$$
CREATE PROCEDURE `get_mapped_depots`(
	IN custno INT
    , IN did INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
   
	IF(did = '' OR did = 0) THEN
		SET did = NULL;
	END IF;
	SELECT 	md_mappingid
			, md.depotid
			, md.depotmappingid	
            , d.depotname
            , md.factoryid
			, f.factoryname
			, md.customerno
			, md.created_on
			, md.updated_on
			
			
   FROM multidepot_mapping as md
   INNER JOIN depot as d on d.depotid = md.depotid
   INNER JOIN factory as f on f.factoryid = md.factoryid
   WHERE (md.customerno = custno OR custno IS NULL)
   AND 	(md.depotid = did OR did IS NULL)
   AND 	md.isdeleted = 0;
END$$
DELIMITER ;
