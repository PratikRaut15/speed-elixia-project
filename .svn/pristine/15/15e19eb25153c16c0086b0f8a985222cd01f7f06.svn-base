ALTER TABLE unit CHANGE digitalio digitalio INT(4) NOT NULL DEFAULT '0';
ALTER TABLE temp_compliance CHANGE min_1 min_1 INT( 4 ) NOT NULL DEFAULT '0',
CHANGE max_1 max_1 INT( 4 ) NOT NULL DEFAULT '0',
CHANGE min_2 min_2 INT( 4 ) NOT NULL DEFAULT '0',
CHANGE max_2 max_2 INT( 4 ) NOT NULL DEFAULT '0',
CHANGE min_3 min_3 INT( 4 ) NOT NULL DEFAULT '0',
CHANGE max_3 max_3 INT( 4 ) NOT NULL DEFAULT '0',
CHANGE min_4 min_4 INT( 4 ) NOT NULL DEFAULT '0',
CHANGE max_4 max_4 INT( 4 ) NOT NULL DEFAULT '0';


update unit SET extra_digital = 1 where extra_digital > 0 and customerno = 59; 

update unit SET extra_digital = 2 where unitno in(904715
,904419
,905088
,904842
,904834
,903110
,904417
,905087
,900314
,904448
,904698
,903111
,904837
,900952
,900651
,904701
,904703
,904718
,904418
,900961
,904836
,904704
,904835
,900011
,900816
,900153
,904709
,900312
,905089
,900953
) and customerno = 59;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 364, NOW(), 'Shrikant Suryawanshi','Update single and double genset extra_digital');
