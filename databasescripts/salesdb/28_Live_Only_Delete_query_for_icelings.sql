INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc)
VALUES ('28', '2019-04-08 10:55:00', 'Sanjeet Shukla', 'Live Only - Delete query for Icelings');

delete from shop where customerno = 193;

delete from secondary_order where customerno = 193;
delete from area where customerno = 193;
delete from secondary_order_productlist where customerno = 193;




