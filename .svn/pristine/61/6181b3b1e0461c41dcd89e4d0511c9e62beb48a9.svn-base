-- Insert SQL here.

alter table role add parentroleid int(11) NOT NULL After role;
alter table role add moduleid int(11) NOT NULL After parentroleid;
alter table role add sequenceno tinyint NOT NULL after moduleid;
alter table role add customerno int(11) NOT NULL After moduleid;
alter table role add createdby int(11) NOT NULL After customerno;
alter table role add updatedby int(11) NOT NULL After createdby;
alter table role add created_on datetime NOT NULL After updatedby;
alter table role add updated_on datetime NOT NULL After created_on;
alter table role add isdeleted tinyint(1) NOT NULL DEFAULT '0' after updated_on;


create table maintenance_transactiontype(
transactiontypeid int(11) Primary Key AUTO_INCREMENT,
categoryname varchar(50) NOT NULL,
isdeleted tinyint(1) NOT NULL DEFAULT '0'
);



create table maintenance_conditions(
conditionid int(11) Primary Key AUTO_INCREMENT,
transactiontypeid int(11) NOT NULL,
conditionname varchar(50) NOT NULL,
customerno int(11) NOT NULL,
created_by int(11) NOT NULL,
updated_by int(11) NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
isdeleted tinyint(1) NOT NULL DEFAULT '0'
);


create table maintenance_rules(
ruleid int(11) Primary Key AUTO_INCREMENT,
conditionid int(11) NOT NULL,
minval varchar(25) NOT NULL,
maxval varchar(25) NOT NULL,
sequenceno int(11) NOT NULL,
approverid int(11) NOT NULL,
customerno int(11) NOT NULL,
created_by int(11) NOT NULL,
updated_by int(11) NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
isdeleted tinyint(1) NOT NULL DEFAULT '0'
);




