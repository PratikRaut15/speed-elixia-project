-- Insert SQL here.

 CREATE  TABLE  `vehiclerouteman` (  `vrmanid` int( 11  )  NOT  NULL  AUTO_INCREMENT ,
 `routeid` int( 11  )  NOT  NULL ,
 `vehicleid` int( 11  )  NOT  NULL ,
 `customerno` int( 11  )  NOT  NULL ,
 `userid` int( 11  )  NOT  NULL ,
 `isdeleted` int( 1  )  NOT  NULL ,
 `timestamp` datetime NOT  NULL ,
 PRIMARY  KEY (  `vrmanid`  )  ) ENGINE  =  MyISAM  DEFAULT CHARSET  = latin1;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 14, NOW(), 'Ajay Tripathi','Vehicle Route Manager auto increment');
