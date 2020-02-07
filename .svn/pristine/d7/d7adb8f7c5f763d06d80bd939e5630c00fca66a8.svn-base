INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'540', '2018-02-14 13:00:00', 'Yash Kanakia', 'TEAM_CUSTOMER', '0'
);

-- Customer table for elixiatech db
DELETE FROM elixiatech.customer;
INSERT INTO elixiatech.customer
SELECT * FROM speed.customer;

-- contactperson_details table for elixiatech db
DROP TABLE IF EXISTS elixiatech.`contactperson_details`;
CREATE TABLE elixiatech.contactperson_details (
  `cpdetailid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `typeid` int(11) NOT NULL,
  `person_name` varchar(30) NOT NULL,
  `cp_email1` varchar(50) NOT NULL,
  `cp_email2` varchar(50) NOT NULL,
  `cp_phone1` varchar(15) NOT NULL,
  `cp_phone2` varchar(15) NOT NULL,
  `customerno` int(11) NOT NULL,
  `insertedby` int(11) NOT NULL,
  `insertedon` datetime DEFAULT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- INSERT INTO elixiatech.contactperson_details
-- SELECT * from speed.contactperson_details;

DROP TABLE IF EXISTS elixiatech.contactperson_type_master;
CREATE TABLE elixiatech.contactperson_type_master (
  `person_typeid` int(11) NOT NULL,
  `person_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contactperson_type_master`
--

INSERT INTO elixiatech.contactperson_type_master (`person_typeid`, `person_type`) VALUES
(1, 'Owner'),
(2, 'Accounts'),
(3, 'Co-ordinator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contactperson_type_master`
--
ALTER TABLE elixiatech.contactperson_type_master
  ADD PRIMARY KEY (`person_typeid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contactperson_type_master`
--
ALTER TABLE elixiatech.contactperson_type_master
  MODIFY `person_typeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;


-- User table for elixiatech db
DROP TABLE IF EXISTS elixiatech.user;
CREATE TABLE elixiatech.user (
  `userid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `customerno` int(11) NOT NULL,
  `stateid` int(11) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `role` varchar(50) NOT NULL,
  `roleid` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `mobile1` varchar(15) NOT NULL DEFAULT '',
  `mobile2` varchar(15) NOT NULL DEFAULT '',
  `lastvisit` datetime NOT NULL,
  `visited` int(11) NOT NULL,
  `userkey` varchar(150) NOT NULL,
  `mess_email` tinyint(1) NOT NULL,
  `mess_sms` tinyint(1) NOT NULL,
  `mess_telephone` tinyint(4) NOT NULL,
  `mess_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `speed_email` tinyint(1) NOT NULL,
  `speed_sms` tinyint(1) NOT NULL,
  `speed_telephone` tinyint(4) NOT NULL,
  `speed_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `power_email` tinyint(1) NOT NULL,
  `power_sms` tinyint(1) NOT NULL,
  `power_telephone` tinyint(4) NOT NULL,
  `power_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `tamper_email` tinyint(1) NOT NULL,
  `tamper_sms` tinyint(1) NOT NULL,
  `tamper_telephone` tinyint(4) NOT NULL,
  `tamper_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `chk_email` tinyint(1) NOT NULL,
  `chk_sms` tinyint(1) NOT NULL,
  `chk_telephone` tinyint(4) NOT NULL,
  `chk_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `ac_email` tinyint(1) NOT NULL,
  `ac_sms` tinyint(1) NOT NULL,
  `ac_telephone` tinyint(4) NOT NULL,
  `ac_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `ignition_email` tinyint(4) NOT NULL,
  `ignition_sms` tinyint(4) NOT NULL,
  `ignition_telephone` tinyint(4) NOT NULL,
  `ignition_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `aci_email` tinyint(1) NOT NULL,
  `aci_sms` tinyint(1) NOT NULL,
  `aci_telephone` tinyint(4) NOT NULL,
  `aci_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `temp_email` tinyint(1) NOT NULL,
  `temp_sms` tinyint(1) NOT NULL,
  `temp_telephone` tinyint(4) NOT NULL,
  `temp_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `panic_email` tinyint(1) DEFAULT '0',
  `panic_sms` tinyint(1) DEFAULT '0',
  `panic_telephone` tinyint(4) NOT NULL,
  `panic_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `immob_email` tinyint(1) DEFAULT '0',
  `immob_sms` tinyint(1) DEFAULT '0',
  `immob_telephone` tinyint(4) NOT NULL,
  `immob_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `door_sms` tinyint(1) DEFAULT '0',
  `door_email` tinyint(1) DEFAULT '0',
  `door_telephone` tinyint(4) NOT NULL,
  `door_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `modifiedby` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `chgpwd` tinyint(4) NOT NULL,
  `chgalert` tinyint(4) NOT NULL,
  `groupid` int(11) NOT NULL,
  `start_alert` time NOT NULL,
  `stop_alert` time NOT NULL DEFAULT '23:59:59',
  `fuel_alert_sms` tinyint(1) NOT NULL,
  `fuel_alert_email` tinyint(1) NOT NULL,
  `fuel_alert_percentage` int(3) NOT NULL DEFAULT '20',
  `lastlogin_android` datetime NOT NULL,
  `heirarchy_id` int(11) NOT NULL,
  `dailyemail` tinyint(1) DEFAULT '0',
  `vehicle_movement_alert` tinyint(1) NOT NULL DEFAULT '0',
  `dailyemail_csv` tinyint(1) DEFAULT '0',
  `harsh_break_sms` tinyint(1) DEFAULT '0',
  `harsh_break_mail` tinyint(1) DEFAULT '0',
  `harsh_break_telephone` tinyint(4) NOT NULL,
  `harsh_break_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `high_acce_sms` tinyint(1) DEFAULT '0',
  `high_acce_mail` tinyint(1) DEFAULT '0',
  `high_acce_telephone` tinyint(4) NOT NULL,
  `high_acce_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `sharp_turn_sms` tinyint(1) DEFAULT '0',
  `sharp_turn_mail` tinyint(1) DEFAULT '0',
  `sharp_turn_telephone` tinyint(4) NOT NULL,
  `sharp_turn_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `towing_sms` tinyint(1) DEFAULT '0',
  `towing_mail` tinyint(1) DEFAULT '0',
  `towing_telephone` tinyint(4) NOT NULL,
  `towing_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `fuel_alert_telephone` tinyint(4) NOT NULL,
  `fuel_alert_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
  `tempinterval` varchar(10) NOT NULL,
  `igninterval` varchar(10) NOT NULL,
  `speedinterval` varchar(10) NOT NULL,
  `acinterval` varchar(10) NOT NULL,
  `doorinterval` varchar(10) NOT NULL,
  `chkpushandroid` tinyint(1) NOT NULL,
  `chkmanpushandroid` tinyint(1) NOT NULL,
  `gcmid` text NOT NULL,
  `delivery_vehicleid` int(11) NOT NULL DEFAULT '0',
  `notification_status` tinyint(1) NOT NULL DEFAULT '1',
  `hum_sms` tinyint(1) DEFAULT '0',
  `hum_email` tinyint(1) DEFAULT '0',
  `hum_telephone` tinyint(4) DEFAULT '0',
  `hum_mobilenotification` tinyint(4) DEFAULT '1',
  `huminterval` varchar(10) DEFAULT NULL,
  `refreshtime` tinyint(1) DEFAULT '1',
  `sms_count` int(11) NOT NULL DEFAULT '0',
  `sms_lock` tinyint(1) NOT NULL DEFAULT '0',
  `smsalert_status` tinyint(2) NOT NULL DEFAULT '0',
  `emailalert_status` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- INSERT INTO elixiatech.user
-- SELECT * FROM speed.user;


-- Trans_History table for elixiatech db
DROP TABLE IF EXISTS elixiatech.trans_history;
CREATE TABLE elixiatech.trans_history (
  `thid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `simcard_id` int(11) NOT NULL,
  `unitid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `trans_time` datetime NOT NULL,
  `transaction` text NOT NULL,
  `customerno` int(11) NOT NULL,
  `statusid` int(11) NOT NULL,
  `simcardno` varchar(50) NOT NULL,
  `invoiceno` varchar(50) NOT NULL,
  `expirydate` date NOT NULL,
  `allot_teamid` int(11) NOT NULL,
  `comments` varchar(50) NOT NULL,
  `vehicleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- INSERT INTO elixiatech.trans_history
-- SELECT * FROM speed.trans_history;

-- usermenu_mapping table for elixiatech db
DROP TABLE IF EXISTS elixiatech.usermenu_mapping;
CREATE TABLE elixiatech.usermenu_mapping (
  `umid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `menuid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `add_permission` tinyint(1) NOT NULL DEFAULT '0',
  `edit_permission` tinyint(1) NOT NULL DEFAULT '0',
  `delete_permission` tinyint(1) NOT NULL DEFAULT '0',
  `customerno` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `isactive` tinyint(2) NOT NULL DEFAULT '0',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- INSERT INTO elixiatech.usermenu_mapping
-- SELECT * FROM speed.usermenu_mapping;


-- menu_master table for elixiatech db
DROP TABLE IF EXISTS elixiatech.menu_master;
CREATE TABLE elixiatech.menu_master (
  `menuid` int(11) NOT NULL,
  `menuname` varchar(100) NOT NULL,
  `parent_menuid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `page` varchar(100) NOT NULL,
  `sequenceno` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- INSERT INTO elixiatech.menu_master
-- SELECT * FROM speed.menu_master;

/*
    Name          - insert_customer
    Description   - Insert Customer in Team
    Parameters    - 

    Module          - Team
    Sample Call   - 
    Created by    - Yash Kanakia
    Created on    - 12-02-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `insert_customer`$$
CREATE PROCEDURE `insert_customer`(
    IN primaryusernameParam VARCHAR(100)
    ,IN custcompanyParam VARCHAR(100)
    ,IN todayParam date
    ,IN custsmsParam INT(11)
    ,IN custtelephonicalertParam INT(11)
    ,IN teamidParam INT(11)
    ,IN cloadingParam TINYINT(1)
    ,IN cgeolocationParam TINYINT(1)
    ,IN ctrackingParam TINYINT(4)
    ,IN cmaintenanceParam INT(5)
    ,IN ctempsensorParam INT(11)
    ,IN cportableParam TINYINT(1)
    ,IN cheirarchyParam TINYINT(1)
    ,IN advanced_alertParam TINYINT(1)
    ,IN cacParam TINYINT(1)
    ,IN cgensetParam TINYINT(1)
    ,IN cfuelParam TINYINT(1)
    ,IN cdoorParam TINYINT(1)
    ,IN croutingParam TINYINT(1)
    ,IN cpanicParam TINYINT(1)
    ,IN cbuzzerParam TINYINT(4)
    ,IN cimmoParam TINYINT(1)
    ,IN cimobParam TINYINT(1)
    ,IN cdeliveryParam TINYINT(1)
    ,IN csalesengageParam TINYINT(1)
    ,IN timezoneParam INT(5)
    ,IN todaydatetimeParam datetime
    ,IN primaryuserloginParam VARCHAR(50)
    ,IN primaryphoneParam VARCHAR(15)
    ,OUT lastInsertId INT
)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
SET lastInsertId = 0;
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;

INSERT INTO speed.customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,`use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,`use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`, `use_fuel_sensor`, `use_door_sensor`, `use_routing`, `use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`, `use_delivery`,`use_sales`, `timezone`,`createdtime`)
VALUES (primaryusernameParam, custcompanyParam, todayParam, custsmsParam, custsmsParam, custtelephonicalertParam, custtelephonicalertParam, teamidParam, cloadingParam, cgeolocationParam, ctrackingParam, cmaintenanceParam,ctempsensorParam, cportableParam, cheirarchyParam,advanced_alertParam,cacParam,cgensetParam,cfuelParam,cdoorParam,croutingParam,cpanicParam,cbuzzerParam,cimmoParam,cimobParam,cdeliveryParam,csalesengageParam,timezoneParam,todaydatetimeParam);

SET lastInsertId = last_insert_id();

INSERT INTO elixiatech.customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,`use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,`use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`, `use_fuel_sensor`, `use_door_sensor`, `use_routing`, `use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`, `use_delivery`,`use_sales`, `timezone`,`createdtime`)
VALUES (primaryusernameParam, custcompanyParam, todayParam, custsmsParam, custsmsParam, custtelephonicalertParam, custtelephonicalertParam, teamidParam, cloadingParam, cgeolocationParam, ctrackingParam, cmaintenanceParam,ctempsensorParam, cportableParam, cheirarchyParam,advanced_alertParam,cacParam,cgensetParam,cfuelParam,cdoorParam,croutingParam,cpanicParam,cbuzzerParam,cimmoParam,cimobParam,cdeliveryParam,csalesengageParam,timezoneParam,todaydatetimeParam);


INSERT INTO speed.contactperson_details (`typeid`, `person_name`, `cp_email1`, `cp_phone1`, `customerno`, `isdeleted`,`insertedby`,`insertedon`) 
VALUES (1,primaryusernameParam,primaryuserloginParam,primaryphoneParam,lastInsertId,0,teamidParam,todaydatetimeParam);


INSERT INTO elixiatech.contactperson_details (`typeid`, `person_name`, `cp_email1`, `cp_phone1`, `customerno`, `isdeleted`,`insertedby`,`insertedon`) 
VALUES (1,primaryusernameParam,primaryuserloginParam,primaryphoneParam,lastInsertId,0,teamidParam,todaydatetimeParam);


INSERT INTO speed.trans_history (`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
VALUES (lastInsertId, 0, teamidParam, 2, todaydatetimeParam, 0, concat("SMS Added :",custsmsParam,"Total SMS:",custsmsParam));

INSERT INTO elixiatech.trans_history (`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
VALUES (lastInsertId, 0, teamidParam, 2, todaydatetimeParam, 0, concat("SMS Added :",custsmsParam,"Total SMS:",custsmsParam));

INSERT INTO speed.trans_history (`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
VALUES (lastInsertId, 0, teamidParam, 2, todaydatetimeParam, 0, concat("Telephonice Alerts Added :",custtelephonicalertParam,"Telephonice Alerts:",custtelephonicalertParam));

INSERT INTO elixiatech.trans_history (`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
VALUES (lastInsertId, 0, teamidParam, 2, todaydatetimeParam, 0, concat("Telephonice Alerts Added :",custtelephonicalertParam,"Telephonice Alerts:",custtelephonicalertParam));




END$$

DELIMITER ;


/*
    Name          - insert_user
    Description   - Insert User in Team
    Parameters    -

    Module    - Team
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 12-02-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/

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
    
)
BEGIN
DECLARE lastInsertID INT;   
DECLARE lastelixirId INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION

SET lastInsertId = 0;
SET lastelixirId = 0;
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
INSERT into speed.user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
INSERT into elixiatech.user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);

SET lastInsertId = LAST_INSERT_ID();
INSERT INTO speed.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);
INSERT INTO elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);


ELSEIF(cmaintenanceParam = 1 && cheirarchyParam = 1 && ctrakingParam!=1) THEN
INSERT into speed.user (customerno, realname, username, password, role, roleid,email, userkey)
VALUES (customeridParam,primaryusernameParam,primaryuserloginParam, sha1(primaryuserpasswordParam),'Master', 1,primaryuserloginParam, userkey1Param);
INSERT into elixiatech.user (customerno, realname, username, password, role, roleid,email, userkey)
VALUES (customeridParam,primaryusernameParam,primaryuserloginParam, sha1(primaryuserpasswordParam),'Master', 1,primaryuserloginParam, userkey1Param);

SET lastInsertId = LAST_INSERT_ID();
INSERT INTO speed.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);
INSERT INTO elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);


ELSE
INSERT into speed.user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
INSERT into elixiatech.user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
END IF;

#USER FOR ELIXIA
INSERT into speed.user (customerno, realname, username, password, role, userkey)
VALUES (customeridParam, 'Elixir', concat("elixir",customeridParam), sha1('el!365x!@'),'elixir', userkey2Param);

INSERT into elixiatech.user (customerno, realname, username, password, role, userkey)
VALUES (customeridParam, 'Elixir', concat("elixir",customeridParam), sha1('el!365x!@'),'elixir', userkey2Param);
SET lastelixirId = LAST_INSERT_ID();

IF(cmaintenanceParam = 1 AND cheirarchyParam = 1 AND ctrakingParam = 1) THEN
INSERT INTO speed.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);

INSERT INTO elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);
END IF;

IF(cmaintenanceParam = 1 && cheirarchyParam = 1 && ctrakingParam!=1) THEN
INSERT INTO speed.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);

INSERT INTO elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);
END IF;

END$$

DELIMITER ;




/*
    Name          - insert_customer_details
    Description   - Insert Customer additional Details
    Parameters    -

    Module    - Team
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 12-02-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_customer_details`$$
CREATE PROCEDURE `insert_customer_details`(
     IN typeParam INT(11)
    ,IN primaryusernameParam VARCHAR(100)
    ,IN email1Param VARCHAR(50)
    ,IN email2Param VARCHAR(50)
    ,IN phone1Param VARCHAR(50)
    ,IN phone2Param VARCHAR(50)
    ,IN customenoParam VARCHAR(100)
    ,IN teamidParam INT(11)
    ,IN todaydatetimeParam datetime
 
)
BEGIN


INSERT INTO speed.contactperson_details (`typeid`, `person_name`, `cp_email1`,`cp_email2`, `cp_phone1`,`cp_phone2`, `customerno`, `isdeleted`,`insertedby`,`insertedon`) 
VALUES (typeParam,primaryusernameParam,email1Param,email2Param,phone1Param,phone2Param,customenoParam,0,teamidParam,todaydatetimeParam);

INSERT INTO elixiatech.contactperson_details (`typeid`, `person_name`, `cp_email1`,`cp_email2`, `cp_phone1`,`cp_phone2`, `customerno`, `isdeleted`,`insertedby`,`insertedon`) 
VALUES (typeParam,primaryusernameParam,email1Param,email2Param,phone1Param,phone2Param,customenoParam,0,teamidParam,todaydatetimeParam);


END$$
DELIMITER ;

/*
    Name          - insert_secondary_user
    Description   - Insert new user for same customer
    Parameters    - 

    Module    - Team
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 12-02-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `insert_secondary_user`$$
CREATE PROCEDURE `insert_secondary_user`(
    IN nameParam VARCHAR(50)
    ,IN usernameParam VARCHAR(50)
    ,IN emailParam VARCHAR(50)
    ,IN passwordParam VARCHAR(150)
    ,IN phonenoParam VARCHAR(15)
    ,IN roleParam VARCHAR(150)
    ,IN roleidParam INT(11)
    ,IN groupidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN modified_byParam INT(11)
    ,IN userkeyParam VARCHAR(150)

)
BEGIN

INSERT INTO speed.user
                        (`customerno`,
                        `realname`,
                        `username`,
                        `password`,
                        `role`,
                        `roleid`,
                        `email`,
                        `phone`,
                        `userkey`,
                        `modifiedby`,
                        `isdeleted`,
                        `groupid`)
VALUES (customernoParam,nameParam, usernameParam,sha1(passwordParam), roleParam, roleidParam,emailParam, phonenoParam,userkeyParam,modified_byParam,0,groupidParam);

INSERT INTO elixiatech.user
                        (`customerno`,
                        `realname`,
                        `username`,
                        `password`,
                        `role`,
                        `roleid`,
                        `email`,
                        `phone`,
                        `userkey`,
                        `modifiedby`,
                        `isdeleted`,
                        `groupid`)
VALUES (customernoParam,nameParam, usernameParam,sha1(passwordParam), roleParam, roleidParam,emailParam, phonenoParam,userkeyParam,modified_byParam,0,groupidParam);


END$$

DELIMITER ;



/*
    Name          - edit_customer
    Description   - Update the customer details
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 12-02-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `edit_customer`$$
CREATE PROCEDURE `edit_customer`(
     IN customernoParam INT(11)
    ,IN primaryusernameParam VARCHAR(100)
    ,IN industryidParam INT(11)
    ,IN teamidParam INT(11)
    ,IN salesidParam INT(11)
    ,IN custsmsParam INT(11)
    ,IN custcompanyParam VARCHAR(100)
    ,IN custtelephonicalertParam INT(11)
    ,IN cloadingParam TINYINT(1)
    ,IN cgeolocationParam TINYINT(1)
    ,IN ctrakingParam TINYINT(4)
    ,IN cmaintenanceParam INT(5)
    ,IN cportableParam TINYINT(1)
    ,IN ctempsensorParam INT(11)
    ,IN cheirarchyParam TINYINT(1)
    ,IN advanced_alertParam TINYINT(1)
    ,IN cacParam TINYINT(1)
    ,IN cgensetParam TINYINT(1)
    ,IN cfuelParam TINYINT(1)
    ,IN cpanicParam TINYINT(1)
    ,IN cbuzzerParam TINYINT(4)
    ,IN cimmoParam TINYINT(1)
    ,IN cimobParam TINYINT(1)
    ,IN csalesengageParam TINYINT(1)
    ,IN cdoorParam TINYINT(1)
    ,IN cdeliveryParam TINYINT(1)
    ,IN croutingParam TINYINT(1)
    ,IN cunitpriceParam INT(20)
    ,IN cunitmspParam INT(20)
    ,IN custrenewalParam INT(4)
    ,IN timezoneParam INT(5)
    ,IN leasedurationParam INT(11)
    ,IN leasepriceParam INT(11)
    ,IN comdetailsParam VARCHAR(500)
    ,IN todaydatetimeParam datetime
    )
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;
SELECT totalsms,total_tel_alert INTO @TOTALSMS,@TOTALALERT
from speed.customer
 WHERE `customerno`= customernoParam;
 Select @TOTALSMS,@TOTALALERT;
  UPDATE speed.customer SET
            `customername`=primaryusernameParam,
            `industryid`=industryidParam,
            `salesid`=salesidParam,
            `customercompany`=custcompanyParam,
            `totalsms`= @TOTALSMS+custsmsParam,
           `smsleft`= @TOTALSMS+custsmsParam,
           `sms_balance_alert`='0',
            `total_tel_alert`= @TOTALALERT+custtelephonicalertParam,
            `tel_alertleft`= @TOTALALERT+custtelephonicalertParam,
            `use_msgkey`=cloadingParam,
            `use_geolocation`=cgeolocationParam,
            `use_tracking`=ctrakingParam,
            `use_maintenance`=cmaintenanceParam,
            `use_portable`=cportableParam,
            `temp_sensors`=ctempsensorParam,
            `use_hierarchy`=cheirarchyParam,
            `use_advanced_alert`= advanced_alertParam,
            `use_ac_sensor`= cacParam,
            `use_genset_sensor`= cgensetParam,
            `use_fuel_sensor`= cfuelParam,
            `use_panic`= cpanicParam,
            `use_buzzer`= cbuzzerParam,
            `use_immobiliser`= cimmoParam,
            `use_mobility`= cimobParam,
            `use_sales` = csalesengageParam,
            `use_door_sensor`= cdoorParam,
            `use_delivery`= cdeliveryParam,
            `use_routing`= croutingParam,
            `unitprice`= cunitpriceParam,
            `unit_msp`= cunitmspParam,
            `renewal`= custrenewalParam,
            `timezone`=timezoneParam,
            `lease_duration`= leasedurationParam,
            `lease_price`= leasepriceParam,
            `commercial_details`=comdetailsParam
            WHERE customerno = customernoParam;
            
SELECT totalsms,total_tel_alert INTO @TOTALSMS,@TOTALALERT
from elixiatech.customer
WHERE `customerno`= customernoParam;
UPDATE elixiatech.customer SET
            `customername`=primaryusernameParam,
            `industryid`=industryidParam,
            `salesid`=salesidParam,
            `customercompany`=custcompanyParam,
            `totalsms`= @TOTALSMS+custsmsParam,
           `smsleft`= @TOTALSMS+custsmsParam,
           `sms_balance_alert`='0',
            `total_tel_alert`= @TOTALALERT+custtelephonicalertParam,
            `tel_alertleft`= @TOTALALERT+custtelephonicalertParam,
            `use_msgkey`=cloadingParam,
            `use_geolocation`=cgeolocationParam,
            `use_tracking`=ctrakingParam,
            `use_maintenance`=cmaintenanceParam,
            `use_portable`=cportableParam,
            `temp_sensors`=ctempsensorParam,
            `use_hierarchy`=cheirarchyParam,
            `use_advanced_alert`= advanced_alertParam,
            `use_ac_sensor`= cacParam,
            `use_genset_sensor`= cgensetParam,
            `use_fuel_sensor`= cfuelParam,
            `use_panic`= cpanicParam,
            `use_buzzer`= cbuzzerParam,
            `use_immobiliser`= cimmoParam,
            `use_mobility`= cimobParam,
            `use_sales` = csalesengageParam,
            `use_door_sensor`= cdoorParam,
            `use_delivery`= cdeliveryParam,
            `use_routing`= croutingParam,
            `unitprice`= cunitpriceParam,
            `unit_msp`= cunitmspParam,
            `renewal`= custrenewalParam,
            `timezone`=timezoneParam,
            `lease_duration`= leasedurationParam,
            `lease_price`= leasepriceParam,
            `commercial_details`=comdetailsParam
            WHERE customerno = customernoParam;
BEGIN
Select `smsleft` INTO @smsleft from speed.customer
where customerno = customernoParam;
INSERT INTO speed.trans_history (`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
VALUES (customernoParam, 0, teamidParam, 2, todaydatetimeParam, 0, concat("SMS Added :",custsmsParam,"Total SMS:",@smsleft));

INSERT INTO elixiatech.trans_history (`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
VALUES (customernoParam, 0, teamidParam, 2, todaydatetimeParam, 0, concat("SMS Added :",custsmsParam,"Total SMS:",@smsleft));
END;

BEGIN
Select `tel_alertleft` INTO @telalertleft from speed.customer
where customerno = customernoParam;
INSERT INTO speed.trans_history (`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
VALUES (customernoParam, 0, teamidParam, 2, todaydatetimeParam, 0, concat("Telephonice Alerts Added :",custtelephonicalertParam,"Telephonice Alerts:",@telalertleft));

INSERT INTO elixiatech.trans_history (`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
VALUES (customernoParam, 0, teamidParam, 2, todaydatetimeParam, 0, concat("Telephonice Alerts Added :",custtelephonicalertParam,"Total Telephonice Alerts:",@telalertleft));
END; 

END$$

DELIMITER ;


/*
    Name          - edit_customer_details
    Description   - Update additional details of customer
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 12-02-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `edit_customer_details`$$
CREATE PROCEDURE `edit_customer_details`(
     IN typeParam INT(11)
    ,IN primaryusernameParam VARCHAR(100)
    ,IN email1Param VARCHAR(50)
    ,IN email2Param VARCHAR(50)
    ,IN phone1Param VARCHAR(50)
    ,IN phone2Param VARCHAR(50)
    ,IN customenoParam VARCHAR(100)
    ,IN teamidParam INT(11)
    ,IN todaydatetimeParam datetime
    ,IN cpidParam INT(11)
)

BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;
UPDATE speed.contactperson_details 
SET 
typeid = typeParam ,
person_name = primaryusernameParam,
cp_email1 = email1Param,
cp_email2 = email2Param,
cp_phone1 = phone1Param,
cp_phone2 = phone2Param,
updatedby = teamidParam,
updatedon = todaydatetimeParam 
WHERE customerno = customenoParam AND cpdetailid = cpidParam;

UPDATE elixiatech.contactperson_details 
SET 
typeid = typeParam ,
person_name = primaryusernameParam,
cp_email1 = email1Param,
cp_email2 = email2Param,
cp_phone1 = phone1Param,
cp_phone2 = phone2Param,
updatedby = teamidParam,
updatedon = todaydatetimeParam 
WHERE customerno = customenoParam AND cpdetailid = cpidParam;


END$$

DELIMITER ;

/*
    Name          - edit_secondary_user
    Description   - Update user 
    Parameters    - 

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 12-02-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/

DELIMITER $$
DROP procedure IF EXISTS `edit_secondary_user`$$
CREATE PROCEDURE `edit_secondary_user`(
     IN useridParam INT(11)
    ,IN nameParam VARCHAR(50)
    ,IN usernameParam VARCHAR(50)
    ,IN emailParam VARCHAR(50)
    ,IN passwordParam VARCHAR(150)
    ,IN phonenoParam VARCHAR(15)
    ,IN roleParam VARCHAR(150)
    ,IN roleidParam INT(11)
    ,IN groupidParam INT(11)
    )
BEGIN
  UPDATE speed.user 
  SET
                        `realname` = nameParam,
                        `username`= usernameParam,
                        `password`= sha1(passwordParam),
                        `role`= roleParam,
                        `roleid`= roleidParam,
                        `email`= emailParam,
                        `phone`= phonenoParam,
                        `groupid`= groupidParam
WHERE `userid` = useridParam;

UPDATE elixiatech.user 
  SET
                        `realname` = nameParam,
                        `username`= usernameParam,
                        `password`= sha1(passwordParam),
                        `role`= roleParam,
                        `roleid`= roleidParam,
                        `email`= emailParam,
                        `phone`= phonenoParam,
                        `groupid`= groupidParam
WHERE `userid` = useridParam;


            



END$$

DELIMITER ;

/*
    Name          - delete_customer
    Description   - Remove customer from team
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 12-02-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `delete_customer`$$
CREATE PROCEDURE `delete_customer`(
     IN teamidParam INT(11)
    ,IN todaydatetimeParam datetime
    ,IN cpidParam INT(11)
    ,IN customenoParam VARCHAR(100)

 
)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;

UPDATE speed.contactperson_details 
SET isdeleted = 1 , 
updatedby = teamidParam ,
updatedon = todaydatetimeParam
WHERE cpdetailid = cpidParam AND customerno = customenoParam; 

UPDATE elixiatech.contactperson_details 
SET isdeleted = 1 , 
updatedby = teamidParam ,
updatedon = todaydatetimeParam
WHERE cpdetailid = cpidParam AND customerno = customenoParam; 

END$$

DELIMITER ;



UPDATE dbpatches SET isapplied = 1, patchdate = '2018-02-14 13:00:00' where patchid = 540;