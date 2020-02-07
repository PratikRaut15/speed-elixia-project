-- Insert SQL here.

alter table user 
drop column thhistorypdf, 
drop column thhistorycsv, 
drop column gensetpdf, 
drop column gensetcsv, 
drop column summarypdf, 
drop column summarycsv;



-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 145, NOW(), 'Akhil VL', 'Drop 6 columns in user-table');