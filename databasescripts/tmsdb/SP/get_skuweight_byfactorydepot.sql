DELIMITER $$
DROP PROCEDURE IF EXISTS `get_skuweight_byfactorydepot`$$
CREATE PROCEDURE `get_skuweight_byfactorydepot`(
   IN custno INT
   , IN datereq datetime
   , IN factoryidparam INT
)
BEGIN
   IF(custno = '' OR custno = 0) THEN
       SET custno = NULL;
   END IF;
   IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
       SET datereq = NULL;
   END IF;
   IF(factoryidparam = '' OR factoryidparam = 0) THEN
       SET factoryidparam = NULL;
   END IF;
   IF (custno IS NOT NULL AND datereq IS NOT NULL) THEN
        BEGIN
            SELECT     sum(fd.grossWeight) as weight
                    , fd.date_required
                    , fd.factoryid
                    , fd.depotid
            FROM     factory_delivery fd
            WHERE   customerno = custno
            AND     fd.date_required = datereq
            AND     (fd.factoryid = factoryidparam OR factoryidparam IS NULL)
            AND     fd.isProcessed = 0
            AND     fd.depotid <> 0
            AND       fd.factoryid <> 0
            AND       fd.isdeleted = 0
            GROUP BY date_required, factoryid, depotid;
        END;
    END IF;
END$$
DELIMITER ;
