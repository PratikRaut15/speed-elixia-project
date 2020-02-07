ALTER TABLE unit add n1 int(3) NOT NULL AFTER tempsen4;
ALTER TABLE unit add n2 int(3) NOT NULL AFTER n1;
ALTER TABLE unit add n3 int(3) NOT NULL AFTER n2;
ALTER TABLE unit add n4 int(3) NOT NULL AFTER n3;


create table nomens(
nid int(11) PRIMARY KEY AUTO_INCREMENT,
name varchar(50),
customerno int(11),
isdeleted int(1)
);

ALTER TABLE eventalerts add temp3 int(4) AFTER temp2;
ALTER TABLE eventalerts add temp4 int(4) AFTER temp3;

ALTER TABLE unit add temp3_intv datetime NOT NULL after temp2_intv;
ALTER TABLE unit add temp4_intv datetime NOT NULL after temp3_intv;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (285, NOW(), 'Shrikant','Fassos NomenClature for dashboard');
