INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('7', '2018-10-16 13:00:00', 'Yash Kanakia', 'News Letter Content', '0');

DROP TABLE IF EXISTS `newsLetter_content`;
CREATE TABLE `newsLetter_content`(
contentId INT PRIMARY KEY AUTO_INCREMENT,
contentTitle VARCHAR(200),
email_subject text,
email_body longtext,
filename text,
filepath text,
createdBy INT,
createdOn datetime,
updatedBy INT,
updatedOn datetime);


USE `elixiatech`;
DROP procedure IF EXISTS `insert_newsLetterContent`;

USE `elixiatech`;
DROP procedure IF EXISTS `insert_newsLetterContent`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `insert_newsLetterContent`(
	IN titleParam varchar(100),
    IN email_subjectParam VARCHAR(100),
    IN email_bodyParam longtext,
    IN createdByParam INT,
	IN createdOnParam datetime,
    OUT isExecutedOutParam INT,
    OUT newsLetterIdParam INT
)
BEGIN
 BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;
			SET isExecutedOutParam = 0;
            BEGIN
                INSERT INTO newsLetter_content(
					contentTitle,
                    email_subject,
					email_body,
                    createdBy,
					createdOn
                )VALUES(
				   titleParam,
                   email_subjectParam,
                   email_bodyParam,
                   createdByParam,
				   createdOnParam
                );

			SET isExecutedOutParam = 1;
            SET newsLetterIdParam = LAST_INSERT_ID();
            END;

	COMMIT;
END$$

DELIMITER ;



USE `elixiatech`;
DROP procedure IF EXISTS `fetch_newsLetterContent`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `fetch_newsLetterContent`(
IN contentIdParam INT
)
BEGIN
SELECT contentTitle,email_subject,email_body,filepath,filename
from newsLetter_content where contentId = contentIdParam;
END$$

DELIMITER ;



USE `elixiatech`;
DROP procedure IF EXISTS `fetch_newsLetterContent_name`;

DELIMITER $$
USE `elixiatech`$$
CREATE  PROCEDURE `fetch_newsLetterContent_name`(
IN termParam VARCHAR(255)
)
BEGIN

    SELECT
    contentId, contentTitle
FROM
   newsLetter_content
WHERE
    contentTitle LIKE CONCAT('%',termParam,'%');

END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `update_newsLetterContent`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `update_newsLetterContent`(
    IN newsLetterIdParam VARCHAR(36),
    IN filePathParam text,
    IN fileNameParam text,
    IN createdOnParam datetime,
    OUT isUpdatedOutParam INT
    )
BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
           /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;
            SET isUpdatedOutParam = 0; */
        END;
        BEGIN
            SET isUpdatedOutParam = 0;

            UPDATE `newsLetter_content`
                SET filepath = filePathParam,
					filename = fileNameParam,
					createdOn = createdOnParam
            WHERE   contentId = newsLetterIdParam;

            SET isUpdatedOutParam = 1;
        END;
    END$$

DELIMITER ;


UPDATE  dbpatches
SET     isapplied = 1
WHERE   patchid = 7;
