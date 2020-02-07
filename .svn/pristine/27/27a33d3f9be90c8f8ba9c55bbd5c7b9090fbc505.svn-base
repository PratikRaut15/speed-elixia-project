INSERT INTO dbpatches (
patchid,
patchdate ,
appliedby ,
patchdesc ,
isapplied
)
VALUES (
425, NOW(), 'Shrikant Suryawanshi', 'Checkpoint Ownler Sms / Email Log', '0'
);


Create Table chkptOwnerLog (
    chkptLogId int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    smsId int,
    emailId int,
    checkpointId int,
    vehicleId int,
    routeId int,
    customerno int,
    created_on datetime
);

INSERT INTO `reportMaster`(`reportName`) VALUES
('Checkpoint Owner Sms/Email Log');


UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 425;
