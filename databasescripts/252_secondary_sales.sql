-- Insert SQL here.
-- Table structure for table `invoice`
--

alter table `customer`  add column use_secondary_sales tinyint(1) default 0 after use_mobility;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 252, NOW(), 'Ganesh','new column in customer for sales');
