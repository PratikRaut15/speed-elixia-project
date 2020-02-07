USE wowexpress;
ALTER TABLE `orderreturn` ADD `returnitemimage` TEXT NOT NULL AFTER `paymentmodeid`;
ALTER TABLE `orderreturnhistory` ADD `returnitemimage` TEXT NOT NULL AFTER `paymentmodeid`;
ALTER TABLE `orderreturn` DROP `orderid`;
ALTER TABLE `orderreturnhistory` DROP `orderid`;
ALTER TABLE `orderreturn` DROP `toaddressid`;
ALTER TABLE `orderreturnhistory` DROP `toaddressid`;


RENAME TABLE `wowexpress`.`discountmanage` TO `wowexpress`.`orderdiscountmanage`;

Create Table `returnitemdetails`(
id int(11) PRIMARY KEY AUTO_INCREMENT,
orderreturnid int(11) NOT NULL,
ecommercepartnerid int(11) NOT NULL,
invoiceno varchar(25) NOT NULL,
reason varchar(150) NOT NULL,
addedon datetime,
updatedon datetime
);
  
ALTER TABLE `returnitemdetails` ADD `returnitemimage` TEXT NOT NULL AFTER `reason`;
ALTER TABLE `orderreturn` DROP `returnitemimage`;
ALTER TABLE `orderreturnhistory` DROP `returnitemimage`;

  INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (6, NOW(), 'Shrikant Suryawanshi','Changes for return and tracking');
