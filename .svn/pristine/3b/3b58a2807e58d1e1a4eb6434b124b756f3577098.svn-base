
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'475', '2017-03-06 14:14:04', 'Shrikant Suryawanshi', 'add eta time to routeman table for route tat summary report', '0'
);


ALTER TABLE `unit` CHANGE `unitno` `unitno` VARCHAR(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;


ALTER TABLE `routeman` ADD `eta` TIME NULL DEFAULT NULL AFTER `etaStatus`, ADD `etd` TIME NULL DEFAULT NULL AFTER `eta`, ADD `r_eta` TIME NULL DEFAULT NULL AFTER `etd`, ADD `r_etd` TIME NULL DEFAULT NULL AFTER `r_eta`;



UPDATE routeman SET etd = '03:00:00', r_eta = '04:00:00' WHERE routeid = 198 AND checkpointid = 1351 and isdeleted = 0;
UPDATE routeman SET eta = '06:00:00', etd='07:00:00', r_eta='23:00:00', r_etd='24:00:00' WHERE routeid = 198 AND checkpointid = 1361 and isdeleted = 0;
UPDATE routeman SET eta = '13:00:00', etd='14:00:00', r_eta='16:00:00', r_etd='17:00:00' WHERE routeid = 198 AND checkpointid = 1353 and isdeleted = 0;
UPDATE routeman SET eta = '20:00:00', etd='21:00:00', r_eta='09:00:00', r_etd='10:00:00' WHERE routeid = 198 AND checkpointid = 1359 and isdeleted = 0;
UPDATE routeman SET eta = '09:00:00', etd='', r_eta='', r_etd='11:00:00' WHERE routeid = 198 AND checkpointid = 1311 and isdeleted = 0;


UPDATE routeman SET eta = '', etd='04:00:00', r_eta='02:00:00', r_etd='' WHERE routeid =200  AND checkpointid =1351  and isdeleted = 0;
UPDATE routeman SET eta = '09:00:00', etd='10:00:00', r_eta='20:00:00', r_etd='21:00:00' WHERE routeid =200  AND checkpointid =1352  and isdeleted = 0;
UPDATE routeman SET eta = '21:00:00', etd='22:00:00', r_eta='08:00:00', r_etd='09:00:00' WHERE routeid =200  AND checkpointid =1355  and isdeleted = 0;
UPDATE routeman SET eta = '18:00:00', etd='19:00:00', r_eta='11:00:00', r_etd='12:00:00' WHERE routeid =200  AND checkpointid =1360  and isdeleted = 0;
UPDATE routeman SET eta = '01:00:00', etd='', r_eta='', r_etd='05:00:00' WHERE routeid =200  AND checkpointid =1362  and isdeleted = 0;


UPDATE routeman SET eta = '', etd='06:00:00', r_eta='20:00:00', r_etd='' WHERE routeid =197  AND checkpointid =1351  and isdeleted = 0;
UPDATE routeman SET eta = '10:00:00', etd='11:00:00', r_eta='19:00:00', r_etd='20:00:00' WHERE routeid =197  AND checkpointid =1354  and isdeleted = 0;
UPDATE routeman SET eta = '16:00:00', etd='17:00:00', r_eta='13:00:00', r_etd='14:00:00' WHERE routeid =197  AND checkpointid =1356  and isdeleted = 0;
UPDATE routeman SET eta = '09:00:00', etd='', r_eta='', r_etd='20:00:00' WHERE routeid =197  AND checkpointid =1020  and isdeleted = 0;


UPDATE routeman SET eta = '', etd='23:00:00', r_eta='04:00:00', r_etd='' WHERE routeid =250  AND checkpointid =1020  and isdeleted = 0;
UPDATE routeman SET eta = '14:00:00', etd='15:00:00', r_eta='11:00:00', r_etd='12:00:00' WHERE routeid =250  AND checkpointid =4961  and isdeleted = 0;
UPDATE routeman SET eta = '', etd='', r_eta='', r_etd='' WHERE routeid =250  AND checkpointid =4960  and isdeleted = 0;
UPDATE routeman SET eta = '21:00:00', etd='22:00:00', r_eta='04:00:00', r_etd='05:00:00' WHERE routeid =250  AND checkpointid =1365  and isdeleted = 0;
UPDATE routeman SET eta = '14:00:00', etd='15:00:00', r_eta='10:00:00', r_etd='11:00:00' WHERE routeid =250  AND checkpointid =1360  and isdeleted = 0;
UPDATE routeman SET eta = '20:00:00', etd='', r_eta='', r_etd='04:00:00' WHERE routeid =250  AND checkpointid =1362  and isdeleted = 0;



UPDATE routeman SET eta = '', etd='00:00:00', r_eta='12:00:00', r_etd='' WHERE routeid =180  AND checkpointid =1020  and isdeleted = 0;
UPDATE routeman SET eta = '14:00:00', etd='15:00:00', r_eta='16:00:00', r_etd='22:00:00' WHERE routeid =180  AND checkpointid =1312  and isdeleted = 0;
UPDATE routeman SET eta = '09:00:00', etd='', r_eta='', r_etd='22:00:00' WHERE routeid =180  AND checkpointid =1311  and isdeleted = 0;



UPDATE routeman SET eta = '', etd='00:00:00', r_eta='06:00:00', r_etd='' WHERE routeid =206  AND checkpointid =1362  and isdeleted = 0;
UPDATE routeman SET eta = '21:00:00', etd='23:00:00', r_eta='07:00:00', r_etd='09:00:00' WHERE routeid =206  AND checkpointid =1365  and isdeleted = 0;
UPDATE routeman SET eta = '00:00:00', etd='', r_eta='', r_etd='06:00:00' WHERE routeid =206  AND checkpointid =1311  and isdeleted = 0;



UPDATE routeman SET eta = '', etd='20:00:00', r_eta='06:00:00', r_etd='' WHERE routeid =572  AND checkpointid =1340  and isdeleted = 0;
UPDATE routeman SET eta = '10:00:00', etd='11:00:00', r_eta='08:00:00', r_etd='09:00:00' WHERE routeid =572  AND checkpointid =6102  and isdeleted = 0;
UPDATE routeman SET eta = '21:00:00', etd='22:00:00', r_eta='21:00:00', r_etd='22:00:00' WHERE routeid =572  AND checkpointid =6105  and isdeleted = 0;
UPDATE routeman SET eta = '14:00:00', etd='', r_eta='', r_etd='06:00:00' WHERE routeid =572  AND checkpointid =5996  and isdeleted = 0;





UPDATE  dbpatches
SET     patchdate = '2017-03-06 14:14:04'
        ,isapplied =1
WHERE   patchid = 475;


