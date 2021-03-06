INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('8', '2018-10-16 13:00:00', 'Yash Kanakia', 'News Letter Subscription', '0');

DROP TABLE IF EXISTS `news_letter_subscription`;
CREATE TABLE `news_letter_subscription`(
subscriptionId INT PRIMARY KEY AUTO_INCREMENT,
guid VARCHAR(36),
email_id VARCHAR(100),
is_active tinyint,
createdOn datetime,
updatedOn datetime);

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
        SELECT subscriptionId INTO subscriptionIdParam from news_letter_subscription where email_id = email_idParam;

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



DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `update_newsLetter_Subscription`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `update_newsLetter_Subscription`(
    IN guidParam VARCHAR(36),
    IN updatedOnParam datetime,
    OUT isUpdatedParam INT
    )
BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            /*ROLLBACK;
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;
            SET isUpdatedParam = 0; */
        END;
        BEGIN
            SET isUpdatedParam = 0;

            UPDATE `news_letter_subscription`
                SET `is_active` = 0,
                    `updatedOn` = updatedOnParam
            WHERE `guid` = guidParam;

            SET isUpdatedParam = 1;
        END;
    END$$

DELIMITER ;

UPDATE  dbpatches
SET     isapplied = 1
WHERE   patchid = 8;



