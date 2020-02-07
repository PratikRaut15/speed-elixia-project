INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'581', '2018-07-20 13:00:00', 'Yash Kanakia', 'Count of devices in office', '0'
);

DROP TABLE IF EXISTS `unit_location`;
CREATE TABLE unit_location(
	unit_location_id INT AUTO_INCREMENT PRIMARY KEY
	,unit_location_box_number INT
	,unit_type VARCHAR(200)
	,createdOn DATETIME
	,createdBy INT
	,updatedOn DATETIME
	,updatedBy INT
	,isDeleted TINYINT(1)
	);

ALTER TABLE unit
ADD COLUMN unit_location_box_number INT DEFAULT 0;

INSERT into unit_location (unit_location_box_number,unit_type)
values('0','Temporary');
INSERT into unit_location (unit_location_box_number,unit_type)
values('1','Basic');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('2','IO');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('3','Single Temp');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('4','Double Temp');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('5','Basic + 1 Temp + Door + AC');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('6','Basic + Toggle Switch');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('7','Basic (No GPS) + 1 Temp');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('8','Basic (No GPS) + 2 Temp');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('9','Basic (No GPS) + 4 Temp');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('10','Basic + Buzzer + Panic + Immobilizer');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('11','Single Temp + AC + Hooter');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('12','Single Temp + Humidity');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('13','Basic + Single temp + Hooter');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('14','Basic + RFID');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('15','GT 06 N');
INSERT into unit_location (`unit_location_box_number`,`unit_type`)
values('16','Other');

