DELIMITER $$
DROP PROCEDURE IF EXISTS assign_proposed_indent_to_next_transporter$$
CREATE  PROCEDURE assign_proposed_indent_to_next_transporter(
	IN propIndentIdParam INT
    , IN propTransIdParam INT
    , IN propVehTypeIdParam INT
    , IN factoryIdParam INT
    , IN depotIdParam INT
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
    WHERE 		depotid = depotIdParam
	AND			d.customerno = custno
	AND			d.isdeleted = 0;
    
    IF(zid IS NOT NULL) THEN
		BEGIN
			SELECT 	sharepercent
			INTO 	temp_sharepercent
			FROM 	transportershare
			WHERE 	transporterid = propTransIdParam
			AND		factoryid = factoryIdParam
			AND 	zoneid = zid
            AND		customerno = custno
			AND		isdeleted = 0;
            
			IF(temp_sharepercent IS NOT NULL) THEN
				BEGIN
					 /* 
						Assign the rejected indent to highest share transporter 
                        provided he has not rejected it earlier.
					*/
					SELECT 		t.transporterid
					INTO 		next_proposed_transporter_id
					FROM 		transportershare AS ts
					INNER JOIN 	transporter AS t ON t.transporterid = ts.transporterid
					INNER JOIN	vehtypetransmapping vtm ON vtm.transporterid = t.transporterid
					INNER JOIN 	vehicletype AS vt ON vt.vehicletypeid = vtm.vehicletypeid
					WHERE		ts.zoneid = zid
					AND			ts.factoryid = factoryIdParam
					AND			vtm.vehicletypeid = propVehTypeIdParam
					AND 		t.transporterid != propTransIdParam
					AND			ts.customerno = custno
					AND 		ts.isdeleted = 0
					AND 		t.isdeleted = 0
					AND 		vt.isdeleted = 0
					AND 		vtm.isdeleted = 0
					AND			t.transporterid NOT IN	(	
										SELECT 	proposed_transporterid 
										FROM 	proposed_indent_transporter_mapping
										WHERE 	proposedindentid = propIndentIdParam
										AND		isAccepted = -1
										AND 	proposed_transporterid IS NOT NULL
									)
					ORDER BY 	ts.sharepercent DESC
					LIMIT 1;
                    /*
						If highest share transporter has rejected the indent, assign the proposed indent to 
                        transporter with share less than the current transporter and has the proposed vehicle type.
                    */
                    IF (next_proposed_transporter_id IS NULL) THEN
						SELECT 		t.transporterid
						INTO 		next_proposed_transporter_id
						FROM 		transportershare AS ts
						INNER JOIN 	transporter AS t ON t.transporterid = ts.transporterid
						INNER JOIN	vehtypetransmapping vtm ON vtm.transporterid = t.transporterid
						INNER JOIN 	vehicletype AS vt ON vt.vehicletypeid = vtm.vehicletypeid
						WHERE 		ts.sharepercent <= temp_sharepercent
						AND			ts.zoneid = zid
						AND			ts.factoryid = factoryIdParam
						AND			vtm.vehicletypeid = propVehTypeIdParam
						AND 		t.transporterid != propTransIdParam
						AND			ts.customerno = custno
						AND 		ts.isdeleted = 0
						AND 		t.isdeleted = 0
						AND 		vt.isdeleted = 0
						AND 		vtm.isdeleted = 0
						AND			t.transporterid NOT IN	(	
													SELECT 	proposed_transporterid 
													FROM 	proposed_indent_transporter_mapping
													WHERE 	proposedindentid = propIndentIdParam
													AND		isAccepted = -1
													AND 	proposed_transporterid IS NOT NULL
												)
						ORDER BY 	ts.sharepercent DESC 
						LIMIT 1;
                    END IF;
                    
					IF (next_proposed_transporter_id IS NOT NULL) THEN
						BEGIN
							START TRANSACTION;
							CALL `insert_pit_mapping`(propIndentIdParam
													, next_proposed_transporter_id
                                                    , propVehTypeIdParam
                                                    , custno
                                                    , todaysdate
                                                    , userid
                                                    , @currentpitmappingid);
							SELECT 	@currentpitmappingid 
                            INTO	currentpitmappingid
                            FROM 	DUAL;
                            
                            IF(currentpitmappingid IS NOT NULL) THEN
								CALL `update_proposed_indent`(propIndentIdParam, NULL, NULL, NULL, NULL, NULL, 0, todaysdate, userid);
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









-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (21, NOW(), 'Mrudang Vora','Change in rejection logic ');

