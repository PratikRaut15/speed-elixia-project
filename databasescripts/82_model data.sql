-- Insert SQL here.

ALTER TABLE  `customer` ADD  `use_maintenance` TINYINT( 1 ) NOT NULL;

INSERT INTO `model` (`model_id`, `name`, `make_id`) VALUES
(1, 'Stile', 1),
(2, 'A7', 2),
(3, 'A8 L', 2),
(4, 'New A4', 2),
(5, 'New A6', 2),
(6, 'New R8', 2),
(7, 'Q3', 2),
(8, 'Q5', 2),
(9, 'Q7', 2),
(10, 'RS5', 2),
(11, 'RS7', 2),
(12, 'S6', 2),
(13, 'TT', 2),
(14, 'Continental', 3),
(15, 'Flying Spur', 3),
(16, 'Mulsanne', 3),
(17, '1 Series', 4),
(18, '3 Series', 4),
(19, '5 Series', 4),
(20, '6 Series', 4),
(21, '6 Series Gran Coupe', 4),
(22, '7 Series', 4),
(23, 'M3', 4),
(24, 'X1', 4),
(25, 'X3', 4),
(26, 'X5', 4),
(27, 'Veyron', 5),
(28, 'Beat', 6),
(29, 'Captiva', 6),
(30, 'Cruze', 6),
(31, 'Enjoy', 6),
(32, 'Sail', 6),
(33, 'Sail U VA', 6),
(34, 'Spark', 6),
(35, 'Tavera Neo 3', 6),
(36, 'GO', 7),
(37, '458 Italia', 8),
(38, 'California', 8),
(39, 'FF', 8),
(40, '2014 New Linea', 9),
(41, 'Grande Punto', 9),
(42, 'Force One', 10),
(43, 'Gurkha', 10),
(44, 'EcoSport', 11),
(45, 'Endeavour', 11),
(46, 'Fiesta', 11),
(47, 'Fiesta Classic', 11),
(48, 'Figo', 11),
(49, 'Ambassador', 12),
(50, 'Pajero Sport', 13),
(51, 'Accord', 14),
(52, 'Amaze', 14),
(53, 'Brio', 14),
(54, 'CRV', 14),
(55, 'New City', 14),
(56, 'Elantra', 15),
(57, 'Grand i10', 15),
(58, 'New EON', 15),
(59, 'New Sonata', 15),
(60, 'New i20', 15),
(61, 'Santa Fe', 15),
(62, 'Santro Xing', 15),
(63, 'Verna', 15),
(64, 'Xcent', 15),
(65, 'i10', 15),
(66, 'F Type', 16),
(67, 'XF', 16),
(68, 'XFR', 16),
(69, 'XJ', 16),
(70, 'XKR', 16),
(71, 'Aventador', 17),
(72, 'Gallardo', 17),
(73, 'Discovery 4', 18),
(74, 'Freelander 2', 18),
(75, 'Range Rover', 18),
(76, 'Range Rover Evoque', 18),
(77, 'Range Rover Sport', 18),
(78, 'New Bolero', 19),
(79, 'QUANTO', 19),
(80, 'Scorpio', 19),
(81, 'Thar', 19),
(82, 'Verito', 19),
(83, 'Verito Vibe', 19),
(84, 'XUV 500', 19),
(85, 'Xylo', 19),
(86, 'e2o', 20),
(87, 'A star', 21),
(88, 'Alto', 21),
(89, 'Alto 800', 21),
(90, 'Celerio', 21),
(91, 'Eeco', 21),
(92, 'Ertiga', 21),
(93, 'Grand Vitara', 21),
(94, 'Gypsy', 21),
(95, 'Kizashi', 21),
(96, 'New Swift', 21),
(97, 'New Swift Desire', 21),
(98, 'New Wagon R', 21),
(99, 'Omni', 21),
(100, 'Ritz', 21),
(101, 'SX4', 21),
(102, 'WagonR Stingray', 21);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 82, NOW(), 'Ajay Tripathi','model data and customer alter');