DELIMITER $$
DROP PROCEDURE IF EXISTS insert_maintenance_conditions $$
CREATE PROCEDURE insert_maintenance_conditions
( 
     IN transtypeid INT
    , IN conditionname varchar(50)
    , IN minval INT
    , IN maxval INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentconditionid INT
)
BEGIN
	INSERT INTO maintenance_conditions(
                            transactiontypeid
                            , conditionname
                            , minval
                            , maxval
                            , customerno
                            , created_on
                            , updated_on
                            , created_by
                            , updated_by
			)
	VALUES ( 
		transtypeid
                , conditionname
                , minval
                , maxval
                , customerno
                , todaysdate
		, todaysdate
                , userid
                , userid
                );
            
	SET currentconditionid = LAST_INSERT_ID();

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS update_maintenance_conditions $$
CREATE PROCEDURE update_maintenance_conditions
( 
    IN cond_id INT
    ,IN transtypeid INT
    , IN conditionname varchar(50)
    , IN minval INT
    , IN maxval INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE maintenance_conditions
        SET  transactiontypeid = transtypeid
	, conditionname = conditionname
	, minval = minval
	, maxval = maxval
	, customerno = custno
	, updated_on = todaysdate
        , updated_by = userid
	WHERE conditionid = cond_id;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS  delete_maintenanace_conditions $$
CREATE PROCEDURE delete_maintenanace_conditions
(
    IN cond_id INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE maintenanace_conditions 
    SET  isdeleted = 1
	, updated_on = todaysdate
        , updated_by = userid
	WHERE conditionid = cond_id;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_maintenance_rules $$
CREATE PROCEDURE insert_maintenance_rules
( 
     IN conditionid INT
    , IN minval varchar(25)
    , IN maxval varchar(25)
    , IN sequenceno INT
    , IN approverid INT
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentruleid INT
)
BEGIN
	INSERT INTO maintenance_rules(
                            conditionid
                            , minval
                            , maxval
                            , sequenceno
                            , approverid
                            , customerno
                            , created_on
                            , updated_on
                            , created_by
                            , updated_by
			)
	VALUES ( 
		conditionid
                , minval
                , maxval
                , sequenceno
                , approverid
                , customerno
                , todaysdate
		, todaysdate
                , userid
                , userid
                );
            
	SET currentruleid = LAST_INSERT_ID();
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS update_maintenance_rules $$
CREATE PROCEDURE update_maintenance_rules
( 
    IN rid INT
    , IN minvalue varchar(25)
    , IN mvalue varchar(25)
    , IN custno INT
    , IN sequnce INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE maintenance_rules
        SET  minval = minvalue
	, maxval = mvalue
	, customerno = custno
	, sequenceno = sequnce
	, updated_on = todaysdate
        , updated_by = userid
	WHERE ruleid = rid;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS delete_maintenanace_rules $$
CREATE PROCEDURE delete_maintenanace_rules
(
    IN rid INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE maintenance_rules 
        SET  isdeleted = 1
	, updated_on = todaysdate
        , updated_by = userid
	WHERE ruleid = rid
        AND customerno = custno;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_transactionrules $$
CREATE PROCEDURE get_transactionrules
( 
    IN custno INT
    , IN ruleidparam INT
    , IN conditionidparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(ruleidparam = '' OR ruleidparam = 0) THEN
        SET ruleidparam = NULL;
    END IF;
    IF(conditionidparam = '' OR conditionidparam = 0) THEN
        SET conditionidparam = NULL;
    END IF;

    SELECT 
        mr.ruleid
        ,mr.conditionid
        ,mr.minval
        ,mr.maxval
        ,mr.sequenceno
        ,mr.approverid
        ,mc.conditionname
        ,mt.categoryname
        ,roles.role
    FROM maintenance_rules as mr
    INNER JOIN maintenance_conditions as mc on mc.conditionid = mr.conditionid
    INNER JOIN maintenance_transactiontype as mt on mt.transactiontypeid = mc.transactiontypeid
    INNER JOIN `role` as roles on roles.id = mr.approverid
    WHERE (mr.ruleid = ruleidparam OR ruleidparam IS NULL)
    AND   (mr.conditionid = conditionidparam OR conditionidparam IS NULL)
    AND   (mr.customerno = custno OR custno IS NULL)
    AND   mr.isdeleted = 0
    ORDER BY mr.ruleid ASC;

END$$
DELIMITER ;





DELIMITER $$
DROP PROCEDURE IF EXISTS get_role $$
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
            , (select role from role where id=pid and pid<>0)as prole
            , moduleid
            , customerno
	FROM role
        WHERE (id = rid OR rid IS NULL)
        AND (parentroleid = parentid OR parentid IS NULL)
        AND (moduleid = mid OR mid IS NULL)
        AND (customerno = custno OR custno IS NULL)
        AND isdeleted=0 order by sequenceno ASC;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_role $$ 
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
    INSERT INTO role(
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

    /* Re-sequencing hierarachy*/
    CALL sequence_role(custno);

END$$
DELIMITER ;


DELIMITER $$
/* DROP PROCEDURE IF EXISTS update_role $$ */
CREATE PROCEDURE update_role
( 
    IN roleid INT
    , IN parentid INT
    , IN rolename varchar(50)
    , IN moduleid INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	DECLARE currentparentroleid INT;
	DECLARE childroleid INT;
	
        SELECT parentroleid into currentparentroleid from role 
        WHERE  id = roleid;

        SELECT id into childroleid from role 
        WHERE parentroleid = parentid;
        
        IF(roleid IS NOT NULL && roleid != 0) THEN
            BEGIN
                /* 
                    Assign current role's parent to current role's child
                */
                UPDATE role
                SET     parentroleid = currentparentroleid
                WHERE   parentroleid = roleid
                AND     customerno = custno;
                /* 
                    Assign current role as parent to the child of passed parent role
                */
                UPDATE role
                SET     parentroleid = roleid 
                WHERE   id = childroleid
                AND     customerno = custno;
                /* 
                    Update the current role details
                */
                UPDATE role
                SET     parentroleid = parentid
                        , role = rolename
                        , moduleid = moduleid
                        , updated_on = todaysdate
                        , updated_by = userid
                WHERE   id = roleid 
                AND     customerno = custno;
                /* Re-sequencing hierarachy*/
                CALL sequence_role(custno);
            END;
        END IF;
END$$
DELIMITER ;

DELIMITER $$
/* DROP PROCEDURE IF EXISTS delete_role $$ */
CREATE PROCEDURE delete_role
( 
    IN roleid INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	DECLARE currentparentroleid INT;
	DECLARE childroleid INT;
	
        SELECT parentroleid into currentparentroleid from role 
        WHERE  id = roleid;

        SELECT id into childroleid from role 
        WHERE parentroleid = roleid;
        /* Check For parentroleid is 0 */
        IF (currentparentroleid is NOT NULL && currentparentroleid != 0) THEN
            BEGIN
                IF(childroleid IS NOT NULL && childroleid != 0) THEN
                    /* 
                        Assign current role's parent to current role's child
                    */
                    UPDATE role
                    SET     parentroleid = currentparentroleid
                    WHERE   id = childroleid
                    AND     customerno = custno
                    AND     isdeleted = 0;

                END IF;
                /* 
                        Update the current role details
                */
                UPDATE role
                SET     isdeleted = 1
                WHERE   id = roleid 
                AND     customerno = custno;
            END;
            /* Re-sequencing hierarachy*/
            CALL sequence_role(custno);
        END IF;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS sequence_role $$ 
CREATE PROCEDURE sequence_role
( 
    IN custno INT
)
BEGIN
        DECLARE roleid INT DEFAULT 0;
	DECLARE parentid INT DEFAULT 0;
	DECLARE counter INT DEFAULT 0;
	
	SET roleid = 0;
	SET parentid = 0;
	SET counter = 0;

        
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
	

        /* 
            TO Handle Warning : 1329 No data - zero rows fetched, selected, or processed.
        */
        SELECT custno;
        
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_transactiontype $$
CREATE PROCEDURE get_transactiontype
( 
    IN transaction_typeid INT
)
BEGIN
    
    SELECT 
        transactiontypeid
        ,categoryname
    FROM maintenance_transactiontype    
    WHERE 
        isdeleted = 0;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_transactionconditions $$
CREATE PROCEDURE get_transactionconditions
( 
    IN transtypeidparam INT
    ,IN conditionparam varchar(50)
    ,IN custno INT
)
BEGIN
    IF(transtypeidparam = '' OR transtypeidparam = 0) THEN
        SET transtypeidparam = NULL;
    END IF;
    IF(conditionparam = '' OR conditionparam = 0) THEN
        SET conditionparam = NULL;
    END IF;
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    
    SELECT 
        conditionid
        ,conditionname
    FROM maintenance_conditions    
    WHERE (transactiontypeid = transtypeidparam OR transtypeidparam IS NULL)
    AND   (conditionname = conditionparam OR conditionparam IS NULL)
    AND   (customerno = custno OR custno IS NULL)
    AND   isdeleted = 0;

END$$
DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 315, NOW(), 'Shrikant Suryawanshi','Hierarchy Settings');
