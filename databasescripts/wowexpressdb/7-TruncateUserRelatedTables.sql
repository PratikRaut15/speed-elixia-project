TRUNCATE TABLE `address`;

TRUNCATE TABLE `estimate`;

TRUNCATE TABLE `orderdiscountmanage`;

TRUNCATE TABLE `orderrequest`;

TRUNCATE TABLE `orderrequesthistory`;

TRUNCATE TABLE `orderreturn`;

TRUNCATE TABLE `orderreturnhistory`;

TRUNCATE TABLE `returnitemdetails`;

TRUNCATE TABLE `shippingdetails`;

TRUNCATE TABLE `user`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (7, NOW(), 'Mrudang','Truncate the user and order related tables as per Blessen');
