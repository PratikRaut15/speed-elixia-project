DELIMITER $$
DROP PROCEDURE IF EXISTS get_skuweight$$
CREATE PROCEDURE get_skuweight(
    IN custno INT
    , IN did INT
    , IN factid INT
    , IN datereq datetime
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(did = '' OR did = 0) THEN
        SET did = NULL;
    END IF;
    IF(factid = '' OR factid = 0) THEN
        SET factid = NULL;
    END IF;
    IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
        SET datereq = NULL;
    END IF;
    SELECT  fd.skuid
			, s.skucode
			, s.sku_description
			, s.skutypeid
			, (s.weight * (COALESCE(s.netgross,0))) as unitweight
            , s.volume  as unitvolume
            , sum(fd.grossWeight) as skuweight
            , fd.date_required
            , fd.depotid
            , fd.factoryid
    FROM     factory_delivery fd INNER JOIN sku s ON fd.skuid = s.skuid
    WHERE    (fd.customerno = custno OR custno IS NULL)
    AND      (fd.factoryid = factid OR factid IS NULL)
    AND      (fd.depotid = did OR did IS NULL)
    AND      (fd.date_required = datereq OR datereq IS NULL)
    AND      (fd.isProcessed=0)
    AND      (fd.isdeleted=0)
    GROUP BY date_required,factoryid, depotid, skuid;

END$$
DELIMITER ;






-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (19, NOW(), 'Shrikant Suryawanshi','check isdeleted in get sku weight');
