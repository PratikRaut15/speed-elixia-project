INSERT INTO `dbpatches` (`patchid` ,`patchdate` ,`appliedby` ,`patchdesc` ,`isapplied`)
VALUES ('651', '2019-01-14 11:00:00','Yash Kanakia','Insert Customer,User and Login History', '0');

ALTER TABLE sales_pipeline
MODIFY COLUMN productid VARCHAR(100);

ALTER TABLE sales_pipeline_history
MODIFY COLUMN productid VARCHAR(100);

ALTER TABLE sales_contact
ADD COLUMN isUserCreated tinyInt DEFAULT 0;

ALTER TABLE sales_pipeline
ADD COLUMN customerno INT DEFAULT 0;


ALTER TABLE customer
ADD COLUMN use_books tinyInt,
ADD COLUMN use_controlTower tinyInt,
ADD COLUMN use_crm tinyInt;



DELIMITER $$
DROP procedure IF EXISTS `fetch_contact_pipeline`$$
CREATE PROCEDURE `fetch_contact_pipeline`(
  IN contactIdParam INT)
BEGIN
	SELECT * from sales_contact where contactid = contactIdParam;
END$$
DELIMITER ;




DELIMITER $$
DROP procedure IF EXISTS `insert_customer`$$
CREATE PROCEDURE `insert_customer`(
  IN customernameParam VARCHAR(100),
  IN customercompanyParam VARCHAR(200),
  IN dateaddedParam date,
  IN totalsmsParam INT,
  IN smsleftParam INT,
  IN total_alertParam INT,
  IN alertleftParam INT,
  IN teamidParam INT,
  IN loadingParam INT,
  IN locationParam INT,
  IN trackingParam INT,
  IN maitenanceParam INT,
  IN tempSensorsParam INT,
  IN portableParam INT,
  IN hierarchyParam INT,
  IN advanceAlertsParam INT,
  IN acSensorParam INT,
  IN gensetSensorParam INT,
  IN fuelSensorParam INT,
  IN doorSensorParam INT,
  IN routeParam INT,
  IN panicParam INT,
  IN buzzerParam INT,
  IN immobilizerParam INT,
  IN mobilityParam INT,
  IN deliveryParam INT,
  IN salesParam INT,
  IN erpParam INT,
  IN warehouseParam INT,
  IN traceParam INT,
  IN controlTowerParam INT,
  IN crmParam INT,
  IN booksParam INT,
  IN timezoneParam INT,
  IN commercial_detailsParam VARCHAR(500),
  IN createdTimeParam datetime,
  OUT isexecutedOut INT,
  OUT customerIdOut INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
           ROLLBACK;
        /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error;
           SET isexecutedOut = 0; */
       END;

        SET isexecutedOut = 0;

       START TRANSACTION;
       BEGIN
            INSERT INTO customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,
            `use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,
            `use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`,
            `use_fuel_sensor`, `use_door_sensor`, `use_routing`,
            `use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`, `use_delivery`,`use_sales`,`use_erp`,`use_warehouse`,`use_trace`,`use_controlTower`,`use_crm`,`use_books`, `commercial_details`,`timezone`,`createdtime`)
            VALUES (customernameParam, customercompanyParam, dateaddedParam, totalsmsParam, totalsmsParam, total_alertParam, total_alertParam, teamidParam, loadingParam,
             locationParam, trackingParam, maitenanceParam,tempSensorsParam, portableParam, hierarchyParam,advanceAlertsParam,acSensorParam,gensetSensorParam,
             fuelSensorParam,doorSensorParam,routeParam,panicParam,buzzerParam,immobilizerParam,mobilityParam,deliveryParam,salesParam,erpParam,warehouseParam,traceParam,controlTowerParam,crmParam,booksParam,commercial_detailsParam,timezoneParam,createdTimeParam);

            SET customerIdOut = LAST_INSERT_ID();

            INSERT INTO trans_history (
            `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
            VALUES (customerIdOut, 0, teamidParam, 2, createdTimeParam, 0, CONCAT("SMS Added:",totalsmsParam,"Total SMS:",totalsmsParam));

            INSERT INTO trans_history (
            `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
            VALUES (customerIdOut, 0, teamidParam, 2, createdTimeParam, 0, CONCAT("Telephonic Alerts Added :",total_alertParam,"Total SMS:",total_alertParam));

       INSERT INTO elixiatech.customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,
            `use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,
            `use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`,
            `use_fuel_sensor`, `use_door_sensor`, `use_routing`,
            `use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`, `use_delivery`,`use_sales`,`use_erp`,`use_warehouse`,`use_trace`,`use_controlTower`,`use_crm`,`use_books`, `commercial_details`,`timezone`,`createdtime`)
            VALUES (customernameParam, customercompanyParam, dateaddedParam, totalsmsParam, totalsmsParam, total_alertParam, total_alertParam, teamidParam, loadingParam,
             locationParam, trackingParam, maitenanceParam,tempSensorsParam, portableParam, hierarchyParam,advanceAlertsParam,acSensorParam,gensetSensorParam,
             fuelSensorParam,doorSensorParam,routeParam,panicParam,buzzerParam,immobilizerParam,mobilityParam,deliveryParam,salesParam,erpParam,warehouseParam,traceParam,controlTowerParam,crmParam,booksParam,commercial_detailsParam,timezoneParam,createdTimeParam);

            SET isExecutedOut = 1;
       END;
       COMMIT;
END$$
DELIMITER ;





DELIMITER $$
DROP procedure IF EXISTS `insert_user_trace`$$
CREATE PROCEDURE `insert_user_trace`(
  IN customeridParam INT(11)
  ,IN primaryusernameParam VARCHAR(100)
  ,IN primaryuserloginParam VARCHAR(50)
  ,IN primaryuserpasswordParam VARCHAR(150)
  ,IN userkey1Param VARCHAR(150)
  ,IN userkey2Param VARCHAR(150)
  ,IN todaydatetimeParam datetime
  ,OUT isExecutedOut INT
  ,OUT userIdOut INT)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION

    SET isExecutedOut=0;
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */

    END;

    #USER FOR CUSTOMER

    INSERT into trace.user (customerno, realname, username, password, role, roleid, email, userkey)
    VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);


    #USER FOR ELIXIA
    INSERT into trace.user (customerno, realname, username, password, role, userkey)
    VALUES (customeridParam, 'Elixir', concat("elixir",customeridParam), sha1('el!365x!@'),'elixir', userkey2Param);

    SET userIdOut = LAST_INSERT_ID();

    SET isExecutedOut=1;
