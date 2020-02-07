
/*
    Name		-	authenticate_for_team_login
    Description 	-	authenticate team login.
    Parameters		-
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-
    Created by		-	Arvind
    Created on		- 	24 Nov, 2016
    Change details 	-
    1) 	Updated by	- Mrudang Vora
	    Updated	on	- 30 OCt 2017
        Reason		- Added Role

        TODO   :    Remove out params and have SELECT query
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS authenticate_for_team_login$$
CREATE PROCEDURE authenticate_for_team_login(
	IN usernameparam VARCHAR(50)
	,IN passparam VARCHAR(150)
	,OUT userkeyparam VARCHAR(150)
    ,OUT teamidparam INT
    ,OUT roleparam VARCHAR(50)
    ,OUT companyRoleIdParam INT
)
BEGIN
    DECLARE userkeydata VARCHAR(150);
	DECLARE teamiddata INT;
    /*TODO   :    Remove out params and have SELECT query instead*/
    SELECT  teamid,userkey,rolem,company_roleId
    INTO    teamiddata,userkeydata, roleparam ,companyRoleIdParam
	FROM    team
	WHERE   username = usernameparam
	AND 	`password` = passparam;

	IF (teamiddata IS NULL)THEN
            BEGIN
		SET userkeyparam='Empty';
            END;
        ELSE
            BEGIN
                IF (userkeydata IS NULL OR userkeydata='') THEN
                    BEGIN
                        UPDATE  team
                        SET     userkey = FLOOR(1+RAND()*10000)
                        WHERE   teamid = teamiddata;

                        SELECT  userkey
                        INTO    userkeydata
                        FROM    team
                        where   teamid = teamiddata;

                        SET     userkeyparam = userkeydata;
                        SET     teamidparam = teamiddata;
                    END;
                ELSE
                    BEGIN
                        SELECT  userkey
                        INTO    userkeydata
                        FROM    team
                        where   teamid=teamiddata;

                        SET     userkeyparam = userkeydata;
                        SET     teamidparam=teamiddata;
                    END;
                END IF;
            END;
        END IF;

END$$
DELIMITER ;