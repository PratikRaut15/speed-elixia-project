-- Insert SQL here.
ALTER TABLE description ADD seatcpacity VARCHAR(10) NOT NULL AFTER invoiceamt;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 169, NOW(), 'Shrikanth Suryawanshi','Seat Capacity');
