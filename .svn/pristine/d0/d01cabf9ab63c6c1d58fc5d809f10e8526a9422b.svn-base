DELIMITER $$
DROP PROCEDURE IF EXISTS `pullBucketList`$$
CREATE PROCEDURE `pullBucketList`(
     IN dateParam VARCHAR(100))
BEGIN
        
        SELECT  b.bucketid
                , b.customerno
                , c.customercompany
                , b.priority
                , v.vehicleno
                , v.uid
                , b.location
                , b.purposeid
                , cp.person_name
                , cp.cp_phone1
                , t.`name` as fe_name
                , b.vehicleno as vehno
                , b.vehicleid
                , b.details
                , sp.timeslot
                , b.created_by
                , te.`name` as created_by_name
                , b.apt_date
                , b.fe_id
                , b.status
        FROM    bucket b
        INNER JOIN customer c ON c.customerno = b.customerno
        LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
        LEFT OUTER JOIN contactperson_details cp ON cp.cpdetailid = b.coordinatorid
        LEFT OUTER JOIN team t ON t.teamid = b.fe_id
        LEFT OUTER JOIN team te ON te.teamid = b.created_by
        LEFT OUTER JOIN sp_timeslot sp ON sp.tsid = b.timeslotid                                        
        LEFT OUTER JOIN unit u ON u.uid = b.unitid
        LEFT OUTER JOIN simcard s ON s.id = b.simcardid
        WHERE   b.apt_date LIKE dateParam  
        ORDER BY sp.tsid;

END$$
DELIMITER ;  

-- CALL pullBucketList('2017-02-02','2017-02-20');