-- Insert SQL here.

delete from probity_master where pmid > 12 ;

INSERT INTO `probity_master` (`pmid`, `workkey`, `workkey_name`, `work_master`, `customerno`) VALUES
(13, '1186', 'A.G. Pawar Marg', 'static_13.sqlite', 21),
(14, '688', 'Ram Baugh Lane Matunga', 'static_14.sqlite', 21),
(15, '722', 'Kane Nagar School Road', 'static_15.sqlite', 21),
(16, '1188', 'Alster Road', 'static_22.sqlite', 21),
(17, '726', 'Hindu Colony Road No 2', 'static_16.sqlite', 21),
(18, '1183', 'Iqbal Dudhawala Marg', 'static_17.sqlite', 21),
(19, '1185', 'R.B.Chandorkar Marg', 'static_18.sqlite', 21),
(20, '728', 'Lilabai Kasbe Road', 'static_31.sqlite', 21),
(21, '732', 'Road no 1 Arora Cinema Matiunga Lane', 'static_19.sqlite', 21),
(22, '703', 'J K Basin Marg', 'static_20.sqlite', 21),
(23, '1028', 'Padgha - New Hall Road', 'static_23.sqlite', 15),
(24, '1030', 'Padgha - Masrani', 'static_24.sqlite', 15),
(25, '1029', 'Padgha - Hollow Pool', 'static_25.sqlite', 15),
(26, '1186', 'Padgha - A J Pawar Road', 'static_26.sqlite', 15),
(27, '1188', 'Padgha - Alster Road', 'static_27.sqlite', 15),
(28, '1185', 'Padgha - R.B Chandorker Marg', 'static_28.sqlite', 15),
(29, '730', 'Padgha - Dosti Acre', 'static_29.sqlite', 15),
(30, '694', 'Padgha - Jayshankar Yagnik', 'static_30.sqlite', 15),
(31, '1003', 'PJA Shrikaramchandji Swami Marg', 'static_21.sqlite', 21);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 214, NOW(), 'Shrikanth Suryawanshi','Probity Changes');