END$$

DELIMITER ;




DELIMITER $$
DROP procedure IF EXISTS `update_customer_pipleine`$$
CREATE PROCEDURE `update_customer_pipleine`(
  IN contactIdParam INT
  ,IN customerNoParam INT
  ,IN pipelineIdParam INT
  )
BEGIN
  UPDATE sales_pipeline SET

  customerno = customerNoParam

  WHERE (pipelineid  = pipelineIdParam);


    UPDATE sales_contact SET

  isUserCreated = 1

  WHERE (contactid  = contactIdParam);
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `insert_user`$$
CREATE PROCEDURE `insert_user`(
  IN customeridParam INT(11)
  ,IN primaryusernameParam VARCHAR(100)
  ,IN primaryuserloginParam VARCHAR(50)
  ,IN primaryuserpasswordParam VARCHAR(150)
  ,IN userkey1Param VARCHAR(150)
  ,IN userkey2Param VARCHAR(150)
  ,IN ctrakingParam TINYINT(4)
  ,IN cmaintenanceParam INT(5)
  ,IN cheirarchyParam TINYINT(1)
  ,IN moduleidParam INT(11)
  ,IN todaydatetimeParam datetime
  ,OUT isExecutedOut INT
  ,OUT userIdOut INT)
BEGIN
  DECLARE lastInsertID INT;
  DECLARE lastelixirId INT;
  DECLARE EXIT HANDLER FOR SQLEXCEPTION

  SET lastInsertId = 0;
  SET lastelixirId = 0;
  SET isExecutedOut=0;
      BEGIN
          ROLLBACK;
          /*
          GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
          @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
          SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
          SELECT @full_error;
          */

      END;

  #USER FOR CUSTOMER
  IF(cmaintenanceParam = 1 AND cheirarchyParam = 1 AND ctrakingParam = 1) THEN
  INSERT into user (customerno, realname, username, password, role, roleid, email, userkey)
  VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
  INSERT into elixiatech.user (customerno, realname, username, password, role, roleid, email, userkey)
  VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);

  SET lastInsertId = LAST_INSERT_ID();
  SET userIdOut = LAST_INSERT_ID();
  INSERT INTO usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
  SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);
  /*INSERT INTO uat_elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
  SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);*/


  ELSEIF(cmaintenanceParam = 1 && cheirarchyParam = 1 && ctrakingParam!=1) THEN
  INSERT into user (customerno, realname, username, password, role, roleid,email, userkey)
  VALUES (customeridParam,primaryusernameParam,primaryuserloginParam, sha1(primaryuserpasswordParam),'Master', 1,primaryuserloginParam, userkey1Param);
  INSERT into elixiatech.user (customerno, realname, username, password, role, roleid,email, userkey)
  VALUES (customeridParam,primaryusernameParam,primaryuserloginParam, sha1(primaryuserpasswordParam),'Master', 1,primaryuserloginParam, userkey1Param);

  SET lastInsertId = LAST_INSERT_ID();
  SET userIdOut = LAST_INSERT_ID();
  INSERT INTO usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
  SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);
  /*INSERT INTO uat_elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
  SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);*/


  ELSE
  INSERT into user (customerno, realname, username, password, role, roleid, email, userkey)
  VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
  INSERT into elixiatech.user (customerno, realname, username, password, role, roleid, email, userkey)
  VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
  SET userIdOut = LAST_INSERT_ID();
  END IF;

  #USER FOR ELIXIA
  INSERT into user (customerno, realname, username, password, role, userkey)
  VALUES (customeridParam, 'Elixir', concat("elixir",customeridParam), sha1('el!365x!@'),'elixir', userkey2Param);

  INSERT into elixiatech.user (customerno, realname, username, password, role, userkey)
  VALUES (customeridParam, 'Elixir', concat("elixir",customeridParam), sha1('el!365x!@'),'elixir', userkey2Param);
  SET lastelixirId = LAST_INSERT_ID();

  IF(cmaintenanceParam = 1 AND cheirarchyParam = 1 AND ctrakingParam = 1) THEN
  INSERT INTO usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
  SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);

  /*INSERT INTO uat_elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
  SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);*/
  END IF;

  IF(cmaintenanceParam = 1 && cheirarchyParam = 1 && ctrakingParam!=1) THEN
  INSERT INTO usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
  SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);

  /*INSERT INTO uat_elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
  SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);*/
  END IF;
  SET isExecutedOut=1;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `get_login_history`$$
