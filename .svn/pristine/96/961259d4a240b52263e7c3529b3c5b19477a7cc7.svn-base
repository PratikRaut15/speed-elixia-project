DELIMITER $$
CREATE  PROCEDURE `update_vehicle_route_direction`(IN `checkPointIdParam` INT, IN `vehicleIdParam` INT, IN `inOutStatusParam` INT, IN `customerNoParam` INT)
BEGIN
	SET @routeIdOfVehicle = (SELECT routeid FROM vehiclerouteman WHERE vehicleid=vehicleIdParam and isdeleted=0 limit 1);
    SET @oldCheckPointId = (SELECT checkpointId FROM vehicle WHERE vehicleid=vehicleIdParam and isdeleted=0 limit 1);
    SET @sequenceOfOldCheckPoint = (SELECT sequence FROM routeman WHERE routeid=@routeIdOfVehicle AND checkpointid=@oldCheckPointId and isdeleted=0 limit 1);
    SET @sequenceOfNewCheckPoint = (SELECT sequence FROM routeman WHERE routeid=@routeIdOfVehicle AND checkpointid=checkPointIdParam and isdeleted=0 limit 1);
    SET @minRouteSequence = (SELECT min(sequence) FROM routeman WHERE routeid=@routeIdOfVehicle);
    SET @maxRouteSequence = (SELECT max(sequence) FROM routeman WHERE routeid=@routeIdOfVehicle);
    
    IF (inOutStatusParam=0) THEN
      IF (@sequenceOfNewCheckPoint=@minRouteSequence) THEN
		UPDATE vehicle SET routeDirection='1' WHERE vehicleid=vehicleIdParam;
      ELSEIF (@sequenceOfNewCheckPoint=@maxRouteSequence) THEN
        UPDATE vehicle SET routeDirection='2' WHERE vehicleid=vehicleIdParam;
      ELSEIF (@sequenceOfOldCheckPoint > @sequenceOfNewCheckPoint) THEN  
        UPDATE vehicle SET routeDirection='2' WHERE vehicleid=vehicleIdParam;
      ELSE 
        UPDATE vehicle SET routeDirection='1' WHERE vehicleid=vehicleIdParam;
      END IF; 
    ELSEIF (inOutStatusParam=1) THEN  
      IF (@sequenceOfNewCheckPoint=@minRouteSequence) THEN
		UPDATE vehicle SET routeDirection='1' WHERE vehicleid=vehicleIdParam;
      ELSEIF (@sequenceOfNewCheckPoint=@maxRouteSequence) THEN
        UPDATE vehicle SET routeDirection='2' WHERE vehicleid=vehicleIdParam;
      ELSEIF (@sequenceOfOldCheckPoint > @sequenceOfNewCheckPoint) THEN  
        UPDATE vehicle SET routeDirection='2' WHERE vehicleid=vehicleIdParam;
      ELSE 
        UPDATE vehicle SET routeDirection='1' WHERE vehicleid=vehicleIdParam;
      END IF; 
    END IF;
END$$
DELIMITER ;
