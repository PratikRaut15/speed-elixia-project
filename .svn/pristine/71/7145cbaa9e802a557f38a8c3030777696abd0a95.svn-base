SET @patchId = 745;
SET @patchDate = '2020-01-03 15:01:00';
SET @patchOwner = 'Arvind Thakur';
SET @patchDescription = 'route summary API SP changes';

INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

DROP procedure IF EXISTS `fetch_data_for_route_wise_report`;
DELIMITER $$
CREATE  PROCEDURE `fetch_data_for_route_wise_report`(
    IN `routeIdParam` INT
    , IN `vehicleidParam` INT
    , IN `customerNoParam` INT)
    COMMENT 'This routine is used to fetch the data for routewise report'
BEGIN

    DECLARE isReverseRouteVar TINYINT;
    DECLARE isReverseIdVar INT;
    DECLARE vehicleRouteIdVar INT;
    DECLARE vehicleReverseRouteIdVar INT;
    DECLARE routeDirectionVar TINYINT;
    DECLARE vehiclenoVar VARCHAR(40);

    IF (routeIdParam = 0) THEN
        SET routeIdParam = NULL;
    END IF;
    
    IF (vehicleidParam = 0) THEN
        SET vehicleidParam = NULL;
    END IF;

    IF (vehicleidParam IS NOT NULL) THEN

        SELECT  a.routeid
                , d.routeDirection
                , d.vehicleno
        INTO    vehicleRouteIdVar
                , routeDirectionVar
                , vehiclenoVar
        FROM    route AS a
        INNER JOIN vehiclerouteman AS b ON a.routeid = b.routeid
        INNER JOIN vehicle AS d ON d.vehicleid = b.vehicleid
        WHERE   a.isdeleted = 0 
        AND     d.vehicleid = vehicleidParam
        AND     b.isdeleted = 0
        AND     d.isdeleted = 0
        AND     a.customerno = customerNoParam
        AND     d.customerno = customerNoParam
        LIMIT   1; 

        IF (vehicleRouteIdVar IS NOT NULL) THEN

            SELECT  routeid 
            INTO    vehicleReverseRouteIdVar
            FROM    route 
            WHERE   parentRouteId = vehicleRouteIdVar
            AND     isdeleted = 0 
            LIMIT   1; 

            IF (routeDirectionVar = 1) THEN

                SELECT  a.isReverseRoute
                        , a.routeid
                        , a.routename
                        , b.vehicleid
                        , d.vehicleno
                        , c.checkpointid
                        , c.sequence
                        , ch.cname
                        , c.eta
                        , c.etd
                        , c.kmFromLastCheckpoint
                        , a.routeTat
                        , ch.cgeolat
                        , ch.cgeolong
                FROM    route AS a
                LEFT JOIN vehiclerouteman AS b ON a.routeid = b.routeid
                LEFT JOIN routeman AS c ON a.routeid = c.routeid
                LEFT JOIN vehicle AS d ON d.vehicleid = b.vehicleid
                LEFT JOIN checkpoint AS ch ON c.checkpointid = ch.checkpointid    
                WHERE   a.isdeleted = 0 
                AND     (a.routeid = vehicleRouteIdVar)
                AND     (d.vehicleid = vehicleidParam)
                AND     b.isdeleted = 0
                AND     c.isdeleted = 0
                AND     d.isdeleted = 0
                AND     a.customerno = customerNoParam
                AND     d.customerno = customerNoParam
                ORDER BY a.routeid , b.vehicleid,c.sequence;
                    

            ELSE IF (routeDirectionVar = 2) THEN

                    SELECT  a.isReverseRoute
                            , a.routeid
                            , a.routename
                            , vehicleidParam AS vehicleid
                            , vehiclenoVar AS vehicleno
                            , c.checkpointid
                            , c.sequence
                            , ch.cname
                            , c.eta
                            , c.etd
                            , c.kmFromLastCheckpoint
                            , a.routeTat
                            , ch.cgeolat
                            , ch.cgeolong
                    FROM    route AS a
                    LEFT JOIN vehiclerouteman AS b ON a.routeid = b.routeid
                    LEFT JOIN routeman AS c ON a.routeid = c.routeid
                    LEFT JOIN vehicle AS d ON d.vehicleid = b.vehicleid
                    LEFT JOIN checkpoint AS ch ON c.checkpointid = ch.checkpointid    
                    WHERE   a.isdeleted = 0 
                    AND     (a.routeid = vehicleReverseRouteIdVar)
                    AND     c.isdeleted = 0
                    AND     a.customerno = customerNoParam
                    ORDER BY a.routeid , b.vehicleid,c.sequence;

            END IF;
            END IF;

        END IF;

    ELSE 

        SELECT  isReverseRoute 
        INTO    isReverseRouteVar
        FROM    route 
        WHERE   routeid = routeIdParam
        LIMIT   1; 

        IF (isReverseRouteVar != '1') THEN

            SELECT  isReverseRouteVar AS isReverseRoute
                    ,a.routeid
                    , a.routename
                    , b.vehicleid
                    , d.vehicleno
                    , c.checkpointid
                    , c.sequence
                    , ch.cname
                    , c.eta
                    , c.etd
                    , c.kmFromLastCheckpoint
                    , a.routeTat
                    , ch.cgeolat
                    , ch.cgeolong
            FROM    route AS a
            LEFT JOIN vehiclerouteman AS b ON a.routeid = b.routeid
            LEFT JOIN routeman AS c ON a.routeid = c.routeid
            LEFT JOIN vehicle AS d ON d.vehicleid = b.vehicleid
            LEFT JOIN checkpoint AS ch ON c.checkpointid = ch.checkpointid    
            WHERE   a.isdeleted = 0 
            AND     a.routeid = routeIdParam
            AND     b.isdeleted = 0
            AND     c.isdeleted = 0
            AND     d.isdeleted = 0
            AND     a.customerno = customerNoParam
            AND     d.customerno = customerNoParam
            ORDER BY a.routeid , b.vehicleid,c.sequence; 

        ELSE

            SELECT  parentRouteId 
            INTO    isReverseIdVar
            FROM    route 
            WHERE   routeid = routeIdParam
            LIMIT   1; 
            /*SET @fetchRouteId = (SELECT parentRouteId FROM route WHERE routeid=routeIdParam);	*/
            #SELECT  @fetchRouteId; 
            /* Variable set for checkpointData starts here */

            /* Variable set for checkpointData ends here */
            /* SELECT @checkPointDataBySequence; */
            SELECT  isReverseRouteVar AS isReverseRoute
                    , a.routeid
                    , a.routename
                    , b.vehicleid
                    , d.vehicleno
                    , c.checkpointid
                    , c.sequence
                    , c.kmFromLastCheckpoint
                    , ch.cname
                    , c.eta
                    , c.etd
                    , a.routeTat
                    , ch.cgeolat
                    , ch.cgeolong
            FROM    route AS a
            LEFT JOIN vehiclerouteman AS b ON a.routeid = b.routeid
            LEFT JOIN routeman AS c ON a.routeid = c.routeid
            LEFT JOIN vehicle AS d ON d.vehicleid = b.vehicleid
            LEFT JOIN checkpoint AS ch ON c.checkpointid = ch.checkpointid    
            WHERE   a.isdeleted = 0 
            AND     (a.routeid = isReverseIdVar)
            AND     b.isdeleted = 0
            AND     c.isdeleted = 0
            AND     d.isdeleted = 0
            AND     a.customerno = customerNoParam
            AND     d.customerno = customerNoParam
            ORDER BY a.routeid , b.vehicleid,c.sequence; 

        END IF;

    END IF;

END$$
DELIMITER ;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
