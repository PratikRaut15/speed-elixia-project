-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'406', '2016-08-31 17:05:01', 'Arvind Thakur', 'create zone,zoneman and geozone table', '0'
);


-- Insert SQL here.

CREATE TABLE zone(
    zoneid int(11) AUTO_INCREMENT,
    customerno int(11),
    zonename varchar(100),
    userid int(11),
    isdeleted tinyint(1) DEFAULT 0,
    PRIMARY KEY(zoneid));

CREATE TABLE geozone(
    geozoneid int(11) AUTO_INCREMENT,
    zoneid int(11),
    customerno int(11),
    geolat float,
    geolong float,
    isdeleted tinyint(1) DEFAULT 0,
    userid int(11),
    PRIMARY KEY(geozoneid));

CREATE TABLE zoneman(
    zmid int(11) AUTO_INCREMENT,
    zoneid int(11),
    customerno int(11),
    vehicleid int(11),
    isdeleted tinyint(1) DEFAULT 0,
    userid int(11),
    PRIMARY KEY(zmid));

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 406;
