-- Insert SQL here.

ALTER TABLE `customer` ADD `use_toggle_switch` BOOLEAN NOT NULL ;

CREATE TABLE `toggle_switch` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`customerno` INT( 11 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`starttime` DATETIME NOT NULL ,
`endtime` DATETIME NOT NULL ,
`startlat` FLOAT NOT NULL ,
`startlong` FLOAT NOT NULL ,
`endlat` FLOAT NOT NULL ,
`endlong` FLOAT NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `toggle_switch` ADD `uid` INT( 11 ) NOT NULL AFTER `customerno` ;

create index index_customerno on toggle_switch(customerno);
create index index_vehicleid on toggle_switch(vehicleid);


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_toggleswitch_trips`$$
CREATE PROCEDURE `get_toggleswitch_trips`(
   IN vehicleidparam INT
   ,IN custnoparam INT
   , IN startdateparam DATETIME
   , IN enddateparam DATETIME
   , IN groupidparam INT
)
BEGIN
	IF(vehicleidparam = 0) THEN
		SET vehicleidparam = NULL;
	END IF;
    
    IF(groupidparam = 0) THEN
		SET groupidparam = NULL;
	END IF;
    
    IF(startdateparam = '0000-00-00 00:00:00' OR enddateparam = '0000-00-00 00:00:00') THEN
		SET startdateparam = NULL;
        SET enddateparam = NULL;
	END IF;
    SELECT 		ts.id
				, ts.uid
                , ts.vehicleid
                , ts.starttime
                , ts.endtime
                , ts.startlat
                , ts.startlong
                , ts.endlat
                , ts.endlong
                , veh.vehicleno
                , dev.deviceid
	FROM 		toggle_switch ts
    INNER JOIN	vehicle veh ON veh.vehicleid = ts.vehicleid
    INNER JOIN	devices dev ON dev.uid = ts.uid
    WHERE 		ts.customerno = custnoparam
    AND			(ts.vehicleid = vehicleidparam OR vehicleidparam IS NULL)
    AND 		(veh.groupid = groupidparam OR groupidparam IS NULL)
    AND 		((ts.starttime BETWEEN startdateparam AND enddateparam) OR (startdateparam IS NULL AND enddateparam IS NULL))
	ORDER BY	ts.starttime DESC;

END $$
DELIMITER ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 375, NOW(), 'Sanket Sheth','Toggle Switch');
