-- Insert SQL here.

delete from dailyreport where vehicleid = 1748;
delete from dailyreport where vehicleid = 1751;
delete from dailyreport where vehicleid = 1755;
delete from dailyreport where vehicleid = 2225;

update dailyreport SET uid='1646' where vehicleid = 2227;
update dailyreport SET uid='1650' where vehicleid = 2228;
update dailyreport SET uid='1883' where vehicleid = 2223;
update dailyreport SET uid='1941' where vehicleid = 2233;
update dailyreport SET uid='1979' where vehicleid = 2238;

delete from dailyreport where vehicleid = 2330;
delete from dailyreport where vehicleid = 2339;

update dailyreport SET uid='1986' where vehicleid = 2332;

delete from dailyreport where vehicleid = 3086;
delete from dailyreport where vehicleid = 3087;
delete from dailyreport where vehicleid = 3088;
delete from dailyreport where vehicleid = 3089;
delete from dailyreport where vehicleid = 3091;

update unit SET vehicleid='3106' where uid = 2684;
update unit SET vehicleid='3107' where uid = 2685;

delete from dailyreport where vehicleid = 3112;
delete from dailyreport where vehicleid = 3143;
delete from dailyreport where vehicleid = 3153;
delete from dailyreport where vehicleid = 3155;
delete from dailyreport where vehicleid = 3156;
delete from dailyreport where vehicleid = 3179;
delete from dailyreport where vehicleid = 3183;
delete from dailyreport where vehicleid = 3185;



/*
-- To Cross Check the unit numbers.

select vehicle.vehicleid, vehicle.uid as vuid, unit.uid as uuid, dailyreport.uid as duid from vehicle
inner join unit on vehicle.uid=unit.uid
inner join dailyreport on vehicle.uid = dailyreport.uid
where vehicle.customerno=97 order by vehicle.vehicleid ASC;


select vehicle.vehicleid, vehicle.uid as vuid, unit.uid as uuid, dailyreport.uid as duid from vehicle
inner join unit on vehicle.vehicleid=unit.vehicleid
inner join dailyreport on vehicle.vehicleid = dailyreport.vehicleid
where vehicle.customerno=97 order by vehicle.vehicleid ASC;

*/

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (267, NOW(), 'Shrikant Suryawanshi','Unit Mapping For Monginis Cairo');
