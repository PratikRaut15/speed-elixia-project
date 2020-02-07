INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('9', '2018-10-20 17:00:00', 'Yash Kanakia', 'News Letter Content Changes', '0');

USE `elixiatech`;
DROP procedure IF EXISTS `insert_into_newsLetter_Subscription`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `insert_into_newsLetter_Subscription`(
    IN email_idParam varchar(100),
    IN guidParam VARCHAR(36),
    IN createdOnParam datetime,
    OUT newsLetterIdParam INT,
    OUT subscriptionIdParam INT
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
        SELECT subscriptionId INTO subscriptionIdParam
        FROM news_letter_subscription
        WHERE email_id = email_idParam AND is_active=1;

        IF(subscriptionIdParam IS NULL) THEN

        SET newsLetterIdParam = 0;
            BEGIN
                INSERT INTO news_letter_subscription(
                    guid,
                 email_id,
                 is_active,
                    createdOn
                )VALUES(
                   guidParam,
                   email_idParam,
                   1,
                   createdOnParam
                );

         SET newsLetterIdParam = LAST_INSERT_ID();

            END;
    ELSE
       SET subscriptionIdParam = 2;
END IF;
    COMMIT;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `fetch_guId`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `fetch_guId`(
IN guidParam text,
OUT isExistParam INT)
BEGIN

DECLARE tempGUId text;
	SET isExistParam=0;


	SELECT
    guid
INTO tempGUId FROM
    news_letter_subscription
WHERE
    guid = guidParam;

	IF(tempGUId IS NOT NULL) THEN
	SET isExistParam=1;
    END IF;

END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `fetch_newsLetter_Subscription_Emails`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `fetch_newsLetter_Subscription_Emails`()
BEGIN
SELECT ns.guid,ns.email_id
from news_letter_subscription ns
where ns.is_active = 1;

END$$

DELIMITER ;


UPDATE  dbpatches
SET     isapplied = 1
WHERE   patchid = 9;
