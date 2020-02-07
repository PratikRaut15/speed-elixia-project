
TRUNCATE TABLE `user`;

TRUNCATE TABLE `address`;

TRUNCATE TABLE `shippingdetails`;

TRUNCATE TABLE `orderrequest`;

TRUNCATE TABLE `estimate`;

TRUNCATE TABLE `discountmanage`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (4, NOW(), 'Mrudang','Truncate the user and order related tables as per Blessen');
