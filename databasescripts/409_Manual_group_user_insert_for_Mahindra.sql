-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'409', '2016-09-09 15:00:01', 'Mrudang Vora', 'Manual insert in group man for Mahindra Finanace', '0');
-- Insert SQL here.


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_groups_for_user`$$
CREATE PROCEDURE `insert_groups_for_user`(
	IN useridParam INT
    , IN districtidParam INT
    , IN cityidParam INT
    , IN groupidParam INT
    , IN custnoParam INT
    , IN todaysdate DATETIME
    , OUT isInserted TINYINT(1) 
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
		@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
		SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
		SELECT @full_error;
        */
        SET isInserted = 0;
	END;
    
	IF cityidParam = 0 OR cityidParam = '' THEN
		SET cityidParam = NULL;
    END IF;
    
	IF groupidParam = 0 OR groupidParam = '' THEN
		SET groupidParam = NULL;
    END IF;
    
	START TRANSACTION;
		UPDATE 	groupman
        SET 	isdeleted = 1
        WHERE 	userid = useridParam
        AND 	customerno = custnoParam;
        
		INSERT INTO groupman (groupid, userid, customerno, `timestamp`, vehicleid, isdeleted)
		SELECT  	g.groupid, useridParam, custnoParam, todaysdate, 0, 0
		FROM 		`group` AS g
		INNER JOIN 	city AS c on g.cityid = c.cityid
		INNER JOIN 	district AS d on c.districtid = d.districtid
		WHERE 		d.districtid = districtidParam
		AND 		(cityidParam IS NULL OR c.cityid = cityidParam)
        AND 		(groupidParam IS NULL OR g.groupid = groupidParam)
		AND			d.isdeleted = 0
        AND 		d.customerno = custnoParam
		AND			c.isdeleted = 0
		AND 		c.customerno = custnoParam
        AND			g.isdeleted = 0
		AND 		g.customerno = custnoParam;
        
		SET isInserted = 1;
	COMMIT;
END$$
DELIMITER ;

update groupman SET `timestamp` = '2016-09-09 15:30:00' where userid = 5799 and isdeleted = 0;
update groupman SET isdeleted = 0 where customerno = 64 and `timestamp` = '2016-09-09 15:30:00';


#Sougat
call insert_groups_for_user('5799', '10', 0,  0, '64', '2016-09-09 15:30:00', @isInserted);

#Ramakand
call insert_groups_for_user('5798', '9', 0,  0, '64', '2016-09-09 15:30:00', @isInserted);

#Taj 
call insert_groups_for_user('5805', '10', 36,  0, '64', '2016-09-09 15:30:00', @isInserted);

#Rakesh
call insert_groups_for_user('5802', '9', 27,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5802', '9', 28,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5802', '9', 25,  0, '64', '2016-09-09 15:30:00', @isInserted);

#Amit
call insert_groups_for_user('5803', '9', 29,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5803', '9', 26,  0, '64', '2016-09-09 15:30:00', @isInserted);

#Ashish
call insert_groups_for_user('5804', '10', 37,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5804', '10', 38,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5804', '10', 39,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5804', '10', 42,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5804', '10', 40,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5804', '10', 41,  0, '64', '2016-09-09 15:30:00', @isInserted);

#Kunal
call insert_groups_for_user('5800', '9', 30,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5800', '9', 32,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5800', '9', 34,  0, '64', '2016-09-09 15:30:00', @isInserted);

#KK Vaishnav
call insert_groups_for_user('5801', '9', 31,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5801', '9', 35,  0, '64', '2016-09-09 15:30:00', @isInserted);
call insert_groups_for_user('5801', '9', 33,  0, '64', '2016-09-09 15:30:00', @isInserted);

INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ALOK ROY','24003787',SHA1('24003787'),'Branch Manager',37,'ROY.ALOK@MAHINDRA.COM','9993200829',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'PREJIT BALAKRISHNAN','24000178',SHA1('24000178'),'Branch Manager',37,'BALAKRISHNAN.P@MAHINDRA.COM','9993200800',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ABHISHEK PARIHAR','24003833',SHA1('24003833'),'Branch Manager',37,'PARIHAR.ABHISHEK@mahindra.com','9993200760',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'HIMANSHU JOSHI','24003610',SHA1('24003610'),'Branch Manager',37,'JOSHI.HIMANSHU2@MAHINDRA.COM','9926002803',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ASHISH TIWARI','24004307',SHA1('24004307'),'Branch Manager',37,'TIWARI.ASHISH@MAHINDRA.COM','9993200772',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'PRATEEK PANDEY','23117298',SHA1('23117298'),'Branch Manager',37,'pandey.prateek@mahfin.com','8889651115',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'SANDEEP KUMAR MISHRA','24006577',SHA1('24006577'),'Branch Manager',37,'mishra.sandeep1@mahfin.com','9977582800',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'AJIT KUMAR SINGH','24004111',SHA1('24004111'),'Branch Manager',37,'SINGH.AJIT2@MAHINDRA.COM','9826185499',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'KESHAV HARDIHA','24003475',SHA1('24003475'),'Branch Manager',37,'HARDIHA.KESHAW@MAHINDRA.COM','9826179635',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'KAPIL PATEL','23137369',SHA1('23137369'),'Branch Manager',37,'patel.kapil@mahfin.com','7770900400',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'SHABU DAMODARAN','24000729',SHA1('24000729'),'Branch Manager',37,'DAMODARAN.SHABU@MAHINDRA.COM','9754983838',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'SANDIP KUMAR','24003677',SHA1('24003677'),'Branch Manager',37,'SHARMA.SANDEEPKUMAR@MAHINDRA.COM','9977244553',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'RIYAZUDDIN SHEIKH','24000569',SHA1('24000569'),'Branch Manager',37,'SHEIKH.RIYAZUDDIN@MAHINDRA.COM','9977021786',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'SANDEEP KUMAR YADAV','24003689',SHA1('24003689'),'Branch Manager',37,'YADAV.SANDEEP2@MAHINDRA.COM','9826900861',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ASHISH DUBEY','24001106',SHA1('24001106'),'Branch Manager',37,'DUBEY.ASHISH2@MAHINDRA.COM','9826996669',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ANURAG CHANDRA','23117300',SHA1('23117300'),'Branch Manager',37,'chandra.anurag@mahfin.com','7771833319',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'AMIT KUMAR DUBEY','24001105',SHA1('24001105'),'Branch Manager',37,'dubey.amit3@mahindra.com','9826126667',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5805);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MANSANG RABARI','24005146',SHA1('24005146'),'Branch Manager',37,'rabari.manseng@mahfin.com','8980005626',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MEHUL MALANI','24001379',SHA1('24001379'),'Branch Manager',37,'MALANI.MEHUL@MAHINDRA.COM','9825084790',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'PRADIPSINH HADA','23117530',SHA1('23117530'),'Branch Manager',37,'hada.pradipsinh@mahfin.com','9913364308',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'JAYESHKUMAR PARMAR','23161816',SHA1('23161816'),'Branch Manager',37,'parmar.jayeshkumar@mahfin.com','9974138884',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'NIMESH MAKWANA','23072688',SHA1('23072688'),'Branch Manager',37,'makwana.nimesh@mahfin.com','9978591221',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'KANJI PATEL','24004139',SHA1('24004139'),'Branch Manager',37,'PATEL.KANJI@MAHINDRA.COM','9825306632',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'DIVAKAR SAXENA','23113970',SHA1('23113970'),'Branch Manager',37,'SAXENA.DIVAKAR@mahindra.com','7567865586',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'HIMMATSINH ZALA','24006356',SHA1('24006356'),'Branch Manager',37,'ZALA.HIMMATSINH@mahindra.com','9726144999',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'KAUSHIK GHEDIA','23152550',SHA1('23152550'),'Branch Manager',37,'ghedia.kaushik@mahfin.com','9925175755',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MEHUL THAKKAR','24004281',SHA1('24004281'),'Branch Manager',37,'thakkar.mehul@mahindra.com','9879572603',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'AMIT DHANDHUKIYA','23069794',SHA1('23069794'),'Branch Manager',37,'dhandhukiya.amit@mahindra.com','9825668692',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5802);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ANAND SAVDHARIA','24003970',SHA1('24003970'),'Branch Manager',37,'SAVDHARIA.ANAND@MAHINDRA.COM','9825315356',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Rakesh Chavda','23156422',SHA1('23156422'),'Branch Manager',37,'chavda.rakesh@mahfin.com','9978761114',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Sunny Batliwala','24005187',SHA1('24005187'),'Branch Manager',37,'batliwala.sunny@mahfin.com','8980005644',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Hemant Nishawala','24001636',SHA1('24001636'),'Branch Manager',37,'NISHAWALA.HEMANT@MAHINDRA.COM','9825150177',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Malav Mehta','23072558',SHA1('23072558'),'Branch Manager',37,'mehta.malav@mahfin.com','9737676276',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Daxesh Panchal','24003347',SHA1('24003347'),'Branch Manager',37,'PANCHAL.DAKSHESH@MAHINDRA.COM','9913618414',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Kishore Tiwari','24002552',SHA1('24002552'),'Branch Manager',37,'TIWARI.KISHORE@MAHINDRA.COM','9909026909',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Pratik Sanghvi','24003502',SHA1('24003502'),'Branch Manager',37,'SANGHVI.PRATIK@MAHINDRA.COM','9099930403',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Bimalkumar Fulwala','24000650',SHA1('24000650'),'Branch Manager',37,'FULWALA.BIMAL@MAHINDRA.COM','9825192840',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Hardik Rathod','24004194',SHA1('24004194'),'Branch Manager',37,'RATHOD.HARDIK@MAHINDRA.COM','9879908855',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Jaypalsinh Mahida','24004029',SHA1('24004029'),'Branch Manager',37,'mahida.jaypal@mahfin.com','9925103104',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mrugesh Desai','24007508',SHA1('24007508'),'Branch Manager',37,'DESAI.MRUGESH@mahindra.com','9879552439',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. RAM PRATAP PARIHAR','24000482',SHA1('24000482'),'Branch Manager',37,'PARIHAR.RAMPRATAP@mahindra.com','9993200799',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. ABHINAV PANDEY','23117302',SHA1('23117302'),'Branch Manager',37,'pandey.abhinav@mahfin.com','9755578457',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'NITIN SHARMA','24003820',SHA1('24003820'),'Branch Manager',37,'SHARMA.NITIN@MAHINDRA.COM','9993200691',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. PUSHPRAJ LAL','24000871',SHA1('24000871'),'Branch Manager',37,'LAL.PUSPRAJ@mahindra.com','9981541122',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. ANIL PATHAK','24000174',SHA1('24000174'),'Branch Manager',37,'PATHAK.ANIL@MAHINDRA.COM','9993200700',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. GHANSHYAM SHARMA','24000185',SHA1('24000185'),'Branch Manager',37,'sharma.ghanshyam@mahindra.com','9993200999',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Dharmendra Raghuwanshi ','24000767',SHA1('24000767'),'Branch Manager',37,'RAGHNWANSHI.DHARMENDRA@MAHINDRA.COM','9981511829',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'JITENDRA MISHRA','24003452',SHA1('24003452'),'Branch Manager',37,'MISHRA.JITENDRA@MAHINDRA.COM','9993200703',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Tanvir Rathore','24001232',SHA1('24001232'),'Branch Manager',37,'RATHORE.TANVIR@MAHINDRA.COM','9893002751',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. RUPENDRA PARMAR','24004387',SHA1('24004387'),'Branch Manager',37,'PARMAR.RUPENDRA@mahindra.com','9993200692',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. RUPENDRA SINGH KUSHWAH','24004338',SHA1('24004338'),'Branch Manager',37,'KUSHWAHA.RUPENDRA@MAHINDRA.COM','9993200714',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Dinesh Rangari','24004259',SHA1('24004259'),'Branch Manager',37,'RANGARI.DINESH@MAHINDRA.COM','9993200875',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Rambaran Diwedi','24001880',SHA1('24001880'),'Branch Manager',37,'DWIVEDI.RAMBARAN@MAHINDRA.COM','9993200812',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. NAVEEN SHARMA','24001178',SHA1('24001178'),'Branch Manager',37,'SHARMA.NAVEEN3@MAHINDRA.COM','9981501683',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. AJAY SINGH','23121310',SHA1('23121310'),'Branch Manager',37,'SINGH.AJAY12@mahindra.com','9993200850',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. SANJAY KUMAR GUPTA','24003897',SHA1('24003897'),'Branch Manager',37,'gupta.sanjay2@mahindra.com','9993200633',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. RAKESH ROY','24003728',SHA1('24003728'),'Branch Manager',37,'ROY.RAKESH@mahindra.com','9993200637',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Himanshu Dangi','24003392',SHA1('24003392'),'Branch Manager',37,'DANGI.HIMANSHU@MAHINDRA.COM','9752042426',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Vinod Jain','24000176',SHA1('24000176'),'Branch Manager',37,'VINOD.JAIN2@MAHINDRA.COM','9993200679',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Susheel Sahu','24000425',SHA1('24000425'),'Branch Manager',37,'SAHU.SUSHEEL@MAHINDRA.COM','9993200778',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Shankraj Singh','24000029',SHA1('24000029'),'Branch Manager',37,'SINGH.SHANK@MAHINDRA.COM','9993200830',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Pramod Sharma','24004261',SHA1('24004261'),'Branch Manager',37,'SHARMA.PRAMOD3@MAHINDRA.COM','9755044645',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Arunendra Pratap Singh','23074567',SHA1('23074567'),'Branch Manager',37,'singh.arunendra@mahfin.com','9993200180',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Manohar Sabhnani','24003313',SHA1('24003313'),'Branch Manager',37,'SABHNANI.MANOHAR@MAHINDRA.COM','7869911586',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Harshit Kanungo','23103603',SHA1('23103603'),'Branch Manager',37,'kanungo.harshit@mahfin.com','9826200825',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Deepak Gautam','24001167',SHA1('24001167'),'Branch Manager',37,'GAUTAM.DEEPAK@MAHINDRA.COM','9981540882',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr.  Deepak Jain','24001462',SHA1('24001462'),'Branch Manager',37,'JAIN.DEEPAK2@MAHINDRA.COM','9300072888',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ANIL NIGAM','24000472',SHA1('24000472'),'Branch Manager',37,'nigam.anil@mahindra.com','9993200819',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. MANISH RAGHUWANSHI','24001439',SHA1('24001439'),'Branch Manager',37,'RAGHUWANSI.MANISH@mahindra.com','9981512154',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. SUNIL DUBEY','24000470',SHA1('24000470'),'Branch Manager',37,'DUBEY.SUNIL@MAHINDRA.COM','9981168307',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Rajesh Sharma','24003453',SHA1('24003453'),'Branch Manager',37,'SHARMA.RAJESH5@MAHINDRA.COM','9993200671',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Amulya das','24002018',SHA1('24002018'),'Branch Manager',37,'DAS.AMULYA@mahindra.com','9425105112',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'NRIPENDRA SINGH BAGHEL','24003543',SHA1('24003543'),'Branch Manager',37,'SINGH.NRIPENDRA@MAHINDRA.COM','9993200828',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. AMIT GUPTA','24004282',SHA1('24004282'),'Branch Manager',37,'GUPTA.AMIT4@MAHINDRA.COM','9993200845',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'VIPIN TIWARI','24003541',SHA1('24003541'),'Branch Manager',37,'TIWARI.VIPIN@MAHINDRA.COM','9993200756',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Krishna Nigam','24002791',SHA1('24002791'),'Branch Manager',37,'NIGAM.KRISHNA@MAHINDRA.COM','9826828017',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. GANDHARV SINGH RANA','24001951',SHA1('24001951'),'Branch Manager',37,'RANA.GANDHARV@MAHINDRA.COM','9752035600',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MUKESH SINGH BAGHEL','24001649',SHA1('24001649'),'Branch Manager',37,'BAGHEL.MUKESHSINGH@MAHINDRA.COM','9827248233',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Shayam  Lal Chaudhary','24002570',SHA1('24002570'),'Branch Manager',37,'CHOUDHARY.SHYAMLAL@MAHINDRA.COM','9179048999',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. YADUNATH SINGH TOMAR','24002170',SHA1('24002170'),'Branch Manager',37,'SINGH.YUDUNATH@MAHINDRA.COM','9993012554',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. GURVEER GILL','24004073',SHA1('24004073'),'Branch Manager',37,'GILL.GURVEER@MAHINDRA.COM','9993200717',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Sachin Patidar','24000832',SHA1('24000832'),'Branch Manager',37,'PATIDAR.SACHIN@MAHINDRA.COM','9755500744',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'NARAYAN GAHARWAR','24001168',SHA1('24001168'),'Branch Manager',37,'GAHARWAR.NARAYAN@MAHINDRA.COM','9993200822',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ARUN KUMAR JAIN','24000824',SHA1('24000824'),'Branch Manager',37,'JAIN.ARUN2@MAHINDRA.COM','9893222012',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Uday Gupta','24002376',SHA1('24002376'),'Branch Manager',37,'GUPTE.UDAY@MAHINDRA.COM','9993200880',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MR. NIKUL BHATEWARA','24003152',SHA1('24003152'),'Branch Manager',37,'BHATEWARA.NIKUL@MAHINDRA.COM','9755015267',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'NARESH BHARDWAJ','24001650',SHA1('24001650'),'Branch Manager',37,'BHARDWAJ.NARESH@MAHINDRA.COM','9977666886',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5803);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ASHISH JAIN','24004150',SHA1('24004150'),'Branch Manager',37,'JAIN.ASHISH@MAHINDRA.COM','9785703000',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5800);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'KAMAL KUMAR','24002973',SHA1('24002973'),'Branch Manager',37,'KUMAR.KAMAL@MAHINDRA.COM','9928790083',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5800);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ANIL KUMAR SAINI','24005294',SHA1('24005294'),'Branch Manager',37,'saini.anil2@mahindra.com','9983348505',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5800);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'VISHWANATH BISHT','24000635',SHA1('24000635'),'Branch Manager',37,'BISHT.VISHWANATH@MAHINDRA.COM','8058790124',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5800);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'DEVESH BAID','24007505',SHA1('24007505'),'Branch Manager',37,'BAID.DEVESH@mahindra.com','9928577424',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5800);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'MUKESH KAUSHIK','24003738',SHA1('24003738'),'Branch Manager',37,'KAUSHIK.MUKESH@MAHINDRA.COM','7665015011',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5800);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'VISHAL PARIKH','23069811',SHA1('23069811'),'Branch Manager',37,'parikh.vishal@mahfin.com','9928361390',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5801);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'SUNIL NANDWANA','23081124',SHA1('23081124'),'Branch Manager',37,'nandwana.sunil@mahindra.com','9636448888',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5801);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ASHOK VISHNOI','23152749',SHA1('23152749'),'Branch Manager',37,'vishnoi.ashok@mahfin.com','9983509090',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5801);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'ANAND HARSHA','24004151',SHA1('24004151'),'Branch Manager',37,'harsha.anand@mahindra.com','9829518021',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5801);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'GANESH RAMAWAT','23067177',SHA1('23067177'),'Branch Manager',37,'ramawat.ganesha@mahindra.com','9667513454',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5801);
INSERT INTO user(customerno, realname, username, `password`, role, roleid, email, phone, userkey, heirarchy_id) VALUES (64, 'Mr. Jitendra Sharma','24000797',SHA1('24000797'),'Branch Manager',37,'SHARMA.JITENDRA5@MAHINDRA.COM','9981998141',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 5804);



-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 409;
