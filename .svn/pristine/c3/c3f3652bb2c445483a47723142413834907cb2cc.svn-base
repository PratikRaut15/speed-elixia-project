create table rolemapping(
rolemapid int NOT NULL PRIMARY KEY AUTO_INCREMENT,
vtsroleid int NOT NULL ,
vtsrole varchar(35) NOT NULL,
maintenanceroleid int NOT NULL,
maintenancerole varchar(35) NOT NULL,
userid int NOT NULL,
customerno int NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
created_by int NOT NULL,
updated_by int NOT NULL,
isdeleted tinyint(1) DEFAULT 0
);

ALTER TABLE rolemapping add moduleid tinyint NOT NULL AFTER maintenancerole;

ALTER TABLE rolemapping drop vtsroleid; 
ALTER TABLE rolemapping drop vtsrole;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_role_mapping$$
CREATE PROCEDURE get_role_mapping(
	IN useridparam INT
    ,IN custno INT
    ,IN moduleidparam INT
)
BEGIN

	IF(useridparam = '' OR useridparam = 0) THEN
		SET useridparam = NULL;
    END IF;
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(moduleidparam = '' OR moduleidparam = 0) THEN
		SET moduleidparam = NULL;
    END IF;
    
    
  IF(moduleidparam = 2)THEN 
	
  SELECT 
    maintenanceroleid as roleid ,
	maintenancerole as role
  FROM rolemapping
  WHERE (userid = useridparam OR useridparam IS NULL)
  AND (customerno = custno OR custno IS NULL)
  AND  isdeleted = 0;
  
  ELSE 
  
  SELECT 
    roleid,
	role
  FROM user
  WHERE (userid = useridparam OR useridparam IS NULL)
  AND (customerno = custno OR custno IS NULL)
  AND  isdeleted = 0;
  END IF;
  
  

END$$
DELIMITER ;




INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (350, NOW(), 'Shrikant Suryawasnhi','role mapping');
