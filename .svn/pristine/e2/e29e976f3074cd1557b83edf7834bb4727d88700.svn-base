UPDATE driver set vehicleid = 0
WHERE vehicleid != 0 
AND driverid NOT IN (	SELECT	 	distinct driverid 
						from 		vehicle 
                        where 		driverid != 0 
                        order by 	driverid) ;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (352, NOW(), 'Mrudang Vora','Correct Driver''s vehicle id');
