USE `elixiatech`;
DROP procedure IF EXISTS `calculate_taxes`;
DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `calculate_taxes`(
	In ledgerNoParam Int,
    In amtParam Float,
    Out cgstOut Float,
    Out sgstOut Float,
    Out igstOut Float
)
BEGIN
	DECLARE stateCodeVar INT;
    
    Select state_code into stateCodeVar From speed.ledger Where ledgerid = ledgerNoParam;
    
    CASE WHEN stateCodeVar = 27 THEN
		SET cgstOut = round(amtParam*0.09);
        SET sgstOut = round(amtParam*0.09);
        SET igstOut = 0;
	ELSE
		SET cgstOut = 0;
        SET sgstOut = 0;
        SET igstOut = round(amtParam*0.18);
    END CASE;
END$$
DELIMITER ;