CREATE PROCEDURE `get_login_history`(
  IN customernoParam INT,
  IN dateParam date)
BEGIN

    SELECT 
      lhd.created_on, u.username
    FROM
      login_history_details lhd
          INNER JOIN
      user u ON u.userid = lhd.created_by
    WHERE
      DATE(lhd.created_on) = dateParam
          AND lhd.customerno = customernoParam;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_login_trend`$$
CREATE PROCEDURE `get_login_trend`(
  IN customernoParam INT)
BEGIN
  SELECT 
      DATE_FORMAT(created_on, '%h %p') AS hour,
      DAYNAME(created_on) AS day,
      COUNT(*) AS total,
      customerno
  FROM
      login_history_details
  WHERE
      customerno = customernoParam
  GROUP BY MINUTE(created_on) , WEEKDAY(created_on)
  ORDER BY total DESC , HOUR(created_on) , WEEKDAY(created_on)
  LIMIT 1;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_userkey_trace`$$
CREATE PROCEDURE `fetch_userkey_trace`(
IN userkeyParam VARCHAR(100),
OUT isExistOut INT)
BEGIN
    DECLARE userkey_temp VARCHAR(100);
    SET isExistOut =0;

    SELECT userkey INTO userkey_temp from trace.user where userkey=userkeyParam;

    IF(userkey_temp IS NOT NULL) THEN
        SET isExistOut =1;
    END IF;
END$$

DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2019-01-14 11:00:00'
        ,isapplied = 1
WHERE   patchid = 651;
