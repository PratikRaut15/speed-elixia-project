-- Insert SQL here.

DELIMITER $$
DROP PROCEDURE IF EXISTS sequence_role$$
CREATE PROCEDURE sequence_role
( 
    IN custno INT
)
BEGIN
        DECLARE roleid INT DEFAULT 0;
	DECLARE parentid INT DEFAULT 0;
	DECLARE counter INT DEFAULT 1;
        SET counter = 1;
        
        WHILE (counter != -1) DO
                SET     roleid = 0;
                SELECT  id INTO roleid FROM role
                WHERE   parentroleid = parentid
                AND     isdeleted = 0
                AND     customerno = custno;
                IF (roleid != 0) THEN
                    BEGIN
                        UPDATE  role
                        SET     sequenceno = counter
                        WHERE   id = roleid
                        AND     isdeleted = 0
                        AND     customerno = custno;

                        SET parentid = roleid;
                        SET counter = counter + 1;
                    END;
                ELSE
                    BEGIN
                        SET counter = -1;
                    END;
                END IF;
        END WHILE;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS insert_role$$
CREATE PROCEDURE insert_role
( 
     IN rolename varchar(50)
    , IN parentid INT
    , IN moduleid INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentroleid INT
)
BEGIN
    INSERT INTO `role`(
                        role
                        , parentroleid
                        , moduleid
                        , customerno
                        , created_on
                        , updated_on
                        , created_by
                        , updated_by
                    )
    VALUES ( 
            rolename
            , parentid
            , moduleid
            , custno
            , todaysdate
            , todaysdate
            , userid
            , userid
            );
            
    SET currentroleid = LAST_INSERT_ID();

    IF(parentid IS NOT NULL && parentid != 0) THEN
            Update role
            SET     parentroleid = currentroleid
            Where   parentroleid = parentid 
            AND id NOT IN(0, currentroleid);
    END IF;

    /* Re-sequencing hierarachy */
    CALL sequence_role(custno);

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS update_role $$
CREATE PROCEDURE update_role
( 
    IN roleid INT
    , IN parentid INT
    , IN rolename varchar(50)
    , IN moduleidparam INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	DECLARE currentparentroleid INT;
	DECLARE childroleid INT;
	
        SELECT  parentroleid 
        INTO    currentparentroleid 
        FROM    role 
        WHERE   id = roleid
        AND     moduleid = moduleidparam
        AND     customerno = custno
        AND     isdeleted = 0;

        SELECT  id 
        INTO    childroleid 
        FROM    role 
        WHERE   parentroleid = parentid
        AND     customerno = custno
        AND     isdeleted = 0;
        
        IF(roleid IS NOT NULL && roleid != 0) THEN
            BEGIN
                /* 
                    Assign current role's parent to current role's child
                */
                UPDATE `role`
                SET     parentroleid = currentparentroleid
                WHERE   parentroleid = roleid
                AND     moduleid = moduleidparam
                AND     customerno = custno;
                /* 
                    Update the current role details
                */
                UPDATE `role`
                SET     parentroleid = parentid
                        , `role` = rolename
                        , updated_on = todaysdate
                        , updated_by = userid
                WHERE   id = roleid
                AND     moduleid = moduleidparam
                AND     customerno = custno;
                /* 
                    Assign current role as parent to the child of passed parent role
                */
                UPDATE `role`
                SET     parentroleid = roleid 
                WHERE   id = childroleid
                AND     moduleid = moduleidparam
                AND     customerno = custno;
                
                /* Re-sequencing hierarachy*/
                CALL sequence_role(custno);
                SELECT custno;
            END;
        END IF;
END $$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_role$$
CREATE PROCEDURE get_role
( 
     IN rid INT
    , IN parentid INT
    , IN mid INT
    , IN custno INT
    
)
BEGIN
        IF(rid = '' OR rid = 0) THEN
		SET rid = NULL;
	END IF;
        IF(parentid = '' OR parentid = 0) THEN
		SET parentid = NULL;
	END IF;
        IF(mid = '' OR mid = 0) THEN
		SET mid = NULL;
	END IF;
        IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;

	SELECT 
            id
            , role
            , parentroleid as pid
            , (select role FROM role where id=pid and pid<>0)as prole
            , role.moduleid
            , customerno
            , modulename
	FROM role
        INNER JOIN modules on modules.moduleid = role.moduleid
        WHERE (id = rid OR rid IS NULL)
        AND (parentroleid = parentid OR parentid IS NULL)
        AND (role.moduleid = mid OR mid IS NULL)
        AND (customerno = custno OR custno IS NULL)
        AND role.isdeleted=0 order by sequenceno ASC;

END $$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_users_forparentrole$$ 
CREATE PROCEDURE get_users_forparentrole
( 
    IN roleidparam INT
    , IN moduleidparam INT
    , IN custno INT
)
BEGIN
    DECLARE parentroleidparam INT DEFAULT 0;

    SELECT 
    parentroleid INTO parentroleidparam
    FROM role 
    WHERE customerno = custno
    AND   moduleid = moduleidparam
    AND   id = roleidparam
    AND   isdeleted = 0;
    
    IF(parentroleidparam != 0) THEN
    SELECT 
          userid
        , username
        , realname
        , email
    FROM user 
    WHERE roleid = parentroleidparam
    AND customerno = custno
    AND isdeleted =0;
    END IF;

END $$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_users_groups$$ 
CREATE PROCEDURE get_users_groups
( 
    IN roleidparam INT
    , IN moduleidparam INT
    , IN custno INT
)
BEGIN
    DECLARE hierarchy_id INT DEFAULT 0;

    SELECT 
        heirarchy_id 
    INTO 
        hierarchy_id 
    FROM user
    WHERE userid = roleidparam
    AND   customerno = custno
    AND   isdeleted = 0;

    IF (hierarchy_id != 0) THEN
        SELECT 
              gm.groupid
            , gm.userid
            , gm.customerno
            , g.groupname
        FROM groupman as gm
        INNER JOIN `group` as g on g.groupid = gm.groupid
        WHERE gm.userid = roleidparam
        AND gm.customerno = custno
        AND gm.isdeleted =0;
    ELSE 
        SELECT 
              groupid
            , groupname
        FROM  `group`
        WHERE customerno = custno
        AND isdeleted = 0;
    END IF;
    

END $$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 316, NOW(), 'Shrikant Suryawanshi','Hierarchy Settings');
