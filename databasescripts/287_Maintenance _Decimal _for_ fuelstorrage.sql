ALTER TABLE fuelstorrage CHANGE amount amount DECIMAL(11,2) NOT NULL;
ALTER TABLE fuelstorrage CHANGE rate rate DECIMAL(11,2) NOT NULL;
ALTER TABLE fuelstorrage CHANGE average average DECIMAL(11,2) NOT NULL;
ALTER TABLE fuelstorrage CHANGE dealerid dealerid INTEGER(11) NOT NULL;
ALTER TABLE fuelstorrage CHANGE fuel fuel DECIMAL(11,2) NOT NULL;
-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (287, NOW(), 'Sahil Gandhi','Maintenance Integer To Decimal for fuelstorrage');
