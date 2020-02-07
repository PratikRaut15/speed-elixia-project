INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`)
VALUES (573, NOW(), 'Manasvi Thakur','Update unit table for n1, n2 for cust 473');


update  unit set n1=41, n2 =42 where unitno = '1827010020613' AND customerNo = '473';
update  unit set n1=41, n2 =42 where unitno = '1826010020532' AND customerNo = '473';

update  unit set n1=41, n2 =42 where unitno = '1827010020662' AND customerNo = '473';
update  unit set n1=41, n2 =42 where unitno = '1826010020508' AND customerNo = '473';



update  unit set n1=42, n2 =41 where unitno = '1826010020573' AND customerNo = '473';
update  unit set n1=41, n2 =42 where unitno = '1826010020565' AND customerNo = '473';

UPDATE dbpatches SET isapplied=1 WHERE patchid = 573;