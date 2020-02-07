/**
# Author: Ranjeet Kasture
# Date created: 03-05-2019
# Date pushed to UAT: 22-04-2019
# Description:
# Queries for enhancement of Kutwal foods for consignee/distributors logins
# 
#
***/

/* Create dbpatch */
    INSERT INTO `dbpatches` ( 
    `patchid`,
    `patchdate
    `, 
    `appliedby`, 
    `patchdesc`,
    `isapplied`
    ) 
    VALUES
    ( 
    '705', '2019-05-03 17:00:00', 
    'Ranjeet K','Queries for enhancement of Kutwal foods for consignee/distributors logins','0');

/* New table created as 'user_consignee_mapping' */
    CREATE TABLE `user_consignee_mapping` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` int(11) NOT NULL COMMENT 'Reference to userid from user table',
        `consigneeid` int(11) NOT NULL COMMENT 'Reference to consid  from tripconsignee table',
        `roleid` int(11) NOT NULL COMMENT 'Reference to id from role table',
        `createdBy` int(11) NOT NULL COMMENT 'Reference to userid from user table',
        `createdOn` datetime NOT NULL,
        `updatedBy` int(11) NOT NULL COMMENT 'Reference to userid from user table',
        `updatedOn` datetime NOT NULL,
        `isdeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-record is not deleted, 1- record is deleted',
        PRIMARY KEY (`id`),
        KEY `userid` (`userid`,`consigneeid`,`roleid`,`createdBy`,`isdeleted`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Created new entry in role table for role "Consignee". Query for this is as follows */
    INSERT INTO `role` (`role`, `isdeleted`) VALUES ('Consignee', '0');

/* Inserting kutwal consignee(distributor) data into tripconsignee table. insert data query as follows */
    INSERT INTO `tripconsignee` (`consigneename`, `checkpointid`, `customerno`, `addedby`, `isdeleted`) VALUES 
        ('Sai Urja Shopee - Shikrapur', '0', '795', '0', '0'),
        ('Suryakiran Coldrinks and Snacks Center - Pimple Jag', '0', '795', '0', '0'),
        ('Shivani Food Mall', '0', '795', '0', '0'),
        ('Mayur  Super  Market -Kendur', '0', '795', '0', '0'),
        ('Vaibhav Hotel - Chinchoshi', '0', '795', '0', '0'),
        ('SANTOSH  KIRANA - BAHUL', '0', '795', '0', '0'),
        ('Gauri Agency -  Chakan', '0', '795', '0', '0'),
        ('Hari Om Sweet Chakan', '0', '795', '0', '0'),
        ('Pradip Icecream Parler - Chakan', '0', '795', '0', '0'),
        ('Avneesh  Shopee-   Khed', '0', '795', '0', '0'),
        ('Mahalunge- Ganesh Milk Suppliers', '0', '795', '0', '0'),
        ('Hotel Om Sai - Chakan', '0', '795', '0', '0'),
        ('Pooja Milk Supplier - Indori', '0', '795', '0', '0'),
        ('Samarth Enterprises - Talegaon', '0', '795', '0', '0'),
        ('KETAN  DAIRY-KARLE', '0', '795', '0', '0'),
        ('Laxmi Chikki -  Lonawala', '0', '795', '0', '0'),
        ('Parijat Shopee- Lonavala', '0', '795', '0', '0'),
        ('Moraya General Stores - Lonawala', '0', '795', '0', '0'),
        ('Asha Enterprises - Khandala', '0', '795', '0', '0'),
        ('Akanksha  Dairy- Khopoli', '0', '795', '0', '0'),
        ('Yash Agency - Khopoli', '0', '795', '0', '0'),
        ('A-One  Kirana - Shilphata (Khopoli )', '0', '795', '0', '0'),
        ('Balasaheb  Patil -  Vahoshi', '0', '795', '0', '0'),
        ('Om Sai Urja Shoppee-Pen', '0', '795', '0', '0'),
        ('Yash Agency - Alibag (Shoppe)', '0', '795', '0', '0'),
        ('Anand Dairy', '0', '795', '0', '0'),
        ('Yash Agency - Pen (Distribution)', '0', '795', '0', '0'),
        ('Pande  Dairy -  Shiv ( IPCL)', '0', '795', '0', '0'),
        ('JOGESHWARI DUDH DAIRY ( NAGOTHANE )', '0', '795', '0', '0'),
        ('Choudhari Agencies - Pali', '0', '795', '0', '0'),
        ('Katkar Agency - Sabalewadi', '0', '795', '0', '0'),
        ('Gurumauli Enterprises', '0', '795', '0', '0'),
        ('Cool - Navi Mumbai', '0', '795', '0', '0'),
        ('Swami Enterprises - Mumbai', '0', '795', '0', '0'),
        ('Heramb Enterprises - Girgaon', '0', '795', '0', '0'),
        ('Kamdhenu Dairy Farm-Mumbai', '0', '795', '0', '0'),
        ('Milky Way - Kandivali', '0', '795', '0', '0'),
        ('Sagar Store- Mumbai', '0', '795', '0', '0'),
        ('Excel Enterprises -Thane', '0', '795', '0', '0');	

/* Create users (distributors) for Kutwal foods */
    insert into user (customerno,realname,username,role,roleid,email,phone) VALUES 
        ('795', 'Mr. Tirupatirao Chalsani', 'thirupathichalsani@gmail.com', 'Consignee', '56', 'thirupathichalsani@gmail.com', '9822334133'),
        ('795', 'Mr. Bhagwan Shelake', 'bhagwanshelake202@gmail.com', 'Consignee', '56', 'bhagwanshelake202@gmail.com', '9075746151'),
        ('795', 'Mr. Prashant Daundkar', 'shivkrupa.com@gmail.com', 'Consignee', '56', 'shivkrupa.com@gmail.com', '9767215504'),
        ('795', 'Mr. Rajaram Raskar', 'mayursuper19@gmail.com', 'Consignee', '56', 'mayursuper19@gmail.com', '9284874080'),
        ('795', 'Mr. Sahebrao Bhoskar', 'vaibhavhotel19@gmail.com', 'Consignee', '56', 'vaibhavhotel19@gmail.com', '9049103362'),
        ('795', 'Mr. Parshuram Arekar', 'santoshkirana19@gmail.com', 'Consignee', '56', 'santoshkirana19@gmail.com', '9271198328'),
        ('795', 'Ms. Shubhangi Gaikwad', 'gauriagency19@gmail.com', 'Consignee', '56', 'gauriagency19@gmail.com', '9860576610'),
        ('795', 'Mr. Mangilal Choudhari', 'hariom19@gmail.com', 'Consignee', '56', 'hariom19@gmail.com', '9850035802'),
        ('795', 'Mrs. Suvarna Alhat', 'pradip.alhat1@gmail.com', 'Consignee', '56', 'pradip.alhat1@gmail.com', '9881070844'),
        ('795', 'Mr. Rahul Rakshe', 'rahulrakshe1985@gmail.com', 'Consignee', '56', 'rahulrakshe1985@gmail.com', '9765713331'),
        ('795', 'Mr. Keshav Walke', 'ganeshmilk19@gmail.com', 'Consignee', '56', 'ganeshmilk19@gmail.com', '9657658890'),
        ('795', 'Mr. Sandip Hase', 'sandeephase060@gmail.com', 'Consignee', '56', 'sandeephase060@gmail.com', '9767787841'),
        ('795', 'Mr. Mandar Kashid', 'mandarkashid507@gmail.com', 'Consignee', '56', 'mandarkashid507@gmail.com', '8625862570'),
        ('795', 'Mr. Pravin Phakatkar', 'pravinphakatkar32@gmail.com', 'Consignee', '56', 'pravinphakatkar32@gmail.com', '8796302276'),
        ('795', 'Mr. Sudam Hulawale', 'ketanh44@gmail.com', 'Consignee', '56', 'ketanh44@gmail.com', '9604422335'),
        ('795', 'Mr. Rajendra Agarwal', 'rajuagarwal31@gmail.com', 'Consignee', '56', 'rajuagarwal31@gmail.com', '9763166767'),
        ('795', 'Mr. Nitin Sonawane', 'nmsonawane005@gmail.com', 'Consignee', '56', 'nmsonawane005@gmail.com', '9689935925'),
        ('795', 'Mr. Vinit Dhore', 'dhorepritingpress@gmail.com ', 'Consignee', '56', 'dhorepritingpress@gmail.com ', '7875658225'),
        ('795', 'Mr. Ritesh Dalvi', 'ashaent19@gmail.com', 'Consignee', '56', 'ashaent19@gmail.com', '9552998625'),
        ('795', 'Mr. Jaganath Mete', 'akankshadairy19@gmail.com', 'Consignee', '56', 'akankshadairy19@gmail.com', '9822629885'),
        ('795', 'Ms. Smruti Joshi', 'smrutijoshi2701@gmail.com', 'Consignee', '56', 'smrutijoshi2701@gmail.com', '9075016121'),
        ('795', 'Mr. Suesh Rajpurohit', 'dineshraj8625990200@gmail.com', 'Consignee', '56', 'dineshraj8625990200@gmail.com', '8087402288'),
        ('795', 'Mr. Balasaheb Patil', 'balasaheb19@gmail.com', 'Consignee', '56', 'balasaheb19@gmail.com', '8928807999'),
        ('795', 'Mr. Swapnil Manore', 'omsaiurja19@gmail.com', 'Consignee', '56', 'omsaiurja19@gmail.com', '9372771812'),
        ('795', 'Ms. Smruti Joshi', 'smrutijoshi2701@gmail.com', 'Consignee', '56', 'smrutijoshi2701@gmail.com', '9075016121'),
        ('795', 'Mrs. Ankita Patil', 'ananddairy19@gmail.com', 'Consignee', '56', 'ananddairy19@gmail.com', '9527448817'),
        ('795', 'Ms. Smruti Joshi', 'smrutijoshi2701@gmail.com', 'Consignee', '56', 'smrutijoshi2701@gmail.com', '9075016121'),
        ('795', 'Mr. Madhav Pandy', 'pandedairy19@gmail.com', 'Consignee', '56', 'pandedairy19@gmail.com', '9561105983'),
        ('795', 'Mr. Ravi Deshmukh', 'jogeshwari19@gmail.com', 'Consignee', '56', 'jogeshwari19@gmail.com', '9637373544'),
        ('795', 'Mr. Vikrant Choudhary', 'choudhariage19@gmail.com', 'Consignee', '56', 'choudhariage19@gmail.com', '8237153665'),
        ('795', 'Mr. Bhagwan Katkar', 'vitthalkatkar2805@gmail.com', 'Consignee', '56', 'vitthalkatkar2805@gmail.com', '9689161397'),
        ('795', 'Mr. Swapnil Kapare', 'swapnil4world@gmail.com', 'Consignee', '56', 'swapnil4world@gmail.com', '9420750211'),
        ('795', 'Mr. Kiran Poojari', 'keeranpoojary@yahoo.co.in', 'Consignee', '56', 'keeranpoojary@yahoo.co.in', '8828822001'),
        ('795', 'Ms. Suvarna Pawar', 'sudesh.pawar45@gmail.com', 'Consignee', '56', 'sudesh.pawar45@gmail.com', '9821364171'),
        ('795', 'Mrs. Suhsma Berde', 'deepak00450@gmail.com', 'Consignee', '56', 'deepak00450@gmail.com', '9819000450'),
        ('795', 'Mr. Santosh Jadhav', 'santojadhav2779@gmail.com', 'Consignee', '56', 'santojadhav2779@gmail.com', '9870547193'),
        ('795', 'Mr. Satish Dhotre', 'dhotresatish@yahoo.com', 'Consignee', '56', 'dhotresatish@yahoo.com', '9594409856'),
        ('795', 'Mr. Sagar Shukla', 'sagarshukla123472@gmail.com', 'Consignee', '56', 'sagarshukla123472@gmail.com', '9619698492'),
        ('795', 'Mr. Prashant Shinkar', 'excel.enterprises79@gmail.com', 'Consignee', '56', 'excel.enterprises79@gmail.com', '9820941379');

/* After inserting users into user table via excel sheet execute follwing command to update user's password and userkey */    
    update `user` set `password`=SHA1(phone), userkey=FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000 where customerno=795 AND `role` = 'Consignee' AND roleid=56;	    

/* inserting data into user_consignee_mapping table */
    insert into user_consignee_mapping (userid,consigneeid,roleid,createdBy,createdOn,updatedBy,updatedOn,isdeleted) VALUES 
    ("9357","536","56","0 ","0000-00-00 00:00:00 ","0 ","0000-00-00 00:00:00 ","0"),
    ("9358","537","56","0 ","0000-00-00 00:00:01 ","0 ","0000-00-00 00:00:01 ","0"),
    ("9359","538","56","0 ","0000-00-00 00:00:02 ","0 ","0000-00-00 00:00:02 ","0"),
    ("9360","539","56","0 ","0000-00-00 00:00:03 ","0 ","0000-00-00 00:00:03 ","0"),
    ("9361","540","56","0 ","0000-00-00 00:00:04 ","0 ","0000-00-00 00:00:04 ","0"),
    ("9362","541","56","0 ","0000-00-00 00:00:05 ","0 ","0000-00-00 00:00:05 ","0"),
    ("9363","542","56","0 ","0000-00-00 00:00:06 ","0 ","0000-00-00 00:00:06 ","0"),
    ("9364","543","56","0 ","0000-00-00 00:00:07 ","0 ","0000-00-00 00:00:07 ","0"),
    ("9365","544","56","0 ","0000-00-00 00:00:08 ","0 ","0000-00-00 00:00:08 ","0"),
    ("9366","545","56","0 ","0000-00-00 00:00:09 ","0 ","0000-00-00 00:00:09 ","0"),
    ("9367","546","56","0 ","0000-00-00 00:00:10 ","0 ","0000-00-00 00:00:10 ","0"),
    ("9368","547","56","0 ","0000-00-00 00:00:11 ","0 ","0000-00-00 00:00:11 ","0"),
    ("9369","548","56","0 ","0000-00-00 00:00:12 ","0 ","0000-00-00 00:00:12 ","0"),
    ("9370","549","56","0 ","0000-00-00 00:00:13 ","0 ","0000-00-00 00:00:13 ","0"),
    ("9371","550","56","0 ","0000-00-00 00:00:14 ","0 ","0000-00-00 00:00:14 ","0"),
    ("9372","551","56","0 ","0000-00-00 00:00:15 ","0 ","0000-00-00 00:00:15 ","0"),
    ("9373","552","56","0 ","0000-00-00 00:00:16 ","0 ","0000-00-00 00:00:16 ","0"),
    ("9374","553","56","0 ","0000-00-00 00:00:17 ","0 ","0000-00-00 00:00:17 ","0"),
    ("9375","554","56","0 ","0000-00-00 00:00:18 ","0 ","0000-00-00 00:00:18 ","0"),
    ("9376","555","56","0 ","0000-00-00 00:00:19 ","0 ","0000-00-00 00:00:19 ","0"),
    ("9377","556","56","0 ","0000-00-00 00:00:20 ","0 ","0000-00-00 00:00:20 ","0"),
    ("9378","557","56","0 ","0000-00-00 00:00:21 ","0 ","0000-00-00 00:00:21 ","0"),
    ("9379","558","56","0 ","0000-00-00 00:00:22 ","0 ","0000-00-00 00:00:22 ","0"),
    ("9380","559","56","0 ","0000-00-00 00:00:23 ","0 ","0000-00-00 00:00:23 ","0"),
    ("9381","560","56","0 ","0000-00-00 00:00:24 ","0 ","0000-00-00 00:00:24 ","0"),
    ("9382","561","56","0 ","0000-00-00 00:00:25 ","0 ","0000-00-00 00:00:25 ","0"),
    ("9383","562","56","0 ","0000-00-00 00:00:26 ","0 ","0000-00-00 00:00:26 ","0"),
    ("9384","563","56","0 ","0000-00-00 00:00:27 ","0 ","0000-00-00 00:00:27 ","0"),
    ("9385","564","56","0 ","0000-00-00 00:00:28 ","0 ","0000-00-00 00:00:28 ","0"),
    ("9386","565","56","0 ","0000-00-00 00:00:29 ","0 ","0000-00-00 00:00:29 ","0"),
    ("9387","566","56","0 ","0000-00-00 00:00:30 ","0 ","0000-00-00 00:00:30 ","0"),
    ("9388","567","56","0 ","0000-00-00 00:00:31 ","0 ","0000-00-00 00:00:31 ","0"),
    ("9389","568","56","0 ","0000-00-00 00:00:32 ","0 ","0000-00-00 00:00:32 ","0"),
    ("9390","569","56","0 ","0000-00-00 00:00:33 ","0 ","0000-00-00 00:00:33 ","0"),
    ("9391","570","56","0 ","0000-00-00 00:00:34 ","0 ","0000-00-00 00:00:34 ","0"),
    ("9392","571","56","0 ","0000-00-00 00:00:35 ","0 ","0000-00-00 00:00:35 ","0"),
    ("9393","572","56","0 ","0000-00-00 00:00:36 ","0 ","0000-00-00 00:00:36 ","0"),
    ("9394","573","56","0 ","0000-00-00 00:00:37 ","0 ","0000-00-00 00:00:37 ","0"),
    ("9395","574","56","0 ","0000-00-00 00:00:38 ","0 ","0000-00-00 00:00:38 ","0");

/* Updating dbpatche 705 */
    UPDATE  dbpatches
    SET     patchdate = '2019-05-03 17:00:00'
            ,isapplied =1
    WHERE   patchid = 705;

