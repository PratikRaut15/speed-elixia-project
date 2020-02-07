-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------

DROP procedure IF EXISTS `fetch_data_for_route_wise_report_checkpoints`;
DELIMITER $$
CREATE  PROCEDURE `fetch_data_for_route_wise_report_checkpoints`(
    IN `routeIdParam` INT
    , IN `customerNoParam` INT)
    COMMENT 'This routine is used to fetch the data for routewise report'
BEGIN
    
    SELECT  a.routeid
            , a.routename
            , c.checkpointid
            , c.sequence
            , ch.cname
            , c.eta
            , c.etd
            , a.routeTat
            , ch.cgeolat
            , ch.cgeolong
            , c.kmFromLastCheckpoint
    FROM    route AS a
    LEFT JOIN routeman AS c ON a.routeid = c.routeid
    LEFT JOIN checkpoint AS ch ON c.checkpointid = ch.checkpointid    
    WHERE   a.isdeleted = 0 AND a.routeid = routeIdParam 
    AND     c.isdeleted = 0
    AND     a.customerno = customerNoParam
    ORDER BY a.routeid ,c.sequence;


END$$
DELIMITER ;