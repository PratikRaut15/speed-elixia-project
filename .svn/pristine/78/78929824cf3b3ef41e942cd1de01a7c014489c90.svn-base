INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('632', '2018-11-03 13:00:00', 'Yash Kanakia', 'Invoice Generation Link', '0');


DELIMITER $$
DROP procedure IF EXISTS `fetch_invoice_link_details`$$
CREATE PROCEDURE `fetch_invoice_link_details`(

    IN dateParam date

)
BEGIN

SELECT 
    i.invoiceid, c.customerno, u.userkey
FROM
    invoice i
        INNER JOIN
    customer c ON c.customerno = i.customerno
        INNER JOIN
    user u ON u.customerno = c.customerno
        AND u.role = 'elixir'
WHERE
    i.inv_date = dateParam;

END$$

DELIMITER ;

UPDATE  dbpatches
SET     isapplied = 1
WHERE   patchid = 632;
