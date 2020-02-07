UPDATE task 
SET 
isdeleted=1
WHERE 
id IN(
62
,71
,262
,182
,151
,236
,206
,112
)AND
customerno=118;

UPDATE maintenance_tasks
SET 
partid= 98
WHERE partid=262;

UPDATE maintenance_tasks
SET 
partid= 123
WHERE partid IN(62,71);


UPDATE maintenance_tasks
SET 
partid= 105
WHERE partid=182;


UPDATE maintenance_tasks
SET 
partid= 160
WHERE partid=151;

UPDATE maintenance_tasks
SET 
partid=44
WHERE partid=236;

UPDATE maintenance_tasks
SET 
partid= 229
WHERE partid=206;

UPDATE maintenance_tasks
SET 
partid= 130
WHERE partid=112;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (302, NOW(), 'Sahil Gandhi','duplicate parts trigon');
