
DROP TABLE IF EXISTS actual_monthly_share;
create table actual_monthly_share(
`actshareid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`factoryid` int(11)NOT NULL,
`depotid` int(11) NOT NULL,
`transporterid` int(11) NOT NULL,
`shared_weight` decimal(11,3) NOT NULL,
`total_weight` decimal(11,3) NOT NULL,
`actualpercent` decimal(6,2)NOT NULL,
`customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);

ALTER TABLE `proposed_indent` add date_required date AFTER total_volume;

ALTER TABLE `proposed_indent` CHANGE `total_weight` `total_weight` DECIMAL(7,3) NULL DEFAULT NULL, CHANGE `total_volume` `total_volume` DECIMAL(7,3) NULL DEFAULT NULL;

ALTER TABLE `factory_production` CHANGE `weight` `weight` DECIMAL(7,3) NULL DEFAULT NULL;

ALTER TABLE `factory_delivery` CHANGE `weight` `weight` DECIMAL(7,3) NULL DEFAULT NULL;


ALTER TABLE `actual_monthly_share` CHANGE `depotid` `zoneid` INT(11) NOT NULL;
ALTER TABLE `actual_monthly_share` CHANGE `actualpercent` `actualpercent` DECIMAL(5,2) NOT NULL;
ALTER TABLE `proposed_indent_sku_mapping` CHANGE `indent_sku_mappingid` `proposed_indent_sku_mappingid` INT(11) NOT NULL;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicletypes`$$
CREATE PROCEDURE `get_vehicletypes`(
	IN custno INT
	,IN vehtypeid INT
	,IN sku_typeid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(vehtypeid = '' OR vehtypeid = 0) THEN
		SET vehtypeid = NULL;
	END IF;
IF(sku_typeid = '' OR sku_typeid = 0) THEN
		SET sku_typeid = NULL;
	END IF;
	SELECT 	vehicletypeid
			, vehiclecode
            , vehicledescription
	    , v.skutypeid
	    , st.type
            , volume
			, weight
			, v.customerno
			, v.created_on
			, v.updated_on
   FROM vehicletype as v
   INNER JOIN skutypes as st ON st.tid = v.skutypeid
   WHERE (v.customerno = custno OR custno IS NULL)
   AND (v.vehicletypeid = vehtypeid OR vehtypeid IS NULL)
   AND (v.skutypeid = sku_typeid OR sku_typeid IS NULL)
   AND	v.isdeleted = 0 order by ispreferred DESC, volume DESC;	
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transportershare`$$
CREATE PROCEDURE `insert_transportershare`( 
	IN transporterid INT
	, IN factoryid INT
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
                            , factoryid
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
                , factoryid
		, zoneid
                , sharepercent
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	
	SET currenttransportershareid = LAST_INSERT_ID();
	call insert_transporteractualshare(transporterid,factoryid, zoneid,sharepercent,customerno, todaysdate, userid);
	

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transporteractualshare`$$
CREATE PROCEDURE `insert_transporteractualshare`( 
	IN transporterid INT
	, IN factoryid INT
	, IN zoneid INT
	,IN sharepercent decimal(6, 2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    
	)
BEGIN
	INSERT INTO `actual_monthly_share`(
						`factoryid`
						, `zoneid`
						, `transporterid`
						, `actualpercent`
						, `customerno`
						, `created_on`
						, `updated_on`
						, `created_by`
						, `updated_by`) 
				VALUES (factoryid
					,zoneid
					,transporterid
					,sharepercent
					,customerno
					,todaysdate
					,todaysdate
					,userid
					,userid
					);
            
	

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_transporteractualshare`$$
CREATE PROCEDURE `update_transporteractualshare`( 
	IN transid INT
	, IN factid INT
	, IN zid INT
	, IN sharedwt decimal(11,3)
	, IN totalwt decimal(11,3)
	, IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    
	)
BEGIN
    DECLARE actualsharepercent DECIMAL(5,2);
    DECLARE tempsharedwt decimal(11,3);
    DECLARE temptotalwt decimal(11,3);
    
    SELECT shared_weight, total_weight
	INTO 	tempsharedwt, temptotalwt
    FROM 	actual_monthly_share
    WHERE 	transporterid = transid
    AND		factoryid = factid
    AND 	zoneid = zid
    AND 	customerno = custno;
	
	SET 	tempsharedwt = tempsharedwt + sharedwt;
    SET		temptotalwt = temptotalwt + totalwt;
    
    SET		actualsharepercent = (tempsharedwt/temptotalwt) * 100;
    
	UPDATE `actual_monthly_share`
	SET 	`shared_weight` = shared_weight
			,`total_weight` = total_weight
            , `actualpercent` = actualsharepercent
			, `updated_on` = todaysdate
			, `updated_by`= userid
	WHERE 	transporterid = transid
    AND		factoryid = factid
    AND 	zoneid = zid
    AND 	customerno = custno;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_proposed_indent`$$
CREATE PROCEDURE `insert_proposed_indent`( 
	IN factoryid int 
	, IN total_weight float(7,3)
	, IN total_volume float(7,3)
	, IN daterequired date
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
		    , date_required
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
		, daterequired
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentproposedindentid = LAST_INSERT_ID();

END$$
DELIMITER ;
	

	
