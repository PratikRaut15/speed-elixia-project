DROP PROCEDURE `authenticate_for_login`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `authenticate_for_login`(IN `usernameparam` VARCHAR(50), IN `passparam` VARCHAR(150), IN `todaydt` DATETIME, OUT `usertype` INT, OUT `userkeyparam` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
	DECLARE useridparam INT;
    DECLARE tempPwdParam VARCHAR(150);

	
	SET usertype = 0;
	SET userkeyparam = 0;
    
	SELECT  userid,userkey
	INTO	useridparam , userkeyparam
	FROM    user
	WHERE   username = usernameparam
	AND 	password = passparam
	AND 	isdeleted = 0;
	
	IF (useridparam IS NULL)THEN            
		BEGIN
			SELECT 	f.userid, u.userkey
            INTO 	useridparam, userkeyparam
			FROM 	forgot_password_request AS f
            INNER JOIN	user AS u ON u.userid = f.userid
			WHERE 	f.username = usernameparam 
			AND 	CAST(SHA1(f.otp) AS BINARY) = CAST(passparam AS BINARY)
			AND 	f.isdeleted = 0
            AND 	u.isdeleted = 0
			AND 	f.isused = 0
			AND 	f.request_counter <= 3
			AND 	f.validupto BETWEEN todaydt AND DATE_ADD(todaydt, INTERVAL 24 HOUR);
		END;
		IF (useridparam IS NOT NULL) THEN
			BEGIN
				
				SET usertype = 1;
			END; 
		END IF;
	END IF;

	IF (useridparam IS NOT NULL AND usertype = 0)THEN 
		BEGIN
			
					SELECT  	*
					FROM		user
                    INNER JOIN 	elixiatech.customer ON user.customerno = customer.customerno
                    INNER JOIN 	android_version
					WHERE   	userid = useridparam AND isdeleted =0;
				END;
	END IF;

END


DROP PROCEDURE `delete_ledger`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `delete_ledger`(IN `ledgeridparam` INT, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN UPDATE elixiatech.ledger SET isdeleted = 1 ,updatedby = updatedby ,updatedon = updatedon WHERE ledgerid = ledgeridparam ; END 

DROP PROCEDURE `delete_ledger_cust_mapping`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `delete_ledger_cust_mapping`(IN `ledgeridparam` INT, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN UPDATE elixiatech.ledger_cust_mapping SET isdeleted = 1 ,updatedby = updatedby ,updatedon = updatedon WHERE ledgerid = ledgeridparam ; END 

DROP PROCEDURE `delete_ledger_veh_mapping`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `delete_ledger_veh_mapping`(IN `ledger_veh_mapidparam` INT, IN `customernoparam` INT, IN `ledgeridparam` INT, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN SET ledger_veh_mapidparam = NULL; END IF; IF(customernoparam = '' OR customernoparam = '0') THEN SET customernoparam = NULL; END IF; IF(ledgeridparam = '' OR ledgeridparam = '0') THEN SET ledgeridparam = NULL; END IF; UPDATE elixiatech.ledger_veh_mapping as lv SET lv.isdeleted = 1 ,lv.updatedby = updatedby ,lv.updatedon = updatedon WHERE (lv.ledger_veh_mapid = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL) AND (lv.customerno = customernoparam OR customernoparam IS NULL) AND (lv.ledgerid = ledgeridparam OR ledgeridparam IS NULL) ; END 

DROP PROCEDURE `delete_outdated_forgotpass_user`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `delete_outdated_forgotpass_user`(IN `today` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN UPDATE elixiatech.forgot_password_request SET isdeleted = 1 WHERE today > validupto; END  

DROP PROCEDURE `delete_po`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `delete_po`(IN `poidparam` INT, IN `custparam` INT, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN UPDATE elixiatech.po SET isdeleted = 1 ,updatedby = updatedby ,updatedon = updatedon WHERE poid = poidparam AND customerno = custparam ; END 

DROP PROCEDURE `delete_role`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `delete_role`(IN `roleid` INT, IN `custno` INT, IN `todaysdate` DATETIME, IN `userid` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
	DECLARE currentparentroleid INT;
	DECLARE childroleid INT;
	
        SELECT parentroleid into currentparentroleid from role 
        WHERE  id = roleid;

        SELECT id into childroleid from role 
        WHERE parentroleid = roleid;
        
        IF (currentparentroleid is NOT NULL && currentparentroleid != 0) THEN
            BEGIN
                IF(childroleid IS NOT NULL && childroleid != 0) THEN
                    
                    UPDATE elixiatech.role
                    SET     parentroleid = currentparentroleid
                    WHERE   id = childroleid
                    AND     customerno = custno
                    AND     isdeleted = 0;

                END IF;
                
                UPDATE elixiatech.role
                SET     isdeleted = 1
                WHERE   id = roleid 
                AND     customerno = custno;
            END;
            
            CALL sequence_role(custno);
        END IF;
END

DROP PROCEDURE `get_ledger`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_ledger`(IN `ledgeridparam` INT, IN `ledgernameparam` VARCHAR(100)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN IF(ledgeridparam = '' OR ledgeridparam = '0') THEN SET ledgeridparam = NULL; END IF; IF(ledgernameparam = '' OR ledgernameparam = '0') THEN SET ledgernameparam = NULL; END IF; SELECT l.ledgerid ,l.ledgername ,l.address1 ,l.address2 ,l.address3 ,l.email ,l.phone ,l.pan_no ,l.cst_no ,l.st_no ,l.vat_no ,l.createdby ,l.createdon ,l.updatedby ,l.updatedon FROM elixiatech.ledger AS l WHERE (l.ledgerid = ledgeridparam OR ledgeridparam IS NULL) AND (l.ledgername LIKE CONCAT('%', ledgernameparam, '%') OR ledgernameparam IS NULL) AND l.isdeleted = 0 ORDER BY l.updatedon DESC ; END 

DROP PROCEDURE `get_ledger_cust_mapping`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_ledger_cust_mapping`(IN `ledgeridparam` INT, IN `customernoparam` INT, IN `ledgernameparam` VARCHAR(100)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
	IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
		SET ledgeridparam = NULL;
	END IF;

	IF(ledgernameparam = '' OR ledgernameparam = '0') THEN
		SET ledgernameparam = NULL;
	END IF;
    
    IF(customernoparam = '' OR customernoparam = '0') THEN
		SET customernoparam = NULL;
	END IF;
    
SELECT 	l.ledgerid
		,l.ledgername
        ,l.address1
        ,l.address2
        ,l.address3
        ,l.email
        ,l.phone
        ,l.pan_no
        ,l.cst_no
        ,l.st_no
        ,l.vat_no
        ,lc.customerno
FROM elixiatech.ledger_cust_mapping AS lc
INNER JOIN elixiatech.ledger AS l ON l.ledgerid = lc.ledgerid
WHERE (lc.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
AND (lc.customerno  = customernoparam OR customernoparam IS NULL)
AND (l.ledgername LIKE CONCAT('%', ledgernameparam, '%') OR ledgernameparam IS NULL)
AND lc.isdeleted = 0
;     
END

DROP PROCEDURE `get_ledger_veh_mapping`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_ledger_veh_mapping`(IN `ledger_veh_mapidparam` INT, IN `customernoparam` INT, IN `ledgeridparam` INT, IN `vehiclenoparam` VARCHAR(20)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN

IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
 SET ledger_veh_mapidparam = NULL;
END IF;

IF(customernoparam = '' OR customernoparam = '0') THEN
 SET customernoparam = NULL;
END IF;

IF(vehiclenoparam = '' OR vehiclenoparam = '0') THEN
 SET vehiclenoparam = NULL;
END IF;

IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
 SET ledgeridparam = NULL;
END IF;

SELECT 	l.ledger_veh_mapid
		,l.ledgerid
		,l.vehicleid
        ,l.customerno
        ,v.vehicleno
		,l.createdby
        ,l.createdon
        ,l.updatedby
        ,l.updatedon
FROM elixiatech.ledger_veh_mapping as l
INNER JOIN vehicle as v ON l.vehicleid = v.vehicleid
WHERE (l.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
AND (l.customerno = customernoparam OR customernoparam IS NULL)
AND (l.ledgerid = ledgeridparam OR ledgeridparam IS NULL)
AND (v.vehicleno LIKE CONCAT('%', vehiclenoparam, '%') OR vehiclenoparam IS NULL)
AND l.isdeleted = 0
ORDER BY v.vehicleno ASC
;
END

DROP PROCEDURE `get_po`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_po`(IN `customernoparam` INT, IN `poidparam` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN IF(customernoparam = '' OR customernoparam = '0') THEN SET customernoparam = NULL; END IF; IF(poidparam = '' OR poidparam = '0') THEN SET poidparam = NULL; END IF; SELECT poid ,pono ,podate ,poamount ,poexpiry ,description ,customerno ,createdby ,createdon ,updatedby ,updatedon FROM elixiatech.po WHERE (customerno = customernoparam OR customernoparam IS NULL) AND (poid = poidparam OR poidparam IS NULL) AND isdeleted = 0 ; END 

DROP PROCEDURE `get_role`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_role`(IN `rid` INT, IN `parentid` INT, IN `mid` INT, IN `custno` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
        IF(rid = '' OR rid = 0) THEN
		SET rid = NULL;
	END IF;
        IF(parentid = '' OR parentid = 0) THEN
		SET parentid = NULL;
	END IF;
        IF(mid = '' OR mid = 0) THEN
		SET mid = NULL;
	END IF;
        IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;

	SELECT 
            id
            , role
            , parentroleid as pid
            , (select role FROM elixiatech.role where id=pid and pid<>0)as prole
            , role.moduleid
            , customerno
            , modulename
	FROM elixiatech.role
        INNER JOIN elixiatech.modules on modules.moduleid = role.moduleid
        WHERE (id = rid OR rid IS NULL)
        AND (parentroleid = parentid OR parentid IS NULL)
        AND (role.moduleid = mid OR mid IS NULL)
        AND (customerno = custno OR custno IS NULL)
        AND role.isdeleted=0 order by sequenceno ASC;

END

DROP PROCEDURE `get_role_mapping`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_role_mapping`(IN `useridparam` INT, IN `custno` INT, IN `moduleidparam` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN IF(useridparam = '' OR useridparam = 0) THEN SET useridparam = NULL; END IF; IF(custno = '' OR custno = 0) THEN SET custno = NULL; END IF; IF(moduleidparam = '' OR moduleidparam = 0) THEN SET moduleidparam = NULL; END IF; IF(moduleidparam = 2)THEN SELECT maintenanceroleid as roleid , maintenancerole as role FROM elixiatech.rolemapping WHERE (userid = useridparam OR useridparam IS NULL) AND (customerno = custno OR custno IS NULL) AND isdeleted = 0; ELSE SELECT roleid, role FROM user WHERE (userid = useridparam OR useridparam IS NULL) AND (customerno = custno OR custno IS NULL) AND isdeleted = 0; END IF; END 

DROP PROCEDURE `get_transactionrules`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_transactionrules`(IN `custno` INT, IN `ruleidparam` INT, IN `conditionidparam` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(ruleidparam = '' OR ruleidparam = 0) THEN
        SET ruleidparam = NULL;
    END IF;
    IF(conditionidparam = '' OR conditionidparam = 0) THEN
        SET conditionidparam = NULL;
    END IF;

    SELECT 
        mr.ruleid
        ,mr.conditionid
        ,mr.minval
        ,mr.maxval
        ,mr.sequenceno
        ,mr.approverid
        ,mc.conditionname
        ,mt.categoryname
        ,roles.role
    FROM maintenance_rules as mr
    INNER JOIN maintenance_conditions as mc on mc.conditionid = mr.conditionid
    INNER JOIN maintenance_transactiontype as mt on mt.transactiontypeid = mc.transactiontypeid
    INNER JOIN elixiatech.role as roles on roles.id = mr.approverid
    WHERE (mr.ruleid = ruleidparam OR ruleidparam IS NULL)
    AND   (mr.conditionid = conditionidparam OR conditionidparam IS NULL)
    AND   (mr.customerno = custno OR custno IS NULL)
    AND   mr.isdeleted = 0
    ORDER BY mr.ruleid ASC;

END

DROP PROCEDURE `get_transmitter`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_transmitter`(IN `transmitteridparam` INT, IN `transmitternoparam` VARCHAR(25), IN `teamidparam` VARCHAR(20), IN `trans_statusparam` VARCHAR(20), IN `customernoparam` VARCHAR(20)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
	IF(transmitteridparam = '' OR transmitteridparam = '0') THEN
		SET transmitteridparam = NULL;
	END IF;

	IF(customernoparam = '') THEN
		SET customernoparam = NULL;
	 ELSE 
		SET customernoparam = CAST(customernoparam AS SIGNED INTEGER);
	END IF;

	IF(trans_statusparam = '') THEN
	 SET trans_statusparam = NULL;
	END IF;

	IF(transmitternoparam = '') THEN
	 SET transmitternoparam = NULL;
	END IF;

	IF(teamidparam = '') THEN
		SET teamidparam = NULL;
	 ELSE 
		SET teamidparam = CAST(teamidparam AS SIGNED INTEGER);
	END IF;

	SELECT t.transmitterid
			,t.transmitterno
			,t.teamid
			,t.comments
			,t.customerno
			,t.trans_status
			,t.created_on
			,t.updated_on
			,t.created_by
			,t.updated_by
			,ts.`status`
	FROM  transmitter as t
	INNER JOIN elixiatech.trans_status AS ts ON ts.id = t.trans_status
	WHERE (t.transmitterno  = transmitteridparam OR transmitteridparam IS NULL)
	AND (t.transmitterno = transmitternoparam OR transmitternoparam IS NULL)
	AND (t.teamid = teamidparam OR teamidparam IS NULL)
	AND (t.customerno IN (customernoparam) OR customernoparam IS NULL)
	AND (FIND_IN_SET(t.trans_status,trans_statusparam) OR trans_statusparam IS NULL)
	AND t.isdeleted = 0
	ORDER BY t.transmitterno ASC
;        
END

DROP PROCEDURE `get_users_forparentrole`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_users_forparentrole`(IN `roleidparam` INT, IN `moduleidparam` INT, IN `custno` INT, IN `isHigherUser` TINYINT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
	DECLARE parentroleidparam VARCHAR(30);

	SELECT GROUP_CONCAT(T2.id SEPARATOR ',') INTO parentroleidparam
	FROM 	(
				SELECT
					@r AS _id,
					(SELECT @r := parentroleid FROM elixiatech.role WHERE id = _id) AS parent_id,
					@l := @l + 1 AS lvl
				FROM
					(SELECT @r := roleidparam, @l := 0) vars INNER JOIN elixiatech.role WHERE @r <> 0
			) T1
	INNER JOIN elixiatech.role T2	ON T1._id = T2.id
	WHERE 	T2.customerno = custno
	AND  	T2.moduleid = moduleidparam
	AND  	T2.isdeleted = 0 
	AND		CASE  
				WHEN isHigherUser = 1 THEN T1.lvl > 2
				ELSE T1.lvl = 2
			END
	ORDER BY T1.lvl DESC; 
    
    
    IF(parentroleidparam != 0) THEN
		BEGIN
			SELECT 	  userid
					, username
					, realname
					, email
			FROM 	user 
			WHERE 	FIND_IN_SET(roleid,parentroleidparam)
			AND 	TRIM(LOWER(realname)) != 'elixir'
			AND customerno = custno
			AND isdeleted =0;
		END;
    END IF;

END

DROP PROCEDURE `get_vehiclewarehouse_details`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `get_vehiclewarehouse_details`(IN `pageIndex` INT, IN `pageSize` INT, IN `custnoparam` INT, IN `isWareHouse` TINYINT, IN `searchstring` VARCHAR(40), IN `groupidparam` VARCHAR(250), IN `userkeyparam` INT, IN `isRequiredThirdPartyParam` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
    DECLARE recordCount INT;
	DECLARE fromRowNum INT DEFAULT 1;
	DECLARE toRowNum INT DEFAULT 1;
	DECLARE roleIdparam INT;
	DECLARE useridparam INT;
    SET searchstring = LTRIM(RTRIM(searchstring));
    IF searchstring = '' THEN
		SET searchstring = NULL;
	END IF;
    IF groupidparam = '0' ||  groupidparam = '' THEN
		SET groupidparam = NULL;
    END IF;
	IF userkeyparam = '0' ||  userkeyparam = '' THEN
		SET userkeyparam = NULL;
    END IF;
	IF isRequiredThirdPartyParam = '0' THEN
		SET isRequiredThirdPartyParam = NULL;
    END IF;

	IF (userkeyparam IS NOT NULL) then
		SELECT roleid,userid INTO roleIdparam ,useridparam
		FROM user where user.userkey = userkeyparam
		AND customerno = custnoparam;
	END IF;	

    IF (roleIdparam != 43)THEN 
		SET recordCount =  (SELECT 		COUNT(vehicle.vehicleid) 
						FROM 		vehicle 
						INNER JOIN 	devices ON devices.uid = vehicle.uid 
						INNER JOIN 	driver ON driver.driverid = vehicle.driverid 
						INNER JOIN 	unit ON devices.uid = unit.uid 
						INNER JOIN 	elixiatech.customer ON customer.customerno = vehicle.customerno 
						INNER JOIN 	ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid 
						INNER JOIN 	simcard on simcard.id = devices.simcardid
						LEFT JOIN   vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child=custnoparam
						WHERE 		(vehicle.customerno = custnoparam OR vehicle.customerno = vr.parent) 
						
						AND 		unit.trans_statusid NOT IN (10,22) 
						AND			vehicle.isdeleted = 0 
						AND 		driver.isdeleted = 0 
						AND 		devices.lastupdated <> '0000-00-00 00:00:00' 
                        AND 		(
										CASE 
											WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
											ELSE vehicle.kind !='Warehouse' 
										END
									)
						AND 		(vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                        AND			(FIND_IN_SET(vehicle.groupid, groupidparam) OR groupidparam IS NULL)
						AND			(unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
					   );
                        
	
	IF (pageSize = -1) THEN
		SET pageSize = recordCount;
    END IF;
    
	SET fromRowNum = (pageIndex - 1) * pageSize + 1;
	SET toRowNum = (fromRowNum + pageSize) - 1;
	SET @rownum = 0;

	SELECT	*, recordCount
	FROM 	(SELECT  @rownum:=@rownum + 1 AS rownum, vehDetails.*
			 FROM	(SELECT 	vehicle.kind
								, vehicle.groupid
								, vehicle.groupid as veh_grpid
								, vehicle.vehicleid
								, vehicle.overspeed_limit
								, vehicle.extbatt
								, vehicle.vehicleno
								, vehicle.curspeed
								, vehicle.temp1_min
								, vehicle.temp1_max
								, vehicle.temp2_min
								, vehicle.temp2_max
								, vehicle.temp3_min
								, vehicle.temp3_max
								, vehicle.temp4_min
								, vehicle.temp4_max
								, vehicle.sequenceno
								, vehicle.stoppage_flag
								, devices.lastupdated
								, devices.ignition
								, devices.inbatt
								, devices.tamper
								, devices.powercut
								, devices.gsmstrength
								, devices.devicelat
								, devices.devicelong
                                , devices.directionchange
								, driver.drivername
								, driver.driverphone
								, unit.tempsen1
								, unit.tempsen2
								, unit.tempsen3
								, unit.tempsen4
								, unit.analog1
								, unit.analog2
								, unit.analog3
								, unit.analog4
								, unit.unitno
								, unit.digitalio
								, unit.acsensor
								, unit.is_ac_opp
                                , unit.is_freeze
								, elixiatech.customer.temp_sensors
								, elixiatech.customer.use_humidity
								, elixiatech.customer.use_geolocation
								, elixiatech.customer.customercompany
								, vehicle.customerno as customer_no
								, simcardid
								, simcardno
								, (SELECT customname 
									FROM customfield 
									WHERE customerno=vehicle.customerno 
									AND LTRIM(RTRIM(name)) = 'Digital'
									AND usecustom = 1) AS digital
								, ignitionalert.status AS igstatus
								, ignitionalert.ignchgtime
					FROM 		vehicle 
					INNER JOIN 	devices ON devices.uid = vehicle.uid 
					INNER JOIN 	driver ON driver.driverid = vehicle.driverid 
					INNER JOIN 	unit ON devices.uid = unit.uid 
					INNER JOIN 	elixiatech.customer ON customer.customerno = vehicle.customerno 
					INNER JOIN 	ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid 
					     
					INNER JOIN 	simcard on simcard.id = devices.simcardid
                    			LEFT JOIN   vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child=custnoparam
					WHERE 		(vehicle.customerno = custnoparam OR vehicle.customerno = vr.parent) 
					
					AND 		unit.trans_statusid NOT IN (10,22) 
					AND			vehicle.isdeleted = 0 
					AND 		driver.isdeleted = 0 
					AND 		devices.lastupdated <> '0000-00-00 00:00:00'
					AND 		(
									CASE 
										WHEN isWareHouse = 1 THEN vehicle.kind = 'Warehouse'
										ELSE vehicle.kind != 'Warehouse' 
									END
								)
					AND 		(vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
					AND			(FIND_IN_SET(vehicle.groupid, groupidparam) OR groupidparam IS NULL)
					AND			(unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
					ORDER BY	CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END,
								vehicle.sequenceno ASC, 
								vehicle.vehicleno ASC
					) vehDetails
			) vehDetails
            
	WHERE		rownum BETWEEN fromRowNum AND toRowNum
    ORDER BY 	rownum;


	ELSE 


		SET recordCount =  (SELECT 		COUNT(vehicle.vehicleid) 
						FROM 		vehicle 
						INNER JOIN 	devices ON devices.uid = vehicle.uid 
						INNER JOIN 	driver ON driver.driverid = vehicle.driverid 
						INNER JOIN 	unit ON devices.uid = unit.uid 
						INNER JOIN 	elixiatech.customer ON customer.customerno = vehicle.customerno 
						INNER JOIN 	ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid 
						INNER JOIN 	simcard on simcard.id = devices.simcardid
						LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child=custnoparam
						INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = useridparam
						WHERE 		(vehicle.customerno = custnoparam OR vehicle.customerno = vr.parent) 
						
						AND 		unit.trans_statusid NOT IN (10,22) 
						AND			vehicle.isdeleted = 0 
						AND 		driver.isdeleted = 0 
						AND 		devices.lastupdated <> '0000-00-00 00:00:00' 
                        AND 		(
										CASE 
											WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
											ELSE vehicle.kind !='Warehouse' 
										END
									)
						AND 		(vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                        AND			(FIND_IN_SET(vehmap.groupid, groupidparam) OR groupidparam IS NULL)
						AND			(unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
					   );
                        
	
	IF (pageSize = -1) THEN
		SET pageSize = recordCount;
    END IF;
    
	SET fromRowNum = (pageIndex - 1) * pageSize + 1;
	SET toRowNum = (fromRowNum + pageSize) - 1;
	SET @rownum = 0;

	SELECT	*, recordCount
	FROM 	(SELECT  @rownum:=@rownum + 1 AS rownum, vehDetails.*
			 FROM	(SELECT 	vehicle.kind
								, vehicle.groupid
								, vehicle.groupid as veh_grpid
								, vehicle.vehicleid
								, vehicle.overspeed_limit
								, vehicle.extbatt
								, vehicle.vehicleno
								, vehicle.curspeed
								, vehicle.temp1_min
								, vehicle.temp1_max
								, vehicle.temp2_min
								, vehicle.temp2_max
								, vehicle.temp3_min
								, vehicle.temp3_max
								, vehicle.temp4_min
								, vehicle.temp4_max
								, vehicle.sequenceno
								, vehicle.stoppage_flag
								, devices.lastupdated
								, devices.ignition
								, devices.inbatt
								, devices.tamper
								, devices.powercut
								, devices.gsmstrength
								, devices.devicelat
								, devices.devicelong
                                , devices.directionchange
								, driver.drivername
								, driver.driverphone
								, unit.tempsen1
								, unit.tempsen2
								, unit.tempsen3
								, unit.tempsen4
								, unit.analog1
								, unit.analog2
								, unit.analog3
								, unit.analog4
								, unit.unitno
								, unit.digitalio
								, unit.acsensor
								, unit.is_ac_opp
                                , unit.is_freeze
								, elixiatech.customer.temp_sensors
								, elixiatech.customer.use_humidity
								, elixiatech.customer.use_geolocation
								, elixiatech.customer.customercompany
								, vehicle.customerno as customer_no
								, simcardid
								, simcardno
								, (SELECT customname 
									FROM customfield 
									WHERE customerno=vehicle.customerno 
									AND LTRIM(RTRIM(name)) = 'Digital'
									AND usecustom = 1) AS digital
								, ignitionalert.status AS igstatus
								, ignitionalert.ignchgtime
					FROM 		vehicle 
					INNER JOIN 	devices ON devices.uid = vehicle.uid 
					INNER JOIN 	driver ON driver.driverid = vehicle.driverid 
					INNER JOIN 	unit ON devices.uid = unit.uid 
					INNER JOIN 	elixiatech.customer ON customer.customerno = vehicle.customerno 
					INNER JOIN 	ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid 
					     
					INNER JOIN 	simcard on simcard.id = devices.simcardid
					LEFT JOIN   vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child=custnoparam
					INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = useridparam
					WHERE 		(vehicle.customerno = custnoparam OR vehicle.customerno = vr.parent) 
					
					AND 		unit.trans_statusid NOT IN (10,22) 
					AND			vehicle.isdeleted = 0 
					AND 		driver.isdeleted = 0 
					AND 		devices.lastupdated <> '0000-00-00 00:00:00'
					AND 		(
									CASE 
										WHEN isWareHouse = 1 THEN vehicle.kind = 'Warehouse'
										ELSE vehicle.kind != 'Warehouse' 
									END
								)
					AND 		(vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
					AND			(FIND_IN_SET(vehmap.groupid, groupidparam) OR groupidparam IS NULL)
					AND			(unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
					ORDER BY	CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END,
								vehicle.sequenceno ASC, 
								vehicle.vehicleno ASC
					) vehDetails
			) vehDetails
            
	WHERE		rownum BETWEEN fromRowNum AND toRowNum
    ORDER BY 	rownum;
	END IF;

END

DROP PROCEDURE `insert_forgot_password_request`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `insert_forgot_password_request`(IN `useridparam` INT, IN `todaysdate` DATETIME, OUT `otpparam` INT, OUT `otpvaliduptoparam` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
	DECLARE request_counterparam INT;
	DECLARE username_param varchar(50);
	DECLARE phone_param varchar(15);
    DECLARE minotp INT DEFAULT 100000;
    DECLARE maxotp INT DEFAULT 999999;
    
	IF EXISTS (	SELECT 	userid 
				FROM 	elixiatech.forgot_password_request  
				WHERE 	userid = useridparam 
				AND 	isused = 0 
				AND 	isdeleted = 0
				AND 	validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR) 
				LIMIT 1) THEN 
		BEGIN
			SELECT 	otp,request_counter, validupto 
			INTO 	otpparam, request_counterparam, otpvaliduptoparam
			FROM 	elixiatech.forgot_password_request 
			WHERE 	userid = useridparam 
			AND 	isused = 0 
			AND 	isdeleted = 0
			AND 	validupto between todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR) LIMIT 1;
            
			IF(request_counterparam = 3) THEN
				SET otpparam = -1; 
			ELSE
				UPDATE 	elixiatech.forgot_password_request 
				SET 	request_counter = request_counterparam + 1 
						, updated_on = todaysdate
				WHERE 	userid = useridparam 
				AND 	isused = 0 
				AND 	isdeleted = 0
				AND 	validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR);
			END IF;
		END;
	ELSE
		BEGIN
			SELECT 	phone, username 
			INTO 	phone_param, username_param  
			FROM 	user 
			WHERE 	userid = useridparam 
			AND 	isdeleted = 0;
            
            SET otpparam = FLOOR(RAND() * (maxotp - minotp + 1)) + minotp;
            SET otpvaliduptoparam = DATE_ADD(todaysdate,INTERVAL 24 HOUR);
			
			INSERT INTO elixiatech.forgot_password_request (
						  userid
						, username
						, phone
						, otp
						, validupto
						, isused
						, request_counter
                        , created_on
                        , updated_on
						) 
					VALUES	(
						 useridparam
						, username_param
						, phone_param
						, otpparam
						, otpvaliduptoparam
						, 0
						, 1
                        , todaysdate
                        , todaysdate
					);
		END;
	END IF;

END

DROP PROCEDURE `insert_ledger`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `insert_ledger`(IN `ledgername` VARCHAR(100), IN `address1` VARCHAR(100), IN `address2` VARCHAR(100), IN `address3` VARCHAR(100), IN `email` VARCHAR(40), IN `phone` VARCHAR(20), IN `pan_no` VARCHAR(30), IN `cst_no` VARCHAR(30), IN `st_no` VARCHAR(30), IN `vat_no` VARCHAR(30), IN `createdby` INT, IN `createdon` DATETIME, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
INSERT INTO elixiatech.ledger
(
	ledgername
    , address1 
    , address2 
    , address3
    , email 
    , phone 
    , pan_no 
    , cst_no 
    , st_no 
    , vat_no 
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
) VALUES
(
	ledgername 
    , address1 
    , address2 
    , address3
    , email 
    , phone 
    , pan_no 
    , cst_no 
    , st_no 
    , vat_no 
    , createdby 
    , createdon 
    , updatedby 
	, updatedon
)
    ;
END

DROP PROCEDURE `insert_ledger_cust_mapping`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `insert_ledger_cust_mapping`(IN `ledgerid` INT, IN `customerno` INT, IN `createdby` INT, IN `createdon` DATETIME, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN INSERT INTO elixiatech.ledger_cust_mapping ( ledgerid ,customerno , createdby , createdon , updatedby , updatedon ) VALUES ( ledgerid ,customerno , createdby , createdon , updatedby , updatedon ) ; END 

DROP PROCEDURE `insert_ledger_veh_mapping`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `insert_ledger_veh_mapping`(IN `vehicleid` INT, IN `ledgerid` INT, IN `customerno` INT, IN `createdby` INT, IN `createdon` DATETIME, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN INSERT INTO elixiatech.ledger_veh_mapping ( ledgerid ,vehicleid ,customerno , createdby , createdon , updatedby , updatedon ) VALUES ( ledgerid ,vehicleid ,customerno , createdby , createdon , updatedby , updatedon ) ; END 

DROP PROCEDURE `insert_po`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `insert_po`(IN `pono` VARCHAR(30), IN `podate` DATE, IN `poamount` INT, IN `poexpiry` DATE, IN `description` VARCHAR(50), IN `customerno` INT, IN `createdby` INT, IN `createdon` DATETIME, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN INSERT INTO elixiatech.po ( pono ,podate ,poamount ,poexpiry ,description ,customerno ,createdby ,createdon ,updatedby ,updatedon )VALUES( pono ,podate ,poamount ,poexpiry ,description ,customerno ,createdby ,createdon ,updatedby ,updatedon ) ; END 

DROP PROCEDURE `insert_role`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `insert_role`(IN `rolename` VARCHAR(50), IN `parentid` INT, IN `moduleid` INT, IN `custno` INT, IN `todaysdate` DATETIME, IN `userid` INT, OUT `currentroleid` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
    INSERT INTO elixiatech.role(
                        role
                        , parentroleid
                        , moduleid
                        , customerno
                        , created_on
                        , updated_on
                        , created_by
                        , updated_by
                    )
    VALUES ( 
            rolename
            , parentid
            , moduleid
            , custno
            , todaysdate
            , todaysdate
            , userid
            , userid
            );
            
    SET currentroleid = LAST_INSERT_ID();

    IF(parentid IS NOT NULL && parentid != 0) THEN
            Update elixiatech.role
            SET     parentroleid = currentroleid
            Where   parentroleid = parentid 
            AND id NOT IN(0, currentroleid);
    END IF;

    
    CALL sequence_role(custno);

END

DROP PROCEDURE `sequence_role`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `sequence_role`(IN `custno` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
        DECLARE roleid INT DEFAULT 0;
	DECLARE parentid INT DEFAULT 0;
	DECLARE counter INT DEFAULT 1;
        SET counter = 1;
        
        WHILE (counter != -1) DO
                SET     roleid = 0;
                SELECT  id INTO roleid FROM elixiatech.role
                WHERE   parentroleid = parentid
                AND     isdeleted = 0
                AND     customerno = custno;
                IF (roleid != 0) THEN
                    BEGIN
                        UPDATE  elixiatech.role
                        SET     sequenceno = counter
                        WHERE   id = roleid
                        AND     isdeleted = 0
                        AND     customerno = custno;

                        SET parentid = roleid;
                        SET counter = counter + 1;
                    END;
                ELSE
                    BEGIN
                        SET counter = -1;
                    END;
                END IF;
        END WHILE;
END

DROP PROCEDURE `team_cron_archive_knowledgebase_email`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `team_cron_archive_knowledgebase_email`(IN `emailidparam` INT, IN `cnoparam` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    START TRANSACTION;
    BEGIN
    INSERT INTO elixiatech.knowledgebase_emaillog_history(
    kb_emailid
    ,kbsid
    ,kb_to
    ,kb_from
    ,kb_subject
    ,kb_message
    ,islater
    ,laterdatetime
    ,issent
    ,customerno
    ,createdby
    ,createdon
    ,updatedby
    ,updatedon
    ,isdeleted
    )
    SELECT
		ke.kb_emailid
        ,ke.kbsid
        ,ke.kb_to
        ,ke.kb_from
        ,ke.kb_subject
        ,ke.kb_message
        ,ke.islater
        ,ke.laterdatetime
        ,ke.issent
        ,ke.customerno
        ,ke.createdby
        ,ke.createdon
        ,ke.updatedby
        ,ke.updatedon
        ,ke.isdeleted
    FROM knowledgebase_emaillog ke
    WHERE ke.kb_emailid = emailidparam
    AND ke.customerno = cnoparam
    ;
    call team_delete_knowledgebase_emaillog(cnoparam,emailidparam);
    
    COMMIT;
    END;
END

DROP PROCEDURE `team_delete_knowledgebase_emaillog`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `team_delete_knowledgebase_emaillog`(IN `custnoparam` INT, IN `emailidparam` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN DELETE FROM elixiatech.knowledgebase_emaillog WHERE customerno = custnoparam AND kb_emailid = emailidparam ; END 

DROP PROCEDURE `update_ledger`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `update_ledger`(IN `ledgeridparam` INT, IN `ledgername` VARCHAR(100), IN `address1` VARCHAR(100), IN `address2` VARCHAR(100), IN `address3` VARCHAR(100), IN `email` VARCHAR(40), IN `phone` VARCHAR(20), IN `pan_no` VARCHAR(30), IN `cst_no` VARCHAR(30), IN `st_no` VARCHAR(30), IN `vat_no` VARCHAR(30), IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN UPDATE elixiatech.ledger SET ledgername = ledgername , address1 = address1 , address2 = address2 , address3 = address3 , email = email , phone = phone , pan_no = pan_no , cst_no = cst_no , st_no = st_no , vat_no = vat_no , updatedby = updatedby , updatedon = updatedon WHERE ledgerid = ledgeridparam AND isdeleted = 0 ; END 

DROP PROCEDURE `update_newforgotpassword`;
CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `update_newforgotpassword`(IN `newpwdparam` VARCHAR(150), IN `userkeyparam` VARCHAR(150), IN `todaysdate` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
	DECLARE tempUserId INT;
    DECLARE tempForgotPwdUserId INT;
    SELECT 	userid
    INTO	tempUserId
    FROM	user
    WHERE	userkey = userkeyparam;
    
	IF (tempUserId IS NOT NULL) THEN
		SELECT 	userid
        INTO	tempForgotPwdUserId
        FROM	elixiatech.forgot_password_request
        WHERE	userid = tempUserId
        AND 	isused = 0
		AND 	isdeleted = 0
        AND 	request_counter <= 3
        AND 	validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR);
        
        IF (tempForgotPwdUserId IS NOT NULL) THEN
			BEGIN
				UPDATE 	user 
				SET 	chgpwd = 1
						, password = newpwdparam
				WHERE 	userid = tempUserId;

				UPDATE 	elixiatech.forgot_password_request
				SET 	isused = 1
						, updated_on = todaysdate
				WHERE 	userid = tempUserId
				AND 	isdeleted = 0
				AND 	isused = 0;
			END;
        END IF;
	END IF;
END

DROP PROCEDURE `update_po`; CREATE DEFINER=`EPolice`@`localhost` PROCEDURE `update_po`(IN `poidparam` INT, IN `pono` VARCHAR(30), IN `podate` DATE, IN `poamount` INT, IN `poexpiry` DATE, IN `description` VARCHAR(50), IN `customernoparam` INT, IN `updatedby` INT, IN `updatedon` DATETIME) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN UPDATE elixiatech.po SET pono = pono ,podate = podate ,poamount = poamount ,poexpiry = poexpiry ,description = description ,updatedby = updatedby ,updatedon = updatedon WHERE customerno = customernoparam AND poid = poidparam AND isdeleted = 0 ; END 