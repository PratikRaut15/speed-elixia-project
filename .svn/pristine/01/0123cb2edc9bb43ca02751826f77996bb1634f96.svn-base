INSERT INTO `speed`.`dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`) VALUES ('700', '2019-04-18 16:30:00', 'Yash Kanakia', 'Insert Customer Changes');

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
  IN radarParam INT,
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
       /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error;  
           SET isexecutedOut = 0; */
       END;

        SET isexecutedOut = 0;

       START TRANSACTION;
       BEGIN
            INSERT INTO customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,
            `use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,`use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`,
            `use_fuel_sensor`, `use_door_sensor`, `use_routing`,`use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`, `use_delivery`,`use_sales`,`use_erp`,`use_warehouse`,
            `use_trace`,`use_controlTower`,`use_crm`,`use_books`,`use_radar`, `commercial_details`,`timezone`,`createdtime`)
            VALUES (customernameParam, customercompanyParam, dateaddedParam, totalsmsParam, totalsmsParam, total_alertParam, total_alertParam, teamidParam, loadingParam,
             locationParam, trackingParam, maitenanceParam,tempSensorsParam, portableParam, hierarchyParam,advanceAlertsParam,acSensorParam,gensetSensorParam,
             fuelSensorParam,doorSensorParam,routeParam,panicParam,buzzerParam,immobilizerParam,mobilityParam,deliveryParam,salesParam,erpParam,warehouseParam,traceParam,controlTowerParam,crmParam,booksParam,radarParam,commercial_detailsParam,
             timezoneParam,createdTimeParam);

            SET customerIdOut = LAST_INSERT_ID();

            INSERT INTO trans_history (
            `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
            VALUES (customerIdOut, 0, teamidParam, 2, createdTimeParam, 0, CONCAT("SMS Added:",totalsmsParam,"Total SMS:",totalsmsParam));

            INSERT INTO trans_history (
            `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
            VALUES (customerIdOut, 0, teamidParam, 2, createdTimeParam, 0, CONCAT("Telephonic Alerts Added :",total_alertParam,"Total SMS:",total_alertParam));

			INSERT INTO elixiatech.customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,
            `use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,`use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`,
            `use_fuel_sensor`, `use_door_sensor`, `use_routing`,`use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`, `use_delivery`,`use_sales`,`use_erp`,`use_warehouse`,
            `use_trace`,`use_controlTower`,`use_crm`,`use_books`,`use_radar`, `commercial_details`,`timezone`,`createdtime`)
            VALUES (customernameParam, customercompanyParam, dateaddedParam, totalsmsParam, totalsmsParam, total_alertParam, total_alertParam, teamidParam, loadingParam,
             locationParam, trackingParam, maitenanceParam,tempSensorsParam, portableParam, hierarchyParam,advanceAlertsParam,acSensorParam,gensetSensorParam,
             fuelSensorParam,doorSensorParam,routeParam,panicParam,buzzerParam,immobilizerParam,mobilityParam,deliveryParam,salesParam,erpParam,warehouseParam,traceParam,controlTowerParam,crmParam,booksParam,radarParam,commercial_detailsParam,
             timezoneParam,createdTimeParam);

            SET isExecutedOut = 1;
       END;
       COMMIT;
END$$

DELIMITER ;



UPDATE  dbpatches
SET     patchdate = '2019-04-18 16:30:00'
        ,isapplied =1
WHERE   patchid = 700;