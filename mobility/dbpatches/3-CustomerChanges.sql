ALTER TABLE  `customer` ADD  `istrack` BOOLEAN NOT NULL AFTER  `customerno` ,
ADD  `isservice` BOOLEAN NOT NULL AFTER  `istrack`;






 INSERT INTO `dbpatches` (  `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (  NOW(), 'vishu','Some Fancy Patch');