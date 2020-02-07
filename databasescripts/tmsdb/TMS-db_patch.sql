--
-- Table structure for table `depot`
--

CREATE TABLE IF NOT EXISTS `depot` (
  `depotid` int(11) NOT NULL AUTO_INCREMENT,
  `depotcode` varchar(20) NOT NULL,
  `depotname` varchar(50) NOT NULL,
  `locationid` int(11) NOT NULL,
  `zoneid` int(11) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`depotid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `factory`
--

CREATE TABLE IF NOT EXISTS `factory` (
  `factoryid` int(11) NOT NULL AUTO_INCREMENT,
  `factoryname` varchar(50) NOT NULL,
  `locationid` int(11) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`factoryid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `factory_delivery`
--

CREATE TABLE IF NOT EXISTS `factory_delivery` (
  `fdid` int(11) NOT NULL AUTO_INCREMENT,
  `factoryid` int(11) NOT NULL,
  `skuid` int(11) DEFAULT NULL,
  `depotid` int(11) DEFAULT NULL,
  `date_required` date DEFAULT NULL,
  `weight` float(6,2) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`fdid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `factory_production`
--

CREATE TABLE IF NOT EXISTS `factory_production` (
  `fpid` int(11) NOT NULL AUTO_INCREMENT,
  `factoryid` int(11) NOT NULL,
  `skuid` int(11) DEFAULT NULL,
  `weight` float(6,2) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`fpid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `indent`
--

CREATE TABLE IF NOT EXISTS `indent` (
  `indentid` int(11) NOT NULL AUTO_INCREMENT,
  `transporterid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `Proposedindentid` int(11) NOT NULL,
  `indent_sku_mappingid` int(11) NOT NULL,
  `shipmentno` varchar(50) DEFAULT NULL,
  `totalweight` float(6,2) DEFAULT NULL,
  `totalvolume` float(6,2) DEFAULT NULL,
  `isdelivered` tinyint(1) DEFAULT '0',
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`indentid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `indent_sku_mapping`
--

CREATE TABLE IF NOT EXISTS `indent_sku_mapping` (
  `indent_sku_mappingid` int(11) NOT NULL AUTO_INCREMENT,
  `indentid` int(11) NOT NULL,
  `skuid` int(11) NOT NULL,
  `no_of_units` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`indent_sku_mappingid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `locationid` int(11) NOT NULL AUTO_INCREMENT,
  `locationname` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`locationid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `proposed_indent`
--

CREATE TABLE IF NOT EXISTS `proposed_indent` (
  `proposedindentid` int(11) NOT NULL AUTO_INCREMENT,
  `factoryid` int(11) NOT NULL,
  `total_weight` float(6,2) DEFAULT NULL,
  `total_volume` float(6,2) DEFAULT NULL,
  `isApproved` tinyint(1) DEFAULT '0',
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`proposedindentid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `proposed_indent_transporter_mapping`
--

CREATE TABLE IF NOT EXISTS `proposed_indent_transporter_mapping` (
  `pitmappingid` int(11) NOT NULL AUTO_INCREMENT,
  `proposedindentid` int(11) NOT NULL,
  `proposed_transporterid` int(11) NOT NULL,
  `proposed_vehicletypeid` int(11) NOT NULL,
  `actual_vehicletypeid` int(11) DEFAULT NULL,
  `vehicleid` int(11) DEFAULT NULL,
  `isAccepted` tinyint(1) DEFAULT '0',
  `remarks` varchar(300) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`pitmappingid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `routecheckpoint`
--

CREATE TABLE IF NOT EXISTS `routecheckpoint` (
  `routecheckpointid` int(11) NOT NULL AUTO_INCREMENT,
  `routemasterid` int(11) NOT NULL,
  `fromlocationid` int(11) NOT NULL,
  `tolocationid` int(11) NOT NULL,
  `distance` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`routecheckpointid`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `routemaster`
--

CREATE TABLE IF NOT EXISTS `routemaster` (
  `routemasterid` int(11) NOT NULL AUTO_INCREMENT,
  `routename` varchar(20) NOT NULL,
  `fromlocationid` int(11) NOT NULL,
  `tolocationid` int(11) NOT NULL,
  `distance` int(11) NOT NULL,
  `travellingtime` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`routemasterid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `sku`
--

CREATE TABLE IF NOT EXISTS `sku` (
  `skuid` int(11) NOT NULL AUTO_INCREMENT,
  `skucode` varchar(100) NOT NULL,
  `sku_description` varchar(250) DEFAULT NULL,
  `volume` decimal(9,5) DEFAULT NULL,
  `weight` decimal(7,6) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`skuid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `skumanager`
--

CREATE TABLE IF NOT EXISTS `skumanager` (
  `skumid` int(11) NOT NULL AUTO_INCREMENT,
  `factoryid` int(11) NOT NULL,
  `skuid` int(11) NOT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`skumid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `transporter`
--

CREATE TABLE IF NOT EXISTS `transporter` (
  `transporterid` int(11) NOT NULL AUTO_INCREMENT,
  `transportercode` varchar(20) NOT NULL,
  `transportername` varchar(50) NOT NULL,
  `transportermail` varchar(50) NOT NULL,
  `transportermobileno` varchar(15) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`transporterid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `transportershare`
--

CREATE TABLE IF NOT EXISTS `transportershare` (
  `transportershareid` int(11) NOT NULL AUTO_INCREMENT,
  `transporterid` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  `sharepercent` decimal(6,2) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`transportershareid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `transporter_share_total`
--

CREATE TABLE IF NOT EXISTS `transporter_share_total` (
  `tstid` int(11) NOT NULL AUTO_INCREMENT,
  `transporterid` int(11) NOT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  `share` decimal(5,2) DEFAULT NULL,
  `insert_date` date DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`tstid`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE IF NOT EXISTS `vehicle` (
  `vehicleid` int(11) NOT NULL AUTO_INCREMENT,
  `transporterid` int(11) NOT NULL,
  `vehicletypeid` int(11) NOT NULL,
  `vehicleno` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`vehicleid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `vehicletype`
--

CREATE TABLE IF NOT EXISTS `vehicletype` (
  `vehicletypeid` int(11) NOT NULL AUTO_INCREMENT,
  `vehiclecode` varchar(20) NOT NULL,
  `vehicledescription` varchar(50) DEFAULT NULL,
  `volume` decimal(7,3) NOT NULL,
  `weight` decimal(7,3) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`vehicletypeid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `vehtypetransmapping`
--

CREATE TABLE IF NOT EXISTS `vehtypetransmapping` (
  `vtmid` int(11) NOT NULL AUTO_INCREMENT,
  `transporterid` int(11) NOT NULL,
  `vehicletypeid` int(11) NOT NULL,
  `vehiclecount` int(5) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`vtmid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `zone`
--

CREATE TABLE IF NOT EXISTS `zone` (
  `zoneid` int(11) NOT NULL AUTO_INCREMENT,
  `zonename` varchar(10) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`zoneid`)
);





DELIMITER $$
--
-- Procedures
--

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_depot`(
	IN did INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE depot 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE depotid = did;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_factory`(
	IN fid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE factory 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE factoryid = fid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_factory_delivery`( 
	IN fdidparam int
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	factory_delivery
	SET 	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE	fdid = fdidparam;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_factory_production`( 
	IN fpidparam int
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	factory_production
	SET 	isdeleted =1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	fpid = fpidparam;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_indent`( 
	IN indentidparam int
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	indent
	SET 	isdeleted = 1 
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	indentid = indentidparam;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_indent_sku_mapping`( 
	IN ismid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE  indent_sku_mapping
	SET 	isdeleted =1
			, updated_on = todaysdate
			, updated_by = userid
	WHERE 	indent_sku_mappingid = ismid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_location`(
	IN locid INT
	, IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
	UPDATE location 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE locationid = locid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_pit_mapping`( 
	IN pitmapid int
    , IN propindentid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF (pitmapid = '' OR pitmapid = 0) THEN
		SET pitmapid = NULL;
    END IF;
    IF (propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
    END IF;
	UPDATE  proposed_indent_transporter_mapping
	SET 	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	(pitmappingid = pitmapid	OR pitmapid IS NULL)
    AND 	(proposedindentid = propindentid OR propindentid IS NULL);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_proposed_indent`( 
	IN propindentid int
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	proposed_indent
	SET 	isdeleted = 1 
			,updated_on = todaysdate
            ,updated_by = userid
	WHERE 	proposedindentid = propindentid;
	CALL delete_pit_mapping(NULL,propindentid,todaysdate,userid);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_routecheckpoint`(
	IN routechkptid INT
    , IN routemstid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	IF routechkptid = '' OR routechkptid = 0 THEN
		SET routechkptid = NULL;
    END IF;
    IF routemstid = '' OR routemstid = 0 THEN
		SET routemstid = NULL;
    END IF;
	UPDATE 	routecheckpoint
    SET  	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	(routecheckpointid = routechkptid OR routechkptid IS NULL)
    AND 	(routemasterid = routemstid OR routemstid IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_routemaster`(
	IN rtmasterid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE routemaster 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE routemasterid = rtmasterid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_sku`( 
	IN skuidparam INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	sku
	SET  	isdeleted = 1
			, updated_on  = todaysdate
            , update_by = userid
	WHERE	skuid = skuidparam;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_transporter`(
	IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE transporter 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE transporterid = tranid;
    
    CALL delete_vehicle(NULL, tranid, todaysdate, userid);
    
    CALL delete_transportershare(NULL, tranid, todaysdate, userid);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_transportershare`(
	IN currenttransportershareid INT
    , IN currenttransporterid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	IF (currenttransportershareid = '' OR currenttransportershareid = 0) THEN
		SET currenttransportershareid = NULL;
    END IF;
    
    IF (currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
    END IF;
    
	UPDATE transportershare 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE 	(transportershareid = currenttransportershareid OR currenttransportershareid IS NULL)
    AND		(transporterid = currenttransporterid OR currenttransporterid Is NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_vehicle`(
	IN vehid INT
    ,IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	IF(vehid = '' OR vehid = 0) THEN
		SET vehid = NULL;
	END IF;
    IF(tranid = '' OR tranid = 0) THEN
		SET tranid = NULL;
	END IF;
	UPDATE vehicle 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE 	(vehicleid = vehid OR vehid IS NULL)
    AND		(transporterid = tranid OR tranid IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_vehicletype`(
	IN vehtypeid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE vehicletype 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE vehicletypeid = vehtypeid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_zone`(
	IN zid INT
	, IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	
	UPDATE zone 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE zoneid = zid;
     
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_depots`(
	IN custno INT
    , IN locid INT
    , IN zid INT
    , IN did INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(locid = '' OR locid = 0) THEN
		SET locid = NULL;
	END IF;
    IF(zid = '' OR zid = 0) THEN
		SET zid = NULL;
	END IF;
	IF(did = '' OR did = 0) THEN
		SET did = NULL;
	END IF;
	SELECT 	depotid
			, depotcode
            , depotname
            , d.zoneid
			, d.locationid
			, d.customerno
			, d.created_on
			, d.updated_on
			, z.zonename
			, l.locationname
   FROM depot as d
   INNER JOIN zone as z on z.zoneid = d.zoneid
   INNER JOIN location as l on l.locationid = d.locationid
   WHERE (d.customerno = custno OR custno IS NULL)
   AND 	(d.zoneid = zid OR zid IS NULL)
   AND 	(d.locationid = locid OR locid IS NULL)
   AND 	(d.depotid = did OR did IS NULL)
   AND 	d.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_factories`(
	IN custno INT
	, IN factid INT
	, IN locid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(factid = '' OR factid = 0) THEN
		SET factid = NULL;
	END IF;
	IF(locid = '' OR locid = 0) THEN
		SET locid = NULL;
	END IF;
	SELECT factoryid
			,factoryname
			,f.locationid
			,l.locationname
			,f.customerno
			,f.created_on
			,f.updated_on
			,l.locationname
			,f.created_by
			,f.updated_by
	FROM   	factory AS f INNER JOIN 
			location AS l ON l.locationid = f.locationid
	WHERE   (f.customerno = custno OR custno IS NULL)
	AND 	(f.factoryid = factid OR factid IS NULL)
    AND 	(f.locationid = locid OR locid IS NULL)
    AND		f.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_factory_delivery`(
	IN custno INT
    , IN fdidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(fdidparam = '' OR fdidparam = 0) THEN
		SET fdidparam = NULL;
	END IF;
	SELECT 	f.factoryid
			, f.factoryname
            , s.skuid
            , s.skucode
            , s.sku_description
            , d.depotid
            , d.depotname
            , date_required
            , weight
            , fd.customerno
	FROM 	factory_delivery fd
    INNER JOIN factory f ON f.factoryid = fd.factoryid
    INNER JOIN sku s ON s.skuid = fd.skuid
    INNER JOIN depot d ON d.depotid = fd.depotid
	WHERE 	(fd.customerno = custno OR custno IS NULL)
    AND 	(fdid = fdidparam OR fdidparam IS NULL)
	AND 	fd.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_factory_production`(
	IN custno INT
    , IN fpidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(fpidparam = '' OR fpidparam = 0) THEN
		SET fpidparam = NULL;
	END IF;
	SELECT 	f.factoryid
			, f.factoryname
            , s.skuid
            , s.skucode
            , s.sku_description
            , weight
            , customerno
            , date_required
            , weight
            , customerno
	FROM 	factory_production fp
    INNER JOIN factory f ON f.factoryid = fp.factoryid
    INNER JOIN sku s ON s.skuid = fp.skuid
	WHERE 	(fp.customerno = custno OR custno IS NULL)
    AND 	(fp.fpid = fpidparam OR fpidparam IS NULL)
	AND 	fp.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_indent`(
	IN custno INT
    , IN indentidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(indentidparam = '' OR indentidparam = 0) THEN
		SET indentidparam = NULL;
	END IF;
    
	SELECT 	 t.transporterid
			, v.vehicleid
            , pi.Proposedindentid
            , ism.indent_sku_mappingid
            , shipmentno
            , totalweight
            , totalvolume
            , isdelivered
            , customerno
			, ism.skuid
            , ism.no_of_units
            , s.skuname
            , i.customerno
	FROM 	indent i
    INNER JOIN transporter t ON t.transporterid = i.transporterid
    INNER JOIN indent_sku_mapping ism ON ism.indent_sku_mappingid = i.indent_sku_mappingid
    INNER JOIN sku s ON s.skuid = ism.skuid
	WHERE 	(customerno = custno OR custno IS NULL)
	AND		(indentid = indentidparam OR indentidparam IS NULL)
	AND 	i.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_locations`(
	IN custno INT
    , IN locid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(locid = '' OR locid = 0) THEN
		SET locid = NULL;
	END IF;
	SELECT locationid
			, locationname
			, customerno
			, created_on
			, updated_on
   FROM location
   WHERE (customerno = custno OR custno IS NULL)
   AND	(locationid = locid OR locid IS NULL)
   AND isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_proposed_indent`(
	IN custno INT
    , propindentid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
	SELECT 	 pi.factoryid
			, pit.pitmappingid
            , t.transportername
            , vehtype.vehiclecode
            , v.vehicleno
            , s.skuname
			, pi.indent_SKU_mappingid
			, total_weight
			, total_volume
			, pi.customerno
			, pi.created_on
			, pi.updated_on 
			, pi.created_by
			, pi.updated_by
	FROM 	proposed_indent pi
    INNER JOIN proposed_indent_transporter_mapping pit ON pit.proposedindentid = pi.proposedindentid
    INNER JOIN transporter t ON t.transporterid = pit.proposed_transporterid
    INNER JOIN vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
    INNER JOIN vehicle v ON v.vehicleid = pit.vehicleid
    INNER JOIN indent_sku_mapping ism ON ism.indentid = pi.proposedindentid
    INNER JOIN sku s ON s.skuid = ism.skuid
	WHERE 	(customerno = custno OR custno IS NULL)
    AND		(proposedindentid = propindentid OR propindentid IS NULL)
	AND 	pi.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_routecheckpoints`(
	IN custno INT
    , IN routechkptid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF routechkptid = '' OR routechkptid = 0 THEN
		SET routechkptid = NULL;
    END IF;
	SELECT 	r.routemasterid
			, r.routename
			, r.fromlocationid
            , fromloc.locationame
			, r.tolocationid
            , toloc.locationname
            , r.distance
			, r.customerno
   FROM routecheckpoint AS r
   INNER JOIN location AS fromloc ON fromloc.locationid = r.fromlocationid
   INNER JOIN location AS toloc ON toloc.locationid = r.tolocationid
   WHERE 	(r.customerno = custno OR custno IS NULL)
   AND		(r.routecheckpointid = routechkptid OR routechkptid IS NULL)
   AND 		r.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_routemaster`(
	IN custno INT
    , IN rtmasterid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(rtmasterid = '' OR rtmasterid = 0) THEN
		SET rtmasterid = NULL;
	END IF;
	SELECT 	r.routemasterid
			, r.routename
			, r.fromlocationid
            , fromloc.locationame
			, r.tolocationid
            , toloc.locationname
            , r.distance
            , r.travellingtime
			, r.customerno
   FROM routemaster AS r
   INNER JOIN location AS fromloc ON fromloc.locationid = r.fromlocationid
   INNER JOIN location AS toloc ON toloc.locationid = r.tolocationid
   WHERE 	(r.customerno = custno OR custno IS NULL)
   AND 		(r.routemasterid = rtmasterid OR rtmasterid IS NULL)
   AND 		r.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_sku`(
	IN custno INT
    , IN skuidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF (skuidparam = '' OR skuidparam = 0) THEN
		SET skuidparam = NULL;
    END IF;
	SELECT 	skucode
			, sku_description
			, volume
			, weight
			, customerno
	FROM 	sku
	WHERE 	(customerno = custno OR custno IS NULL)
    AND		(skuid = skuidparam OR skuidparam IS NULL)
	AND 	isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_skuweight`(
	IN custno INT
    	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
   
    
	SELECT 	 fd.skuid
            , sum(fd.weight)
            , fd.date_required
            FROM factory_delivery fd
    	WHERE 	(customerno = custno OR custno IS NULL) group by skuid, date_required;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_skuweight_byfactory`(
	IN custno INT
    	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
       
	SELECT 	fd.skuid
            ,sum(fd.weight) as weight
            , fd.date_required
            , fd.depotid
            , fd.factoryid
            FROM factory_delivery fd
    	WHERE 	(customerno = custno OR custno IS NULL) group by factoryid, depotid, date_required, skuid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_transporters`(
	IN custno INT
    , IN tranid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(tranid = '' OR tranid = 0) THEN
		SET tranid = NULL;
	END IF;
	SELECT 	transporterid
			, transportercode
            , transportername
            , transportermail
			, transportermobileno
			, customerno
			, created_on
			, updated_on
   FROM transporter
   WHERE (customerno = custno OR custno IS NULL)
   AND (transporterid = tranid OR tranid IS NULL)
	AND	isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_transportershare`(
	IN custno INT
    , IN currenttransporterid INT
    , IN currentzoneid INT
    , IN transhareid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
	END IF;
    IF(currentzoneid = '' OR currentzoneid = 0) THEN
		SET currentzoneid = NULL;
	END IF;
    IF(transhareid = '' OR transhareid = 0) THEN
		SET transhareid = NULL;
	END IF;
    
	SELECT          t.transporterid
			, t. transportername
			, ts.sharepercent
			, ts.transportershareid
			, z.zoneid
            , z.zonename
			, ts.customerno
   FROM transportershare AS ts
   INNER JOIN transporter AS t ON t.transporterid = ts.transporterid
   INNER JOIN zone AS z ON z.zoneid = ts.zoneid
   WHERE 	(ts.customerno = custno OR custno IS NULL)
   AND		(ts.transporterid = currenttransporterid OR currenttransporterid IS NULL)
   AND		(ts.zoneid = currentzoneid OR currentzoneid IS NULL)
   AND		(ts.transportershareid = transhareid OR transhareid IS NULL)
   AND 		ts.isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_vehicles`(
	IN custno INT
    ,IN currenttransporterid INT
    ,IN vid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
	END IF;
IF(vid = '' OR vid = 0) THEN
		SET vid = NULL;
	END IF;
	SELECT 	t.transporterid
			, transportername
			, vehicleid
			, v.vehicletypeid
            , vehiclecode
            , vehicleno
			, t.customerno
   FROM transporter as t
   INNER JOIN vehicle as v on t.transporterid = v.transporterid
   INNER JOIN vehicletype as vt on v.vehicletypeid = vt.vehicletypeid
   WHERE 	(t.customerno = custno OR custno IS NULL)
   AND		(t.transporterid = currenttransporterid OR currenttransporterid IS NULL)
   AND		(v.vehicleid = vid OR vid IS NULL)
   AND 		t.isdeleted = 0 and v.isdeleted=0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_vehicletypes`(
	IN custno INT
	,IN vehtypeid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(vehtypeid = '' OR vehtypeid = 0) THEN
		SET vehtypeid = NULL;
	END IF;
	SELECT 	vehicletypeid
			, vehiclecode
            , vehicledescription
            , volume
			, weight
			, customerno
			, created_on
			, updated_on
   FROM vehicletype
   WHERE (customerno = custno OR custno IS NULL)
   AND (vehicletypeid = vehtypeid OR vehtypeid IS NULL)
   AND	isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_vehicletype_count`(
	IN custno INT
	,IN transid INT
    	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
IF(transid = '' OR transid = 0) THEN
		SET transid = NULL;
	END IF;
       
	SELECT 	vtm.vehicletypeid
            ,vtm.vehiclecount
            ,vt.volume
            ,vt.weight
            ,vtm.transporterid
            FROM vehtypetransmapping vtm
            INNER JOIN vehicletype vt ON vtm.vehicletypeid = vt.vehicletypeid
    	WHERE 	(vtm.customerno = custno OR custno IS NULL)
    	AND (vtm.transporterid = transid OR transid IS NULL) order by vt.weight DESC;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_zones`(
	IN custno INT
	,IN zid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
	IF(zid = '' OR zid = 0) THEN
		SET zid = NULL;
	END IF;
	SELECT zoneid
			, zonename
			, customerno
			, created_on
			, updated_on
            , created_by
            , updated_by
   FROM zone
   WHERE (customerno = custno OR custno IS NULL) 
   AND 	(zoneid = zid OR zid IS NULL)
   AND  isdeleted = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_depot`( 
	IN depotcode VARCHAR (20)
	, IN depotname VARCHAR (50)
    , IN zoneid INT
    , IN locationid INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentdepotid INT
	)
BEGIN
	INSERT INTO depot(
							depotcode
                            , depotname
                            , zoneid
							, locationid
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				depotcode
                , depotname
                , zoneid
                , locationid
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentdepotid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_factory`( 
	IN factoryname VARCHAR (50)
    ,IN locationid INT
	,IN customerno INT
    ,IN todaysdate DATETIME
    ,IN userid INT
    , OUT currentfactoryid INT
	)
BEGIN
	INSERT INTO factory (
							factoryname
							, locationid
                            , customerno
							, created_on
							, updated_on
							, created_by
							, updated_by
						)
	VALUES ( 
				factoryname
                , locationid
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentfactoryid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_factory_delivery`( 
	IN factoryid int
	, IN skuid int
	, IN depotid int
	, IN date_required date
	, IN weight float(6,2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
	, OUT currentfactorydeliveryid INT
)
BEGIN

	INSERT INTO factory_delivery( 
								factoryid
                                , skuid
                                , depotid
                                , date_required
                                , weight
                                , customerno
                                , created_on
                                , updated_on 
                                , created_by
                                , updated_by
							) 
	VALUES ( 
				factoryid 
                , skuid 
                , depotid
                , date_required
                , weight
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentfactorydeliveryid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_factory_production`( 
	IN factoryid int
	, IN skuid int
	, IN weight float(6,2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentfpid INT
)
BEGIN

	INSERT INTO factory_production( 
									factoryid
									, skuid
                                    , weight
                                    , customerno
                                    , created_on
                                    , updated_on
                                    , created_by
                                    , updated_by
                                    ) 
	VALUES	(
					factoryid
					, skuid 
					, weight
					, customerno
					, todaysdate
					, todaysdate
					, userid
					, userid
			);

	SET currentfpid = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_indent`( 
	IN transporterid int
	, IN vehicleid int
	, IN proposedindentid int
	, IN indent_sku_mappingid int
	, IN shipmentno varchar(50)
	, IN totalweight float(6,2)
	, IN totalvolume float(6,2)
	, IN isdelivered tinyint(1)
	, IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentindentid INT
)
BEGIN

	INSERT INTO indent	( 
							transporterid
							, vehicleid
							, proposedindentid
							, indent_sku_mappingid
							, shipmentno
							, totalweight
							, totalvolume
							, isdelivered
							, customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						) 
	VALUES 	( 
				transporterid                
                , vehicleid
                , proposedindentid
                , indent_sku_mappingid
                , shipmentno
				, totalweight
				, totalvolume
				, isdelivered
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentindentid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_indent_sku_mapping`( 
	IN indentid INT
	, IN skuid INT
	, IN no_of_units INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT current_indent_sku_mappingid INT
)
BEGIN

	INSERT INTO indent_sku_mapping( 
									indentid
									, skuid
									, no_of_units
									, customerno
									, created_on
									, updated_on 
									, created_by
									, updated_by
								)
	VALUES ( 
				indentid
				, skuid
                , no_of_units
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET current_indent_sku_mappingid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_location`( 
	IN locationname VARCHAR (50)
	,IN customerno INT
    ,IN todaysdate DATETIME
    ,IN userid INT
    ,OUT currentlocationid INT
	)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	  BEGIN
		-- ERROR
	  ROLLBACK;
	END;
	START TRANSACTION;
	INSERT INTO location (
							locationname
							, customerno
							, created_on
							, updated_on
							, created_by
							, updated_by
						)
	VALUES ( 
				locationname
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
	SET currentlocationid = LAST_INSERT_ID();
    
    COMMIT;	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_pit_mapping`( 
	IN proposedindentid int,
	IN proposed_transporterid int,
    IN proposed_vehicletypeid INT,
	IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentpitmappingid INT
)
BEGIN

	INSERT INTO proposed_indent_transporter_mapping(
								proposedindentid 
								,proposed_transporterid
                                , proposed_vehicletypeid
								,customerno
								, created_on
								, updated_on
								, created_by
								, updated_by
                            ) 
	VALUES ( 
				proposedindentid
                , proposed_transporterid
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);
	SET currentpitmappingid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_proposed_indent`( 
	IN factoryid int 
	, IN total_weight float(6,2)
	, IN total_volume float(6,2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentproposedindentid INT
)
BEGIN

	INSERT INTO proposed_indent( 
					factoryid
                    , total_weight
                    , total_volume
                    , customerno
                    , created_on
                    , updated_on 
                    , created_by
                    , updated_by
                    ) 
	VALUES 	( 
				factoryid
                , total_weight
                , total_volume
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentproposedindentid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_routecheckpoint`( 
	  IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentroutecheckpointid INT
	)
BEGIN
	INSERT INTO routecheckpoint(
							routemasterid
                            , fromlocationid
                            , tolocationid
                            , distance
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				routemasterid
				, fromlocationid
				, tolocationid
				, distance
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentroutecheckpointid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_routemaster`( 
	IN routename VARCHAR(20)
	, IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
    , IN travellingtime INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentroutemasterid INT
	)
BEGIN
	INSERT INTO routemaster(
							routename
                            , fromlocationid
                            , tolocationid
                            , distance
                            , travellingtime
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				routename
				, fromlocationid
				, tolocationid
				, distance
                , travellingtime
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentroutemasterid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_sku`( 
	IN skucode varchar(100)
	, IN sku_description varchar(250)
	, IN volume float (6,2)
	, IN weight float (6,2)
	, IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentskuid INT
)
BEGIN
	INSERT INTO sku 
				(
					skucode
					, sku_description
					, volume
					, weight
					, customerno
					, created_on
					, updated_on
                    , created_by
                    , updated_by
				) 
	VALUES 		(
					skucode
					, sku_description
					, volume
					, weight
					, customerno
					, todaysdate
					, todaysdate
                    , userid
                    , userid
				);

        
	SET currentskuid = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_transporter`( 
	IN transportercode VARCHAR (20)
	, IN transportername VARCHAR (50)
    , IN transportermail VARCHAR (50)
    , IN transportermobileno VARCHAR (15)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currenttransporterid INT
	)
BEGIN
	INSERT INTO transporter(
							transportercode
                            , transportername
                            , transportermail
							, transportermobileno
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				transportercode
                , transportername
                , transportermail
				, transportermobileno
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currenttransporterid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_transportershare`( 
	IN transporterid INT
	, IN zoneid INT
    , IN sharepercent decimal(6, 2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currenttransportershareid INT
	)
BEGIN
	INSERT INTO transportershare(
							transporterid
                            , zoneid
                            , sharepercent
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				transporterid
                , zoneid
                , sharepercent
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currenttransportershareid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_vehicle`( 
	IN transporterid INT
	, IN vehicletypeid INT
    , IN vehicleno VARCHAR(20)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentvehicleid INT
	)
BEGIN
	INSERT INTO vehicle(
							transporterid
                            , vehicletypeid
                            , vehicleno
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				transporterid
                , vehicletypeid
                , vehicleno
				, customerno
				, todaysdate
				, todaysdate
				, userid
                , userid
			);
            
	SET currentvehicleid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_vehicletype`( 
	IN vehiclecode VARCHAR (20)
	, IN vehicledescription VARCHAR (50)
    , IN volume VARCHAR (50)
    , IN weight VARCHAR (15)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentvehicletypeid INT
	)
BEGIN
	INSERT INTO vehicletype(
							vehiclecode
                            , vehicledescription
                            , volume
							, weight
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				vehiclecode
                , vehicledescription
                , volume
				, weight
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentvehicletypeid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_zone`( 
	IN zonename VARCHAR (10)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT 
    , OUT currentzoneid INT
	)
BEGIN

	INSERT INTO zone(
						zonename
						, customerno
						, created_on
						, updated_on
                        , created_by
                        , updated_by
					)
	VALUES ( 
				zonename
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentzoneid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_depot`( 
	IN depotcode VARCHAR(20)
	, IN depotname VARCHAR (50)
	, IN zoneid INT
    , IN locationid INT
	, IN did INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE depot
    SET  depotcode = depotcode
		, depotname = depotname
        , zoneid = zoneid
		, locationid = locationid
        , updated_on = todaysdate
        , updated_by = userid
	WHERE depotid = did;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_factory`( 
	  IN factoryname VARCHAR (50)
    , IN locationid INT
	, IN fid INT
	, IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE factory 
    SET  factoryname = factoryname
		, locationid = locationid
        , updated_on = todaysdate
        , updated_by = userid
	WHERE factoryid = fid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_factory_delivery`( 
	IN fdidparam int
	, IN factoryid int
	, IN skuid int
	, IN depotid int
	, IN date_required date
	, IN weight float(6,2)
	, IN customerno INT
	, IN todaysdate DATETIME
	, IN userid INT
)
BEGIN
	UPDATE 	factory_delivery
	SET 	factoryid=factoryid
			,skuid = skuid
			,depotid = depotid
			,date_required = date_required
			,weight = weight
			,updated_on = todaysdate 
			, updated_by = userid
	WHERE 	fdid = fdidparam;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_factory_production`( 
	IN fpidparam int
	,IN factoryid INT
	,IN skuid int
	,IN weight float(6,2)
	,IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	factory_production
	SET	 	factoryid=factoryid,
			skuid = skuid,
			weight = weight,
			updated_on = todaysdate 
			, updated_by = userid
	WHERE 	fpid = fpidparam;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_indent`( 
	IN indentidparam int,
	IN transporterid int,
	IN vehicleid int,
	IN proposedindentid int,
	IN indent_sku_mappingid int,
	IN shipmentno varchar(50),
	IN totalweight float(6,2),
	IN totalvolume float(6,2),
	IN isdelivered tinyint(1)
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	indent
	SET 	transporterid = transporterid
			,vehicleid = vehicleid
			,proposedindentid = proposedindentid
			,indent_sku_mappingid = indent_sku_mappingid
			,shipmentno = shipmentno
			,totalweight = totalweight
			,totalvolume = totalvolume
			,isdelivered = isdelivered
			,updated_on = todaysdate
			,updated_by = userid
	WHERE 	indentid = indentidparam;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_indent_sku_mapping`( 
	IN ismid INT,
	IN indentid int,
	IN skuid int,
	IN no_of_units int,
	IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE indent_sku_mapping
	SET 	indentid = indentid,
			skuid = skuid,
			no_of_units = no_of_units,
			updated_on = todaysdate, 
			updated_by = userid
	WHERE 	indent_sku_mappingid = ismid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_location`( 
	IN locationname VARCHAR (50)
	, IN locid INT
	, IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE location 
    SET  locationname = locationname
		, updated_on = todaysdate
        , updated_by = userid
	WHERE locationid = locid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_pit_mapping`( 
	IN pitmapid INT
	, IN proposedindentid INT
	, IN proposed_transporterid INT
    , IN proposed_vehicletypeid INT
    , IN actual_vehicletypeid INT
    , IN vehicleid INT
    , IN isAccepted tinyint(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	proposed_indent_transporter_mapping
	SET 	proposedindentid = proposedindentid
			, proposed_transporterid = proposed_transporterid
            , proposed_vehicletypeid = proposed_vehicletypeid
            , actual_vehicletypeid = actual_vehicletypeid
            , vehicleid = vehicleid
            , isAccepted = isAccepted
			, updated_on = todaysdate 
            , updated_by = userid
	WHERE 	pitmappingid = pitmapid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_proposed_indent`( 
	IN propindentid int,
	IN factoryid int, 
	IN total_weight float(6,2),
	IN total_volume float(6,2),
	IN isApproved TINYINT(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE proposed_indent
	SET 
		factoryid = factoryid
		,total_weight = total_weight
		,total_volume = total_volume
        ,isApproved = isApproved
		, updated_on = todaysdate 
        ,updated_by = userid
	WHERE 
		proposedindentid = propindentid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_routecheckpoint`(
	IN routechkptid INT
    , IN routemasterid INT
	, IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE routecheckpoint
    SET  routemasterid = routemasterid
		, fromlocationid = fromlocationid
        , tolocationid = tolocationid
        , distance = distance
        , updated_on = todaysdate
        , updated_by = userid
	WHERE routecheckpointid = routechkptid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_routemaster`(
	IN rtmasterid INT
    , IN routename VARCHAR(20)
	, IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
    , IN travellingtime INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE routemaster
    SET  routename = routename
		, fromlocationid = fromlocationid
        , tolocationid = tolocationid
        , distance = distance
        , travellingtime = travellingtime
		, updated_on = todaysdate
        , updated_by = userid
	WHERE routemasterid = rtmasterid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_sku`( 
	IN skuidparam INT
	, IN skucode varchar(100)
	, IN sku_description varchar(250)
	, IN volume float (6,2)
	, IN weight float (6,2)
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE sku
	SET 
		skucode = skucode
		, sku_description = sku_description
		, volume = volume
		, weight = weight
		, updated_on = todaysdate
        , updated_by = userid
	WHERE skuid = skuidparam;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_transporter`( 
	IN transportercode VARCHAR(20)
	, IN transportername VARCHAR (50)
    , IN transportermail VARCHAR (50)
    , IN transportermobileno VARCHAR (15)
	, IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE transporter
    SET  transportercode = transportercode
		, transportername = transportername
        , transportermail = transportermail
		, transportermobileno = transportermobileno
        , updated_on = todaysdate
		, updated_by = userid
	WHERE transporterid = tranid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_transportershare`(
	IN transhareid INT
    , IN transporterid INT
	, IN zoneid INT
    , IN sharepercent decimal(6, 2)
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE transportershare
    SET  transporterid = transporterid
		, zoneid = zoneid
        , sharepercent = sharepercent
		, updated_on = todaysdate
        , updated_by = userid
	WHERE transportershareid = transhareid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_vehicle`(
	IN vehid INT
    , IN vehicleno VARCHAR(20)
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE vehicle 
    SET  vehicletypeid = vehicletypeid
		, vehicleno = vehicleno
		, updated_on = todaysdate
        , updated_by = userid
	WHERE vehicleid = vehid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_vehicletype`( 
	IN vehiclecode VARCHAR(20)
	, IN vehicledescription VARCHAR (50)
    , IN volume decimal
    , IN weight decimal
	, IN vehtypeid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE vehicletype
    SET  vehiclecode = vehiclecode
		, vehicledescription = vehicledescription
        , volume = volume
		, weight = weight
        , updated_on = todaysdate
        , updated_by = userid
	WHERE vehicletypeid = vehtypeid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_zone`( 
	IN zid INT
        ,IN zonename VARCHAR (10)
	
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	/* DECLARE noOfRowsAffected INT; 
    START TRANSACTION;*/
	UPDATE zone 
	SET  zonename = zonename
		, updated_on = todaysdate
		, updated_by = userid
	WHERE zoneid = zid;
        /*
	SET noOfRowsAffected = ROW_COUNT();

	IF(noOfRowsAffected = 1) THEN COMMIT;
		ELSE ROLLBACK;
	END IF;    
        */
END$$

DELIMITER ;

-- --------------------------------------------------------



















