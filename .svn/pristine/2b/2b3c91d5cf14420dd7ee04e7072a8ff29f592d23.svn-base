alter table unit add humidity int(3) NOT NULL DEFAULT 0 after n4;

alter table customer add use_humidity tinyint(1) NOT NULL DEFAULT 0 after use_pickup;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (299, NOW(), 'Shrikanth Suryawanshi','Humidity Sensor');
