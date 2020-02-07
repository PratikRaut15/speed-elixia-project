/***************************************************************************************/
/*        wow express				       				       */ 	
/*                                                                                     */
/***************************************************************************************/
create table vendormapping(
mid int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
customerid int(11) NOT NULL,
vendorid int(11) NOT NULL, 
vendor_no int(11) NOT NULL
);


create table pickup_user(
pid int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
customerno int(11) NOT NULL,
name varchar(50) NOT NULL,
phone varchar(15) NOT NULL,
email varchar(50) NOT NULL,
isdeleted tinyint(1) NOT NULL

);

create table pinmapping(
mpid int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
csutomerno int(11) NOT NULL,
pid int(11) NOT NULL,
pincode int(11) NOT NULL,
isdeleted tinyint(1) NOT NULL
);


alter table vendormapping ADD isdeleted tinyint(1) NOT NULL AFTER vendor_no;

alter table pickup_vendor add vendorcompany varchar(100) NOT NULL AFTER vendorname;


