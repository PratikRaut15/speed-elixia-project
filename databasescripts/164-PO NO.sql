-- Insert SQL here.

ALTER TABLE devices ADD po_no varchar(50) NOT NULL AFTER invoiceno;
ALTER TABLE devices ADD po_date date NOT NULL AFTER po_no;
ALTER TABLE devices ADD warrantyexpiry date NOT NULL AFTER po_date;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 164, NOW(), 'Shreekanth','PO Number');
