/*
    Name          - delete_from_ticketusermapping
    Description   - delete from ticketusrmapping before insert the new one
    Parameters    - ticketid
    Module    - ERP
    Sample Call   -CALL delete_from_ticketusermapping('ticketid#')

    Created by    - Manasvi Thakur
    Created on    - 22-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS delete_from_ticketusermapping$$
CREATE PROCEDURE delete_from_ticketusermapping(
IN ticketidIn INT(20))
BEGIN
    DELETE FROM ticket_user_mapping 
    WHERE ticketid  =   ticketidIn;

END $$
DELIMITER ;


call delete_from_ticketusermapping('430');