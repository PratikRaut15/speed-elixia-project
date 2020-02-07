ALTER TABLE maintenance CHANGE amount_quote amount_quote DECIMAL(11,2) NOT NULL;ALTER TABLE maintenance CHANGE invoice_amount invoice_amount DECIMAL(11,2) NOT NULL;ALTER TABLE maintenance CHANGE tax tax DECIMAL(11,2) NOT NULL;ALTER TABLE maintenance_history CHANGE amount_quote amount_quote DECIMAL(11,2) NOT NULL;ALTER TABLE maintenance_history CHANGE invoice_amount invoice_amount DECIMAL(11,2) NOT NULL;ALTER TABLE maintenance_history CHANGE tax tax DECIMAL(11,2) NOT NULL;ALTER TABLE accessory CHANGE max_amount max_amount DECIMAL(11,2) NOT NULL;
ALTER TABLE accessory_map CHANGE amount amount DECIMAL(11,2) NOT NULL;ALTER TABLE accessory_map CHANGE cost cost DECIMAL(11,2) NOT NULL;ALTER TABLE maintenance_parts CHANGE amount amount DECIMAL(11,2) NOT NULL;
ALTER TABLE maintenance_parts CHANGE total total DECIMAL(11,2) NOT NULL;ALTER TABLE maintenance_tasks CHANGE amount amount DECIMAL(11,2) NOT NULL;
ALTER TABLE maintenance_tasks CHANGE total total DECIMAL(11,2) NOT NULL;ALTER TABLE accident CHANGE loss_amount loss_amount DECIMAL(11,2) NOT NULL;
ALTER TABLE accident CHANGE sett_amount sett_amount DECIMAL(11,2) NOT NULL;
ALTER TABLE accident CHANGE actual_amount actual_amount DECIMAL(11,2) NOT NULL;
ALTER TABLE accident CHANGE mahindra_amount mahindra_amount DECIMAL(11,2) NOT NULL;ALTER TABLE accident_history CHANGE loss_amount loss_amount DECIMAL(11,2) NOT NULL;
ALTER TABLE accident_history CHANGE sett_amount sett_amount DECIMAL(11,2) NOT NULL;
ALTER TABLE accident_history CHANGE actual_amount actual_amount DECIMAL(11,2) NOT NULL;
ALTER TABLE accident_history CHANGE mahindra_amount mahindra_amount DECIMAL(11,2) NOT NULL; 

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (286, NOW(), 'Sahil Gandhi','Maintenance Integer To Decimal');
