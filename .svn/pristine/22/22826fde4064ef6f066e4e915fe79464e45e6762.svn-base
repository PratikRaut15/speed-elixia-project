DELIMITER $$
DROP PROCEDURE IF EXISTS update_transporteractualshare$$
CREATE PROCEDURE `update_transporteractualshare`( 
    IN transid INT
    , IN factid INT
    , IN zid INT
    , IN sharedwt decimal(11,3)
    , IN totalwt decimal(11,3)
    , IN sharepercent decimal(6,2)
    , IN actshareidparam INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
    IF (sharedwt IS NULL AND totalwt IS NULL) THEN
        UPDATE transporter_actualshare
        SET      transporterid = transid
                , factoryid = factid
                , zoneid = zid
                , actualpercent = sharepercent
                , updated_on = todaysdate
                , updated_by= userid
        WHERE   actshareid= actshareidparam 
        AND     customerno = custno
        AND		isdeleted = 0;
    ELSE
        BEGIN
            DECLARE actualsharepercent DECIMAL(5,2);
            DECLARE tempsharedwt decimal(11,3);
            DECLARE temptotalwt decimal(11,3);
             
            SELECT shared_weight, total_weight
            INTO    tempsharedwt, temptotalwt
            FROM    transporter_actualshare
            WHERE   transporterid = transid
            AND     factoryid = factid
            AND     zoneid = zid
            AND     customerno = custno
            AND		isdeleted = 0;
             
            SET     tempsharedwt = tempsharedwt + sharedwt;
            SET     temptotalwt = temptotalwt + totalwt;
             
            SET     actualsharepercent = (tempsharedwt/temptotalwt) * 100;
             
            UPDATE transporter_actualshare
            SET     shared_weight = tempsharedwt
                    ,total_weight = temptotalwt
                    , actualpercent = actualsharepercent
                    , updated_on = todaysdate
                    , updated_by= userid
            WHERE   transporterid = transid
            AND     factoryid = factid
            AND     zoneid = zid
            AND     customerno = custno
            AND		isdeleted = 0;
        END;
    END IF;
END$$
DELIMITER ;