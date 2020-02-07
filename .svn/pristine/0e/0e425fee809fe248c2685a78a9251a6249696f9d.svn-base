
/*
    Name          - fetch_chatdetails
    Description   - Fetch chat detail of a client for freshchat
    Parameters    - useridParam

    Module    		- SPEED
    Sample Call   - CALL fetch_chatdetails('7431')

    Created by    - Yash Kanakia
    Created on    - 26-12-2017
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS fetch_chatdetails$$
CREATE PROCEDURE `fetch_chatdetails`(IN `useridParam` INT)
BEGIN
              
SELECT 	chatDetailsId
		,customerno
		,external_id
		,restore_id
FROM 	chatdetails 
WHERE 	external_id = useridParam;
                    
END$$
DELIMITER ;