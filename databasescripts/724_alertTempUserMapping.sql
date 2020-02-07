SET @patchId = 724;
SET @patchDate = '2019-09-13 12:34:00';
SET @patchOwner = 'Pratik Raut';
SET @patchDescription = 'Inerting data into alertTempUserMapping Table';

INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

INSERT INTO alertTempUserMapping(uid
        , vehicleid
        , userid
        , temp1_intv
        , temp2_intv
        , temp3_intv
        , temp4_intv
        , customerno)
SELECT  uid
        , un.vehicleid
        , u.userid
        , temp1_intv
        , temp2_intv
        , temp3_intv
        , temp4_intv
        , u.customerno
FROM    `user` u
LEFT OUTER JOIN unit un ON un.customerno = u.customerno
LEFT OUTER JOIN vehiclewise_alert va ON va.customerno = un.customerno AND va.vehicleid = un.vehicleid AND va.userid = u.userid
WHERE   u.isdeleted = 0
AND     (u.temp_email = 1 OR u.temp_sms = 1 OR temp_telephone = 1 OR temp_mobilenotification = 1)
AND     un.trans_statusid NOT IN (10,22)
AND     va.temp_active = 1
AND     u.customerno = 421
AND     u.userid in (
'10090',
'10092',
'8995',
'10093',
'6624',
'6594',
'6591',
'10094',
'10113',
'10044',
'10095');


UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;





