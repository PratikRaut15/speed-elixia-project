DROP TABLE IF EXISTS pipelineFileVersions;
CREATE TABLE pipelineFileVersions (pipelineId INT,docType INT, version INT,extensionType INT(11),remarks VARCHAR(255), uploadedTime DATETIME, uploadedBy INT,isdeleted TINYINT DEFAULT 0);

DROP TABLE IF EXISTS  salesDocType;
CREATE TABLE salesDocType (
	docId INT AUTO_INCREMENT PRIMARY KEY,
	docTypeName VARCHAR (100),
	createdBy INT,
	createdOn DATETIME,
	isdeleted TINYINT DEFAULT 0
);
INSERT INTO salesDocType (docTypeName,createdBy,createdOn,isdeleted) VALUES('Quotation','112',NOW(),0),('BRD','112',NOW(),0);

DROP TABLE IF EXISTS `extensionTypeMaster`;
CREATE TABLE `extensionTypeMaster`(
	extensionId INT PRIMARY KEY AUTO_INCREMENT,
    extensionTypeName VARCHAR(10),
    createdBy INT,
    createdOn DATETIME,
    isdeleted TINYINT
);
INSERT INTO `extensionTypeMaster` (extensionId,extensionTypeName,createdBy,createdOn,isdeleted) VALUES (1,'pdf','112',NOW(),0),(2,'doc','112',NOW(),0),(3,'docx','112',NOW(),0),(4,'xls','112',NOW(),0);


ALTER TABLE speed.sales_pipeline ADD COLUMN create_platform TINYINT AFTER subscription_cost ;
ALTER TABLE speed.sales_pipeline ADD COLUMN update_platform TINYINT AFTER create_platform ;
ALTER TABLE speed.sales_pipeline ADD COLUMN delete_platform TINYINT AFTER update_platform ;
ALTER TABLE speed.sales_pipeline ADD COLUMN tepidity TINYINT DEFAULT 2 AFTER company_name ;
ALTER TABLE speed.sales_pipeline ADD COLUMN quantity INT AFTER loss_reason;
ALTER TABLE speed.sales_pipeline ADD COLUMN quotation_request TINYINT AFTER subscription_cost;
ALTER TABLE speed.sales_pipeline ADD COLUMN quotation_text VARCHAR(255) AFTER quotation_request;
ALTER TABLE speed.sales_pipeline ADD COLUMN quotationDetails VARCHAR(255) AFTER quotation_text;
ALTER TABLE speed.sales_pipeline ADD COLUMN revive_date DATETIME after stageid;


ALTER TABLE speed.sales_pipeline_history ADD COLUMN create_platform TINYINT AFTER subscription_cost ;
ALTER TABLE speed.sales_pipeline_history ADD COLUMN update_platform TINYINT AFTER create_platform ;
ALTER TABLE speed.sales_pipeline_history ADD COLUMN delete_platform TINYINT AFTER update_platform ;
ALTER TABLE speed.sales_pipeline_history ADD COLUMN tepidity TINYINT DEFAULT 2 AFTER company_name ;
ALTER TABLE speed.sales_pipeline_history ADD COLUMN quantity INT AFTER loss_reason;
ALTER TABLE speed.sales_pipeline_history ADD COLUMN quotation_request TINYINT AFTER subscription_cost;
ALTER TABLE speed.sales_pipeline_history ADD COLUMN quotation_text VARCHAR(255) AFTER quotation_request;
ALTER TABLE speed.sales_pipeline_history ADD COLUMN quotationDetails VARCHAR(255) AFTER quotation_text;
ALTER TABLE speed.sales_pipeline_history ADD COLUMN revive_date DATETIME after stageid;


DROP TABLE IF EXISTS `sales_tepidity`;
CREATE TABLE `sales_tepidity`(
	tepidityId INT AUTO_INCREMENT PRIMARY KEY,
    tepidityName VARCHAR (25),
    isdeleted TINYINT DEFAULT 0
);
INSERT INTO `sales_tepidity` (tepidityName) VALUES ('Low'),('Medium'),('High');

DELIMITER $$
DROP procedure IF EXISTS `versionFile`$$
CREATE PROCEDURE `versionFile`(
	IN pipelineIdParam INT,
    IN docTypeParam INT,
    IN extParam VARCHAR(10),
    IN todayParam DATETIME,
    IN teamIdParam INT,
    OUT isexecutedOut TINYINT,
    OUT versionOut INT,
    OUT docTypeOut VARCHAR (100)
    )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
            /*
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
			@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
			SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
			SELECT @full_error;
            */
			SET isexecutedOut = 0;
		END;

		SET isexecutedOut = 0;

		START TRANSACTION;
		BEGIN
			DECLARE versionVar INT;
			DECLARE fileNameVar VARCHAR (255);
            DECLARE extVar INT;

			SELECT docTypeName into docTypeOut FROM salesDocType WHERE docId = docTypeParam LIMIT 1;
            SELECT extensionId into extVar FROM extensionTypeMaster WHERE extensionTypeName LIKE CONCAT(extParam,'%') LIMIT 1;
            IF(extVar IS NULL) THEN
				INSERT INTO extensionTypeMaster (`extensionTypeName`,`createdBy`,`createdOn`,`isdeleted`) VALUE(extParam,teamIdParam,todayParam,0);
				SET extVar = LAST_INSERT_ID();
            END IF;
			SELECT max(version) INTO versionVar FROM pipelineFileVersions WHERE pipelineId = pipelineIdParam AND docType = docTypeParam LIMIT 1;

            IF(versionVar IS NULL) THEN
					SET versionVar = 1;
			ELSE
				SET versionVar = versionVar + 1;
            END IF;

            INSERT INTO pipelineFileVersions (pipelineId, docType, version, uploadedTime, uploadedBy,extensionType)
            VALUES (pipelineIdParam,docTypeParam,versionVar,todayParam,teamIdParam,extVar);
            SET versionOut = versionVar;
		END;
		COMMIT;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetchPipelineFiles`$$
CREATE PROCEDURE `fetchPipelineFiles`(
	IN pipelineIdParam INT
)
BEGIN
	SELECT s.docTypeName,p.version,p.uploadedTime, t.name, p.remarks,e.extensionTypeName,p.docType
    FROM pipelineFileVersions p
    LEFT JOIN salesDocType s ON p.docType = s.docId
    LEFT JOIN team t ON p.uploadedBy = t.teamid
    LEFT JOIN extensionTypeMaster e ON e.extensionId = p.extensionType
    WHERE 	p.pipelineId = pipelineIdParam
		AND p.isdeleted = 0;
END$$

DELIMITER ;

