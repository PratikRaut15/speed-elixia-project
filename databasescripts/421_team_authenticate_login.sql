
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'421', '2016-10-14 16:11:01', 'Arvind Thakur', 'Team API changes', '0'
);

ALTER TABLE team
ADD COLUMN userkey varchar(150) NOT Null;


-------------------------proc changes----------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS authenticate_for_team_login$$
CREATE PROCEDURE authenticate_for_team_login(
	IN usernameparam VARCHAR(50)
	,IN passparam VARCHAR(150)
	,OUT userkeyparam VARCHAR(150)
)
BEGIN
        DECLARE userkeydata VARCHAR(150);
	DECLARE teamidparam INT;
  
	SELECT  teamid,userkey
	INTO	teamidparam,userkeydata
	FROM    team
	WHERE   username = usernameparam
	AND 	`password` = passparam;
	
	IF (teamidparam IS NULL)THEN 
            BEGIN
		SET userkeyparam='Empty';
            END;
        ELSE
            BEGIN
                IF (userkeydata IS NULL OR userkeydata='') THEN 
                    BEGIN 
                        UPDATE team SET userkey=FLOOR(1+RAND()*10000) WHERE teamid=teamidparam;
                        SELECT userkey INTO userkeydata FROM team where teamid=teamidparam;
                        SET userkeyparam = userkeydata;
                    END;
                ELSE
                    BEGIN
                        SELECT userkey INTO userkeydata FROM team where teamid=teamidparam;
                        SET userkeyparam = userkeydata;
                    END;
                END IF;
            END;
        END IF;

END$$
DELIMITER ;


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 421;