UPDATE unit SET  unit_location_box_number=5 where unitno ='9800941';
UPDATE unit SET  unit_location_box_number=4 where unitno ='9801512';
UPDATE unit SET  unit_location_box_number=2 where unitno ='901982';
UPDATE unit SET  unit_location_box_number=2 where unitno ='903106';
UPDATE unit SET  unit_location_box_number=2 where unitno ='903550';
UPDATE unit SET  unit_location_box_number=2 where unitno ='905597';
UPDATE unit SET  unit_location_box_number=2 where unitno ='906505';
UPDATE unit SET  unit_location_box_number=2 where unitno ='906763';
UPDATE unit SET  unit_location_box_number=2 where unitno ='907238';
UPDATE unit SET  unit_location_box_number=2 where unitno ='907798';
UPDATE unit SET  unit_location_box_number=2 where unitno ='9802158';
UPDATE unit SET  unit_location_box_number=2 where unitno ='9804553';
UPDATE unit SET  unit_location_box_number=2 where unitno ='9804556';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1732010007893';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1736010012316';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1736010012472';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1821020032155';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1821020032189';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1822020032856';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1822020032906';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1822020032914';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1822020032955';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1822020032971';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1822020032989';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1822020032997';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1717010000329';
UPDATE unit SET  unit_location_box_number=3 where unitno ='1733010008857';
UPDATE unit SET  unit_location_box_number=4 where unitno ='1820010019347';
UPDATE unit SET  unit_location_box_number=4 where unitno ='1826010020425';
UPDATE unit SET  unit_location_box_number=4 where unitno ='1826010020599';
UPDATE unit SET  unit_location_box_number=5 where unitno ='908015';
UPDATE unit SET  unit_location_box_number=7 where unitno ='9804548';
UPDATE unit SET  unit_location_box_number=8 where unitno ='9804830';
UPDATE unit SET  unit_location_box_number=10 where unitno ='901874';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902115';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902117';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902168';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902175';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902186';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902419';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902422';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902587';
UPDATE unit SET  unit_location_box_number=10 where unitno ='902727';
UPDATE unit SET  unit_location_box_number=10 where unitno ='903235';
UPDATE unit SET  unit_location_box_number=10 where unitno ='903240';
UPDATE unit SET  unit_location_box_number=10 where unitno ='903243';
UPDATE unit SET  unit_location_box_number=10 where unitno ='904135';
UPDATE unit SET  unit_location_box_number=10 where unitno ='905900';
UPDATE unit SET  unit_location_box_number=10 where unitno ='905902';
UPDATE unit SET  unit_location_box_number=12 where unitno ='9802365';
UPDATE unit SET  unit_location_box_number=15 where unitno ='908097';
UPDATE unit SET  unit_location_box_number=15 where unitno ='908098';
UPDATE unit SET  unit_location_box_number=15 where unitno ='908111';
UPDATE unit SET  unit_location_box_number=15 where unitno ='908112';
UPDATE unit SET  unit_location_box_number=15 where unitno ='908113';
UPDATE unit SET  unit_location_box_number=15 where unitno ='908114';
UPDATE unit SET  unit_location_box_number=15 where unitno ='908115';
UPDATE unit SET  unit_location_box_number=15 where unitno ='1818010019267';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090351673';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090351756';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090351772';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090351855';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090352382';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090352440';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090352523';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090353026';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090353745';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090353794';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090354404';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090354636';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090355351';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090356094';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090360104';
UPDATE unit SET  unit_location_box_number=16 where unitno ='353701090360575';
UPDATE unit SET  unit_location_box_number=2 where unitno ='358739050574707';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1817020028227';
UPDATE unit SET  unit_location_box_number=17 where unitno ='99481';
UPDATE unit SET  unit_location_box_number=17 where unitno ='900157';
UPDATE unit SET  unit_location_box_number=17 where unitno ='906675';
UPDATE unit SET  unit_location_box_number=17 where unitno ='906676';
UPDATE unit SET  unit_location_box_number=17 where unitno ='906677';
UPDATE unit SET  unit_location_box_number=17 where unitno ='9801864';
UPDATE unit SET  unit_location_box_number=17 where unitno ='9802151';
UPDATE unit SET  unit_location_box_number=17 where unitno ='9802364';
UPDATE unit SET  unit_location_box_number=17 where unitno ='9804423';
UPDATE unit SET  unit_location_box_number=17 where unitno ='1722010004214';
UPDATE unit SET  unit_location_box_number=17 where unitno ='1722010004461';
UPDATE unit SET  unit_location_box_number=17 where unitno ='1722010004503';
UPDATE unit SET  unit_location_box_number=2 where unitno ='99830';
UPDATE unit SET  unit_location_box_number=2 where unitno ='903281';
UPDATE unit SET  unit_location_box_number=2 where unitno ='906361';
UPDATE unit SET  unit_location_box_number=2 where unitno ='9376';
UPDATE unit SET  unit_location_box_number=2 where unitno ='9862';
UPDATE unit SET  unit_location_box_number=2 where unitno ='99055';
UPDATE unit SET  unit_location_box_number=2 where unitno ='99233';
UPDATE unit SET  unit_location_box_number=2 where unitno ='99602';
UPDATE unit SET  unit_location_box_number=2 where unitno ='100196';
UPDATE unit SET  unit_location_box_number=2 where unitno ='100261';
UPDATE unit SET  unit_location_box_number=2 where unitno ='100281';
UPDATE unit SET  unit_location_box_number=2 where unitno ='100285';
UPDATE unit SET  unit_location_box_number=2 where unitno ='100294';
UPDATE unit SET  unit_location_box_number=2 where unitno ='900417';
UPDATE unit SET  unit_location_box_number=2 where unitno ='901633';
UPDATE unit SET  unit_location_box_number=2 where unitno ='901935';
UPDATE unit SET  unit_location_box_number=2 where unitno ='903196';
UPDATE unit SET  unit_location_box_number=2 where unitno ='903413';
UPDATE unit SET  unit_location_box_number=2 where unitno ='903420';
UPDATE unit SET  unit_location_box_number=2 where unitno ='903685';
UPDATE unit SET  unit_location_box_number=2 where unitno ='904704';
UPDATE unit SET  unit_location_box_number=2 where unitno ='905007';
UPDATE unit SET  unit_location_box_number=2 where unitno ='905008';
UPDATE unit SET  unit_location_box_number=2 where unitno ='905027';
UPDATE unit SET  unit_location_box_number=2 where unitno ='905674';
UPDATE unit SET  unit_location_box_number=2 where unitno ='905690';
UPDATE unit SET  unit_location_box_number=2 where unitno ='905721';
UPDATE unit SET  unit_location_box_number=2 where unitno ='906617';
UPDATE unit SET  unit_location_box_number=2 where unitno ='906940';
UPDATE unit SET  unit_location_box_number=2 where unitno ='907089';
UPDATE unit SET  unit_location_box_number=2 where unitno ='907146';
UPDATE unit SET  unit_location_box_number=2 where unitno ='907703';
UPDATE unit SET  unit_location_box_number=2 where unitno ='907715';
UPDATE unit SET  unit_location_box_number=2 where unitno ='907720';
UPDATE unit SET  unit_location_box_number=2 where unitno ='907739';
UPDATE unit SET  unit_location_box_number=2 where unitno ='9800660';
UPDATE unit SET  unit_location_box_number=2 where unitno ='9802112';
UPDATE unit SET  unit_location_box_number=2 where unitno ='9802409';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1726010006020';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1736010011565';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1739010014019';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1740010014223';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1740020008264';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1744010016147';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1801020013496';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1806010016722';
UPDATE unit SET  unit_location_box_number=2 where unitno ='1811020022736';
UPDATE unit SET  unit_location_box_number=7 where unitno ='906460';
UPDATE unit SET  unit_location_box_number=7 where unitno ='9804214';
UPDATE unit SET  unit_location_box_number=7 where unitno ='1811020022579';
UPDATE unit SET  unit_location_box_number=7 where unitno ='1814020025493';
UPDATE unit SET  unit_location_box_number=7 where unitno ='1814020026046';
UPDATE unit SET  unit_location_box_number=7 where unitno ='1818020028712';
UPDATE unit SET  unit_location_box_number=7 where unitno ='1818020028803';
UPDATE unit SET  unit_location_box_number=5 where unitno ='903073';
UPDATE unit SET  unit_location_box_number=5 where unitno ='903651';
UPDATE unit SET  unit_location_box_number=5 where unitno ='904153';
UPDATE unit SET  unit_location_box_number=5 where unitno ='904687';
UPDATE unit SET  unit_location_box_number=5 where unitno ='905165';
UPDATE unit SET  unit_location_box_number=5 where unitno ='905193';
UPDATE unit SET  unit_location_box_number=5 where unitno ='906986';
UPDATE unit SET  unit_location_box_number=5 where unitno ='907061';
UPDATE unit SET  unit_location_box_number=5 where unitno ='907764';
UPDATE unit SET  unit_location_box_number=5 where unitno ='9800937';
UPDATE unit SET  unit_location_box_number=16 where unitno ='1733010009251';
UPDATE unit SET  unit_location_box_number=16 where unitno ='1733010009335';
UPDATE unit SET  unit_location_box_number=16 where unitno ='1737010012793';
UPDATE unit SET  unit_location_box_number=4 where unitno ='99191';
UPDATE unit SET  unit_location_box_number=4 where unitno ='100782';
UPDATE unit SET  unit_location_box_number=4 where unitno ='901010';
UPDATE unit SET  unit_location_box_number=4 where unitno ='901218';
UPDATE unit SET  unit_location_box_number=4 where unitno ='902531';
UPDATE unit SET  unit_location_box_number=4 where unitno ='904558';
UPDATE unit SET  unit_location_box_number=4 where unitno ='904616';
UPDATE unit SET  unit_location_box_number=4 where unitno ='905175';
UPDATE unit SET  unit_location_box_number=4 where unitno ='907023';
UPDATE unit SET  unit_location_box_number=4 where unitno ='9801237';
UPDATE unit SET  unit_location_box_number=4 where unitno ='1808010017080';
UPDATE unit SET  unit_location_box_number=4 where unitno ='1811010017779';
UPDATE unit SET  unit_location_box_number=4 where unitno ='1825010020383';
UPDATE unit SET  unit_location_box_number=2 where unitno ='100694';

