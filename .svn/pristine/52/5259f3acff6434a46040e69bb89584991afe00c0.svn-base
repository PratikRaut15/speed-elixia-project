INSERT INTO `speed`.`dbpatches` (
`patchid`
, `patchdate`
, `appliedby`
, `patchdesc`
, `isapplied`)
VALUES ('395', '2016-07-07 15:59:43', 'Sanket Sheth', 'CreateIndex', '0');

ALTER TABLE `comhistory` ADD INDEX ( `comqid` ) ;
ALTER TABLE `cellTowerDetail` ADD INDEX ( `cellId` );
ALTER TABLE `cellTowerDetail` ADD INDEX ( `locationAreaCode` ) ;

create index index_groupid on vehicle(groupid);
create index index_vehicleid on description(vehicleid);
create index index_userid on maintenance(userid);
create index index_dealer on maintenance(dealer_id);
create index index_driverid on vehicle(driverid);
create index index_simcardid on devices(simcardid);

create index index_userid on groupman(userid);
create index index_userid on vehicle(userid);
create index index_modelid on vehicle(modelid);
create index index_makeid on model(make_id);
create index index_vehicleid on insurance(vehicleid);
create index index_vehicleid on tax(vehicleid);
create index index_vehicleid on loan(vehicleid);
create index index_vehicleid on capitalization(vehicleid);
create index index_dealer on description(dealerid);

create index index_customerno on `user`(customerno);

ALTER TABLE `ignitionalert` DROP INDEX `vehicleid_2` ;
ALTER TABLE `unit` DROP INDEX `vehicleid_2` ;





UPDATE 	dbpatches
SET 	patchdate = NOW()
		, isapplied =1
WHERE 	patchid = 395;
