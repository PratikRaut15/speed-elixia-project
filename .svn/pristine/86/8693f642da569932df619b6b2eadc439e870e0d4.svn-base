
ALTER TABLE `orderrequest`  ADD `orderimage` TEXT NOT NULL  AFTER `paymentmodeid`;

ALTER TABLE `discountmanage`  ADD `orderid` int  NOT NULL AFTER `amount`;

ALTER TABLE `shippingdetails`  ADD `orderimage` TEXT NOT NULL  AFTER `insureshipment`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (3, NOW(), 'Shrikant Suryawanshi','Add Orderimage Id in orderrequest');