USE `speed`;
DROP procedure IF EXISTS `get_unit_location`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_unit_location`()
BEGIN
 
    select 	* from unit_location;


END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_device_loctn_count`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_device_loctn_count`(
IN location_idParam INT)
BEGIN
DECLARE id INT;
SELECT  GROUP_CONCAT(unitno SEPARATOR ', ')as unitno,CONCAT('BOX ',dl.unit_location_box_number) as location ,dl.unit_type as unit_type,count(u.unit_location_box_number) as count from unit u
INNER JOIN unit_location dl on dl.unit_location_box_number = u.unit_location_box_number
where dl.unit_location_box_number=location_idParam AND u.trans_statusid IN(1,2,3,4,17);
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `repair`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `repair`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,IN docketidParam INT
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnoOut VARCHAR(16)
    ,OUT simnumberOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
        /*     
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error; */
          
            SET isexecutedOut = 0;
	END;
    BEGIN    
        DECLARE unitnoVar VARCHAR(11);
        DECLARE vehicleidVar INT(11);
        DECLARE vehiclenoVar VARCHAR(40);
        DECLARE groupidVar INT(11);

        SELECT      unitno 
        INTO        unitnoVar 
        FROM        unit 
        WHERE       uid =unitidParam
        ORDER BY    uid DESC
        LIMIT       1;
		
        SELECT      vehicleid
                    ,vehicleno
                    ,groupid 
        INTO        vehicleidVar 
                    ,vehiclenoVar
                    ,groupidVar 
        FROM        vehicle 
        WHERE       uid =unitidParam
        ORDER BY    vehicleid DESC
        LIMIT       1;

        IF statusParam = 2 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  unit 
                SET     trans_statusid= 5
						, comments = commentParam 
                        ,unit_location_box_number=-1        
                WHERE   uid= unitidParam;

                UPDATE  simcard 
                SET     trans_statusid= 13
                                ,comments =commentParam 
                WHERE   id=simcardidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`oldsimcardid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,simcardidParam
                        ,7
                        ,1
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE 	bucket 
                SET 	`status` = statusParam
                        ,`task_completion_timestamp` = todaysdateParam 
                WHERE	bucketid= bucketidParam ;

                SET isexecutedOut = 1;

            END;
            COMMIT;

        

            SELECT      simcardno 
            INTO        simnumberOut 
            FROM        simcard 
            WHERE       id = simcardidParam
            ORDER BY    id DESC
            LIMIT       1;

        
            SELECT      `name` 
            INTO        elixirOut 
            FROM        team 
            WHERE       teamid = eteamidParam
            ORDER BY    teamid DESC
            LIMIT       1;

            SELECT          c.username
                            ,c.realname
                            ,c.email 
            INTO            usernameOut
                            ,realnameOut
                            ,emailOut 
            FROM            `user` c 
            LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
            LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
            WHERE           c.customerno =  customernoParam  
            AND             c.email <> '' 
            AND             (c.groupid=groupidVar or c.groupid ='0' ) 
            AND             (c.role = 'Administrator' OR c.role = 'Master')
            ORDER BY        c.userid DESC
            LIMIT           1;

            SET vehiclenoOut=vehiclenoVar;
            SET unitnoOut=unitnoVar;

        ELSEIF statusParam = 3 THEN

            START TRANSACTION;
            BEGIN

                UPDATE 	bucket 
                SET 	`status`=statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks`= commentParam
                        ,`task_completion_timestamp` = todaysdateParam 
                where 	`bucketid`=bucketidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES ( bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'2'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = unitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = simcardidParam;

                SET     isexecutedOut = 1;

            END;
            COMMIT;

        ELSEIF statusParam = 6 THEN

            START TRANSACTION;
            BEGIN
     
                UPDATE	bucket 
                SET 	`status` = statusParam
                        ,`reschedule_date` = incompleteDateParam
                        ,`reschedule_timestamp` = todaysdateParam 
                        ,remarks = commentParam
                where 	bucketid = bucketidParam;

                INSERT INTO bucket(`apt_date`
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        , `status`
                        ,`docketid`
                        ,`prevBucketId`)
                SELECT  incompleteDateParam
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , todaysdateParam
                        , 0
                        ,docketidParam
                        ,bucketidParam
                FROM    `bucket`
                WHERE   `bucketid`=bucketidParam
                ORDER BY    bucketid DESC
                LIMIT       1;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'5'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;

            END;
            COMMIT;
            
        ELSEIF statusParam = 1 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  bucket 
                SET     `status` = statusParam 
                        ,reschedule_date = rescheduleDateParam
                        ,reschedule_timestamp = todaysdateParam 
                        ,remarks = commentParam
                WHERE   bucketid = bucketidParam;
           
                INSERT INTO bucket (`apt_date`
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        , `status`
                        ,`docketid`
                        ,`prevBucketId`)
                SELECT 	rescheduleDateParam
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , todaysdateParam
                        , 0
                        ,docketidParam
                        ,bucketidParam
                FROM 	`bucket`
                WHERE	`bucketid`=bucketidParam
                ORDER BY    bucketid DESC
                LIMIT       1;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'3'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;

            END;
            COMMIT;   
            
        ELSEIF statusParam = 5 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  bucket 
                SET     status = statusParam
                        , cancelled_timestamp = todaysdateParam
                        , cancellation_reason = commentParam 
                WHERE   bucketid = bucketidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'4'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = unitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = simcardidParam;

                SET     isexecutedOut = 1;

            END;
            COMMIT;

        END IF;

    END;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `purchase_unit`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `purchase_unit`(
     IN unitnoParam varchar(16)
    ,IN commentParam varchar(50)
    ,IN acsParam TINYINT(1)
    ,IN acoppParam TINYINT(1)
    ,IN gssParam TINYINT(1)
    ,IN gssoppParam TINYINT(1)
    ,IN dosParam TINYINT(1)
    ,IN dooroppParam TINYINT(1)
    ,IN todayParam DATETIME
    ,IN transmitnoParam varchar(20)
    ,IN devicetypeParam TINYINT(2)
    ,IN fsParam TINYINT(2)
    ,IN fuelanalogParam INT(11)
    ,IN tempsenParam TINYINT(2)
    ,IN analog1Param INT(11)
    ,IN analog2Param INT(11)
    ,IN analog3Param INT(11)
    ,IN analog4Param INT(11)
    ,IN typevalueParam INT(11)
    ,IN panicParam TINYINT(1)
    ,IN buzzerParam TINYINT(1)
    ,IN immobilizerParam TINYINT(1)
    ,IN twowaycomParam TINYINT(1)
    ,IN portableParam TINYINT(1)
    ,IN acesensorParam TINYINT(1)
    ,IN acdigitaloppParam TINYINT(1)
    ,IN chalaannoParam varchar(20)
    ,IN lteamidParam INT(11)
    ,IN chalaandateParam DATE
    ,IN vendornoParam varchar(20)
    ,IN vendordateParam DATE
    ,IN device_loctnParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
            
            SET isexecutedOut = 0;
    END;
    BEGIN  
           
        DECLARE unitidVar INT(11);
        DECLARE devicekeyVar BIGINT;
        DECLARE expiryVar date;
        DECLARE vehiclenoVar VARCHAR(40);
        DECLARE vehicleidVar INT(11);
        DECLARE driverlicnoVar VARCHAR(40);
        DECLARE driveridVar INT(11);


        SELECT  CONCAT('V',unitnoParam) 
        INTO    vehiclenoVar;

        SELECT  FLOOR(RAND() * 100000000) + 1000000000 
        INTO    devicekeyVar;

        WHILE(SELECT devicekey FROM `devices` WHERE devicekey = devicekeyVar) DO
                SELECT  FLOOR(RAND() * 100000000) + 1000000000 
                INTO    devicekeyVar;
        END WHILE;

        SELECT  date(DATE_ADD(todayParam, INTERVAL 1 YEAR)) 
        INTO    expiryVar;

        SELECT  CONCAT('LIC',unitnoParam) 
        INTO    driverlicnoVar;

        SET     isexecutedOut = 0;

        START TRANSACTION;   
        BEGIN

            IF devicetypeParam = 1 THEN

                INSERT INTO unit(`customerno`
                    ,`unitno`
                    ,`trans_statusid`
                    ,`comments`
                    ,`get_conversion`
                    ,`unit_location_box_number`) 
                VALUES (1
                    ,unitnoParam
                    ,1
                    ,commentParam
                    ,1
                    ,device_loctnParam);

            ELSE

                INSERT INTO unit(`customerno`
                    ,`unitno`
                    ,`acsensor`
                    ,`is_ac_opp`
                    ,`gensetsensor`
                    ,`is_genset_opp`
                    ,`doorsensor`
                    ,`is_door_opp`
                    ,`trans_statusid`
                    , `comments`
                    ,`digitalioupdated`
                    ,`transmitterno`
                    ,`get_conversion`
                    ,`unit_location_box_number`)
            VALUES (1
                    ,unitnoParam
                    ,acsParam
                    ,acoppParam
                    ,gssParam
                    ,gssoppParam
                    ,dosParam
                    ,dooroppParam
                    ,1
                    ,commentParam
                    ,todayParam
                    ,transmitnoParam
                    ,1
                    ,device_loctnParam);
            END IF;

            SELECT  LAST_INSERT_ID() 
            INTO    unitidVar;

            
            UPDATE  unit 
            SET     digitalioupdated = NOW()
                    ,door_digitalioupdated = NOW()
                    ,extra_digitalioupdated = NOW()
                    ,extra2_digitalioupdated = NOW() 
            WHERE   uid = unitidVar;

            
            IF devicetypeParam = 2 AND (fsParam = 1 AND fuelanalogParam <> 0) THEN

                UPDATE  unit 
                SET     fuelsensor = fuelanalogParam 
                WHERE   unitno = unitnoParam;

            END IF;

            
            IF devicetypeParam = 2 AND tempsenParam = 2 AND analog1Param <> 0 AND analog2Param <> 0 THEN

                UPDATE  unit 
                SET     tempsen1 = analog1Param 
                        ,tempsen2 = analog2Param 
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 1 AND analog1Param <> 0 THEN

                UPDATE  unit 
                SET     tempsen1 = analog1Param 
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 3 AND analog1Param <> 0 AND analog2Param <> 0 AND analog3Param <> 0 THEN

                UPDATE  unit 
                SET     tempsen1 = analog1Param 
                        , tempsen2 = analog2Param
                        , tempsen3 = analog3Param 
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 4 AND analog1Param <> 0 AND analog2Param <> 0 AND analog3Param <> 0 AND analog4Param <> 0 THEN

                UPDATE  unit 
                SET     tempsen1 = analog1Param
                        ,tempsen2 = analog2Param
                        ,tempsen3 = analog3Param 
                        ,tempsen4 = analog4Param 
                WHERE   unitno = unitnoParam;

            END IF;

            

            UPDATE  unit 
            SET     type_value = typevalueParam 
            WHERE   uid = unitidVar;

            
            IF devicetypeParam = 2 AND panicParam = 1 THEN

                UPDATE  unit 
                SET     is_panic = 1 
                WHERE   unitno = unitnoParam ;

                UPDATE  customer 
                SET     use_panic = 1 
                WHERE   customerno = 1;

            END IF;

            
            IF devicetypeParam = 2 AND buzzerParam = 1 THEN

                UPDATE  unit 
                SET     is_buzzer = 1 
                WHERE   unitno = unitnoParam;

                UPDATE  customer 
                SET     use_buzzer = 1 
                WHERE   customerno = 1;

            END IF;

            
            IF devicetypeParam = 2 AND immobilizerParam = 1 THEN

                UPDATE  unit 
                SET     is_mobiliser = 1 
                WHERE   unitno = unitnoParam;

                UPDATE  customer 
                SET     use_immobiliser = 1 
                WHERE   customerno = 1;

            END IF;

            
            IF devicetypeParam = 2 AND twowaycomParam = 1 THEN

                UPDATE  unit 
                SET     is_twowaycom = 1 
                WHERE   unitno = unitnoParam;

            END IF;

            
            IF devicetypeParam = 2 AND portableParam = 1 THEN

                UPDATE  unit 
                SET     is_portable = 1 
                WHERE   unitno = unitnoParam;

            END IF;

            
            INSERT INTO `devices`(`customerno`
                    , `devicekey`
                    , `devicelat`
                    , `devicelong`
                    , `baselat`
                    , `baselng`
                    , `installlat`
                    , `installlng`
                    , `lastupdated`
                    , `registeredon`
                    , `altitude`
                    , `directionchange`
                    , `inbatt`
                    , `hwv`
                    , `swv`
                    , `msgid`
                    , `uid`
                    , `status`
                    , `ignition`
                    , `powercut`
                    , `tamper`
                    , `gpsfixed`
                    , `online/offline`
                    , `gsmstrength`
                    , `gsmregister`
                    , `gprsregister`
                    , `aci_status`
                    , `satv`
                    , `device_invoiceno`
                    , `inv_generatedate`
                    , `installdate`
                    , `expirydate`
                    , `invoiceno`
                    , `po_no`
                    , `po_date`
                    , `warrantyexpiry`
                    , `simcardid`
                    , `inv_device_priority`
                    , `inv_deferdate`
                    , `start_date`
                    , `end_date`) 
            VALUES (1
                    ,devicekeyVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,todayParam
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,unitidVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,expiryVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,'');
            
            
            






            
            INSERT INTO vehicle (vehicleno
                    , customerno
                    , uid
                    , kind) 
            VALUES  ( vehiclenoVar
                    , '1'
                    , unitidVar
                    , 'Truck');


            SELECT  LAST_INSERT_ID() 
            INTO    vehicleidVar;

            
            UPDATE  unit 
            SET     vehicleid = vehicleidVar 
            WHERE   uid = unitidVar;

            
            INSERT INTO driver (drivername
                    ,driverlicno
                    ,customerno
                    ,vehicleid) 
            VALUES ('Not Allocated',driverlicnoVar,1,vehicleidVar);

            SELECT  LAST_INSERT_ID() 
            INTO    driveridVar;

            
            UPDATE  vehicle 
            SET     driverid = driveridVar 
            WHERE   vehicleid = vehicleidVar;


            
            INSERT INTO eventalerts (vehicleid
                    ,overspeed
                    , tamper
                    , powercut
                    , temp
                    , ac
                    , customerno) 
            VALUES (vehicleidVar
                    ,0
                    ,0
                    ,0
                    ,0
                    ,0
                    ,1);


            
            INSERT INTO ignitionalert (vehicleid
                    ,last_status
                    ,last_check
                    ,`count`
                    ,`status`
                    ,customerno) 
                    VALUES (vehicleidVar
                    ,0
                    ,0
                    ,0
                    ,0
                    ,1);

            
            IF acesensorParam = 1 AND acdigitaloppParam <> 0 THEN

                INSERT INTO acalerts (last_ignition
                    , customerno
                    , vehicleid
                    , aci_status) 
                VALUES (0
                    ,1
                    ,vehicleidVar
                    ,0);

            END IF;

            INSERT INTO trans_history (`customerno`
                ,`unitid`
                ,`teamid`
                , `type`
                , `trans_time`
                , `statusid`
                , `transaction`
                , `simcardno`
                , `invoiceno`
                , `expirydate`
                , `comments`)
            VALUES (1
                ,unitidVar
                ,lteamidParam
                , 0
                ,todayParam
                , 1
                , 'New Purchase'
                ,''
                ,''
                ,''
                ,commentParam);

            IF chalaannoParam <> '' THEN

                INSERT INTO chalaan (uid 
                    , chalaan_no
                    , chalaan_date 
                    , vendor_invno 
                    , vendor_invdate 
                    , insertedby 
                    , insertedon)
                VALUES(unitidVar
                    ,chalaannoParam
                    ,chalaandateParam
                    ,vendornoParam
                    ,vendordateParam
                    ,lteamidParam
                    ,todayParam);

            END IF;
            
            SET isexecutedOut = 1;

        END;
        COMMIT;
    
    END;
END$$

DELIMITER ;





