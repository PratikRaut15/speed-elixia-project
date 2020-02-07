ALTER TABLE depot ADD multidrop tinyint(1) DEFAULT 0 AFTER zoneid; 

CREATE TABLE multidepot_mapping(
md_mappingid int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
depotid int(11) NOT NULL,
depotmappingid int(11) NOT NULL,
customerno int(11) NOT NULL,
created_by int(11) NOT NULL,
updated_by int(11) NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
isdeleted tinyint(1) NOT NULL
);

ALTER TABLE multidepot_mapping add factoryid INT(11) NOT NULL AFTER depotid;
ALTER TABLE `multidepot_mapping` CHANGE `depotmappingid` `depotmappingid` VARCHAR(50) NOT NULL;

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_depot$$ 
CREATE PROCEDURE insert_depot( 
	IN depotcode VARCHAR (20)
	, IN depotname VARCHAR (50)
    , IN zoneid INT
    , IN multidrop INT
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
                            , multidrop
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
                , multidrop
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentdepotid = LAST_INSERT_ID();

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_multidepot_mapping$$ 
CREATE PROCEDURE insert_multidepot_mapping( 
	IN depotidparam INT
    , IN factoryidparam INT
	, IN multidepotidparam VARCHAR(50)
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
	INSERT INTO multidepot_mapping(
							depotid
							, factoryid
                            , depotmappingid
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				depotidparam
				, factoryidparam
                , multidepotidparam
                , customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_mapped_depots$$ 
CREATE PROCEDURE get_mapped_depots(
	IN custno INT
    , IN did INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
   
	IF(did = '' OR did = 0) THEN
		SET did = NULL;
	END IF;
	SELECT 	md_mappingid
			, md.depotid
			, md.depotmappingid	
            , d.depotname
			, md.customerno
			, md.created_on
			, md.updated_on
			
			
   FROM multidepot_mapping as md
   INNER JOIN depot as d on d.depotid = md.depotmappingid
   WHERE (md.customerno = custno OR custno IS NULL)
   AND 	(md.depotid = did OR did IS NULL)
   AND 	md.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS delete_mapped_depot$$ 
CREATE PROCEDURE delete_mapped_depot(
	IN did INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE multidepot_mapping 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE depotid = did;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_mapped_depots$$ 
CREATE PROCEDURE get_mapped_depots(
	IN custno INT
    , IN did INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
   
	IF(did = '' OR did = 0) THEN
		SET did = NULL;
	END IF;
	SELECT 	md_mappingid
			, md.depotid
			, md.depotmappingid	
            , d.depotname
            , md.factoryid
			, f.factoryname
			, md.customerno
			, md.created_on
			, md.updated_on
			
			
   FROM multidepot_mapping as md
   INNER JOIN depot as d on d.depotid = md.depotid
   INNER JOIN factory as f on f.factoryid = md.factoryid
   WHERE (md.customerno = custno OR custno IS NULL)
   AND 	(md.depotid = did OR did IS NULL)
   AND 	md.isdeleted = 0;
END$$
DELIMITER ;

/*-----------------------------------------------------------------------------------------------------------------------------------------*/


DELIMITER $$
DROP PROCEDURE IF EXISTS assign_proposed_indent_to_next_transporter$$
CREATE  PROCEDURE assign_proposed_indent_to_next_transporter( 
	IN proposedindentidparam INT
    , IN proposed_transporterid INT
    , IN proposed_vehicletypeid INT
    , IN factid INT
    , IN depotidparam INT
    , IN custno INT
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
    DECLARE zid INT;
    DECLARE temp_sharepercent DECIMAL(5,2);
    DECLARE next_proposed_transporter_id INT;
    DECLARE currentpitmappingid INT;
    
    SELECT 		d.zoneid
    INTO 		zid
    FROM 		depot AS d
    INNER JOIN 	zone AS z ON d.zoneid = z.zoneid
    WHERE 		depotid = depotidparam
	AND			d.customerno = custno
	AND			d.isdeleted = 0;
    
    IF(zid IS NOT NULL && zid != 0) THEN
		BEGIN
			SELECT 	sharepercent
			INTO 	temp_sharepercent
			FROM 	transportershare
			WHERE 	transporterid = proposed_transporterid
			AND		factoryid = factid
			AND 	zoneid = zid
            AND		customerno = custno
			AND		isdeleted = 0;
			
			IF(temp_sharepercent IS NOT NULL && temp_sharepercent != 0) THEN
				BEGIN
					SELECT 		t.transporterid
					INTO 		next_proposed_transporter_id
					FROM 		transportershare AS ts
					INNER JOIN 	transporter AS t ON t.transporterid = ts.transporterid
					INNER JOIN	vehtypetransmapping vtm ON vtm.transporterid = t.transporterid
					INNER JOIN 	vehicletype AS vt ON vt.vehicletypeid = vtm.vehicletypeid
					WHERE 		ts.sharepercent <= temp_sharepercent
					AND			ts.zoneid = zid
					AND			ts.factoryid = factid
					AND			vtm.vehicletypeid = proposed_vehicletypeid
					AND 			t.transporterid !=  proposed_transporterid
					AND			ts.customerno = custno
					AND 		ts.isdeleted = 0
                    AND 		t.isdeleted = 0
                    AND 		vt.isdeleted = 0
                    AND 		vtm.isdeleted = 0
					ORDER BY 	sharepercent DESC 
                    LIMIT 1;
                    /* 
						If there are no transporters with share less than the current transporter,
						assign the proposed indent to highest share transporter provided he has not rejected it earlier.
					*/
					IF (next_proposed_transporter_id IS NULL || next_proposed_transporter_id = 0) THEN
							SELECT 		t.transporterid
							INTO 		next_proposed_transporter_id
							FROM 		transportershare AS ts
							INNER JOIN 	transporter AS t ON t.transporterid = ts.transporterid
							INNER JOIN	vehtypetransmapping vtm ON vtm.transporterid = t.transporterid
							INNER JOIN 	vehicletype AS vt ON vt.vehicletypeid = vtm.vehicletypeid
                            WHERE		ts.zoneid = zid
							AND			ts.factoryid = factid
							AND			vtm.vehicletypeid = proposed_vehicletypeid
							AND			ts.customerno = custno
							AND 		ts.isdeleted = 0
							AND 		t.isdeleted = 0
							AND 		vt.isdeleted = 0
                            AND 		vtm.isdeleted = 0
                            AND			t.transporterid NOT IN	(	
																	SELECT 	proposed_transporterid 
																	FROM 	proposed_indent_transporter_mapping
                                                                    WHERE 	proposedindentid = proposedindentidparam
																	AND		isAccepted = -1
																)
                            ORDER BY 	ts.sharepercent DESC
                            LIMIT 1;
					END IF;
					IF (next_proposed_transporter_id IS NOT NULL && next_proposed_transporter_id != 0) THEN
						BEGIN
							START TRANSACTION;
							CALL insert_pit_mapping(proposedindentidparam
													, next_proposed_transporter_id
                                                    , proposed_vehicletypeid
                                                    , custno
                                                    , todaysdate
                                                    , userid
                                                    , @currentpitmappingid);
							SELECT 	@currentpitmappingid 
                            INTO	currentpitmappingid
                            FROM 	DUAL;
                            
                            IF(currentpitmappingid IS NOT NULL && currentpitmappingid != 0) THEN
								CALL update_proposed_indent(proposedindentidparam, NULL, NULL, NULL, NULL, NULL, 0, todaysdate, userid);
								COMMIT;
							ELSE
                                ROLLBACK;
                            END IF;
						END;
                    END IF;
				END;
			END IF;
		END;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS update_proposed_indent$$ 
CREATE PROCEDURE update_proposed_indent( 
	IN propindentid int
	, IN factoryid int
    , IN depotid int
	, IN total_weight decimal(7,3)
	, IN total_volume decimal(7,3)
	, IN isApprovedParam TINYINT(1)
	, IN hasTransAccepted TINYINT(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF	((propindentid IS NOT NULL && propindentid != '' && propindentid != '0')
		AND (factoryid IS NOT NULL && factoryid !='' && factoryid !='0')
		AND (depotid IS NOT NULL && depotid !='' && depotid !='0')
        AND (total_weight IS NOT NULL && total_weight !='')
        AND (total_volume IS NOT NULL && total_volume != '')) THEN
			UPDATE 	proposed_indent
			SET 	factoryid = factoryid
					, depotid = depotid
					, total_weight = total_weight
					, total_volume = total_volume
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE	proposedindentid = propindentid;
    ELSEIF (hasTransAccepted IS NOT NULL) THEN 
			UPDATE 	proposed_indent
			SET 	hasTransporterAccepted = hasTransAccepted
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE proposedindentid = propindentid;	
	ELSEIF (isApprovedParam IS NOT NULL) THEN
			UPDATE 	proposed_indent
			SET 	 isApproved = isApprovedParam
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE	proposedindentid = propindentid;
	END IF ;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_proposed_indent$$
CREATE PROCEDURE get_proposed_indent(
	IN custno INT
    , propindentid INT
    , factoryidparam INT
    , daterequired varchar(15)
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
        IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
	END IF;
        IF(daterequired = '' OR daterequired = 0) THEN
		SET daterequired = NULL;
	END IF;
	SELECT 	        pi.proposedindentid
			,pi.factoryid
			, pi.hasTransporterAccepted
			, pi.isApproved
			, f.factoryname
			, pi.depotid
			, d.depotname
			, t.transporterid
			,t.transportername
	       	        , vehtype.vehiclecode
		        , pit.vehicleno
		        , pi.date_required
			, pit.proposed_vehicletypeid
			, pit.actual_vehicletypeid
            , pit.isAccepted
			, total_weight
			, total_volume
			, pi.customerno
			, pi.created_on
			, pi.updated_on 
			, pi.created_by
			, pi.updated_by
	FROM 	proposed_indent pi
        INNER JOIN proposed_indent_transporter_mapping pit ON pit.proposedindentid = pi.proposedindentid
        INNER JOIN factory f ON f.factoryid = pi.factoryid
        INNER JOIN depot d ON d.depotid = pi.depotid
        INNER JOIN transporter t ON t.transporterid = pit.proposed_transporterid
        INNER JOIN vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
    
	WHERE 	(pi.customerno = custno OR custno IS NULL)
        AND	(pi.proposedindentid = propindentid OR propindentid IS NULL)
        AND	(pi.factoryid = factoryidparam OR factoryidparam IS NULL)
	AND	(pi.date_required = daterequired OR daterequired IS NULL)
	AND 	pi.isdeleted = 0;
END$$
DELIMITER ;

