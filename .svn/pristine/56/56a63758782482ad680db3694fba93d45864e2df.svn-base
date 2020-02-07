DELIMITER $$
CREATE TRIGGER before_creditnote_insert
BEFORE INSERT
ON credit_note FOR EACH ROW
BEGIN
-- SET credit_note_no = CONCAT('CRN' ,credit_note_id);
 DECLARE selectid INT DEFAULT 0;
     SET selectid := (select CONVERT(SUBSTRING_INDEX(credit_note_id,'O',-1),UNSIGNED INTEGER) AS num  from credit_note order by credit_note_id desc limit 1);
     SET selectid := selectid + 1;
     SET NEW.credit_note_no = CONCAT('CRN',selectid);
END $$
DELIMITER ;