INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'392' '2016-06-16 11:10:45', 'Mrudang Vora', 'Mahindra - base location script', '0'
);


ALTER TABLE `devices` ADD `baselat` DECIMAL(11,8) NOT NULL AFTER `devicelong`, ADD `baselng` DECIMAL(11,8) NOT NULL AFTER `baselat`;
ALTER TABLE `devices` ADD `installlat` DECIMAL(11,8) NOT NULL AFTER `baselng`, ADD `installlng` DECIMAL(11,8) NOT NULL AFTER `installlat`;

DROP TABLE areamaster;
-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 14, 2016 at 02:18 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `speed_old`
--

-- --------------------------------------------------------

--
-- Table structure for table `areamaster`
--

CREATE TABLE `areamaster` (
  `areaid` int(11) NOT NULL,
  `vehid` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `lat` decimal(11,8) NOT NULL,
  `lng` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `areamaster`
--

INSERT INTO `areamaster` (`areaid`, `vehid`, `address`, `lat`, `lng`) VALUES
(1, 2577, 'Shop No. 5 & 6, Harihareshwar Complex, Near S.T. Stand. ,Amboli Road,Sawantwadi,416510\n', '15.91125030', '73.82491910'),
(2, 902, 'Monalisa Complex,Near Mseb Officcce,Opp.Drkonkane Hospital,At/post - Nagothane,402106', '18.54461610', '73.13643570'),
(3, 903, 'Shop.No.14,15,16,Ground floor,''B''Wing green valley,Opp D''mart shopping mall,behind hotel western Inn bhabola,Padi road vasai West - 401207', '19.34679600', '72.81905200'),
(4, 1120, '1St Floor,Sai Plaza , Above Vijay Sales,Kapurbawdi, Ghodbunder Road ,Thane -400607', '19.22185290', '72.97710720'),
(5, 2488, 'Shop No. 6, Ground Floor, Pranav Plaza, Near Shivaji Nagar S.T. Stand.,Mumbai-Goa Highway,Chiplun 415605', '17.52651580', '73.52548990'),
(6, 2579, '726/A, 1st Floor, Nildeep Complex, Above Apana Bazar, Aroyga Mandir, Shivajinagar.,Ratnagiri,415639', '16.99021500', '73.31202330'),
(7, 2664, 'Ratnaprabha Building,2nd Floor, Adalat Road, Behind IDBI Bank,At Post Taluka,District Aurangabad -431001', '19.89108260', '75.15453810'),
(8, 2570, '1st Floor, 243 E Ward, Opp. D. Y. Patil High School, Above Govt. Servant Bank.,RTO Office Road,kolhapur,416003', '16.71369050', '74.23260580'),
(9, 2727, '1st Floor, Shubham Plaza, Ram Mandir Corner.,Sangli,416416', '16.85239730', '74.58147730'),
(10, 2574, 'Uttekar Arcade, 1st Floor, Near Mavashe Petrol Pump.,Pune-Banglore Road,Satara,415003', '17.70013970', '74.04763780'),
(11, 2627, '2nd Floor, Mittal Towers, Mauje Savedi, T. P. Scheme No. 4, Final Plot No. 107.,Manmad Road,Ahmednagar,414001', '19.09644920', '74.73955910'),
(12, 2455, 'MISEM Building, 5th Floor, 19/2, Plot No. 15, Near Sharada Center.,Off. Karve Road,Pune 411004', '18.51093090', '73.83242400'),
(13, 2616, 'Shubh Mangal Plaza, 1st Floor, ''B'' Wing, Shop No. 11 to 14, Near Kohinoor Centre.,Chakan,410501', '18.76026640', '73.86303460'),
(14, 2631, 'Hotel Nikhil, Maharashtra State Highway 27, Shikrapur, Maharashtra 412208', '18.69396800', '74.13802950'),
(15, 2619, 'MISEM Building, 5th Floor, 19/2, Plot No. 15, Near Sharada Center.,Off. Karve Road,Pune 411004', '18.51093090', '73.83242400'),
(16, 2729, 'Shop No.101,102 & 201,202, Snehganga, Near Income Tax Office, Swargate, Pune.,Shankarsheth Road,Pune 411037', '18.49988270', '73.86393930'),
(17, 2494, 'Narang Tower, 1st floor, Opp Traffic Police, Civil Line, Nagpur 440001 India.', '21.15839170', '79.06733020'),
(18, 2673, 'CTS No. 4075, A/6, Hingmire Complex.,Pandharpur,413304', '17.67455350', '75.32372620'),
(19, 2670, '1st Floor, Sindgi Icon, Old employment Chowk, Opp. Hotel Kamat.,Railway Lines,Solapur,413001', '17.67304440', '75.90712720'),
(20, 2632, '1st Floor, Talwar Heights, City Sr. No. 2027, Opp. Zade Hospital, Near Sadhana Hotel.,Belapur Road,Shrirampur 413709', '19.61913780', '74.65977930'),
(21, 2623, 'Shop No. 7 - 8, 1St Floor, Viraj Plaza,Satana Road,Malegaon,423203', '20.55474970', '74.51002910'),
(22, 2622, 'S-7 Tos-11,2nd  Flr, Suyojit City Centre,Mumbai Naka,Near Shatabdi Hospital,Nasik,422011', '19.99438550', '73.80955740'),
(23, 2625, 'Shop No. 2,3,4, Suyojit Complex,,Mumbai Agra Road,Nasik 422011', '19.98979300', '73.78293870'),
(24, 2629, 'Radhai Sankul, A-Wing, Ground Floor,Market Road,Lasalgaon 422306', '20.14914220', '74.23260580'),
(25, 899, '4th Floor, Mahindra Towers, Dr. G.M. Bhosale Marg, P.K. Kurne Chowk, Worli, Mumbai, Maharashtra 400018', '18.99851450', '72.81735450'),
(26, 2620, '1st Floor, Royal Bajaj , Near Gurudwara Malegaon Road and Hotel  walchand Bapuj, Dhule,424001', '20.90731250', '74.78929510'),
(27, 2618, '1St Floor, Pagaria Chamber,Jalgaon MIDC Road,Jalgaon,425001', '20.99195350', '75.57661940'),
(28, 2666, '2nd Floor, Ratnaprabha Bldg.,Adalat Road,,Aurangabad,431001', '19.87230480', '75.32170650'),
(29, 2667, '1St Floor Matoshri Complex,Shani Mandir Road,Jalna,431203', '19.82968930', '75.88003050'),
(30, 6059, '1St Floor, Salunke Complex,Vasmat Road,Parbhani,431401', '19.26099580', '76.77666500'),
(31, 2665, 'Kadam Tower, 1St Floor,Barshi Road,Beed,431122', '18.98908930', '75.76007850'),
(32, 2661, 'Kadam Tower, 1St Floor,Barshi Road,Beed,431122', '18.98908930', '75.76007850'),
(33, 2671, 'Mastan Heights, 1St Floor,Ausa Road,Latur,413512', '18.38024410', '76.55819560'),
(34, 2456, '2Nd Floor, Mf Motors,Hingoli Road,Nanded,431602', '19.17586480', '77.33360740'),
(35, 6641, 'Navathe Square,Badnera Road, Amravati 444805', '20.90850700', '77.75122170'),
(36, 2489, 'Devendra Complex, Shop No 01 Ground Floor behind Indian Overseas Bank Civil Line Nandura Road, Khamgaon, Buldhana 444303\n', '20.71162170', '76.56612760'),
(37, 2445, 'First Floor,Peshwe Complex,Above Vijay Bank,opp. S.P.Office LIC square,Yavatmal?445001', '20.38879370', '78.12040730'),
(38, 2454, '2nd Flour Above State Bank Of Hyderabad,Gandhi Nagar Arvi Naka road Wardha 442001', '20.74531900', '78.60219460'),
(39, 2458, '2nd Floor, Khatri House, Plot No.24/21,AmanKha Plot, Above Axis Bank,Jawahar Nagar,Road, Behind Shitala Mata Mandir, Akola-444001', '20.69964770', '77.01614360'),
(40, 2476, '2ND Floor, Shri Radhe Building,Chamorsi Road,Above,United India, Insurance, Gadchiroli-442605', '20.18487100', '79.99479560'),
(41, 2542, '1st Floor, Shop 5,6 & 7,Paras Plaza,Risod Road,Above HDFC Bank, Washim 444505', '20.11191230', '77.13125860'),
(42, 2451, 'Narang Tower, 1st floor, Opp Traffic Police, Civil Line, Nagpur 440001 India.', '21.15839170', '79.06733020'),
(43, 2481, 'Mahindra Finance, Nagpur, Maharashtra', '21.14580040', '79.08815460'),
(44, 2460, '2nd floor,Mahavidarbh Complex,Chanda Industrial Co-operative Estate,\nMul Road, Chandrapur, 442401\n', '20.01729310', '79.51089100'),
(45, 2633, 'Sur Sankul, 1St Floor,Dondaicha Road,Shahada,425409', '21.54558410', '74.46833330'),
(46, 900, '1St Floor,Sai Plaza , Above Vijay Sales,Kapurbawdi, Ghodbunder Road ,Thane -400607', '19.22185290', '72.97710720'),
(47, 2725, 'Bhigwan Road,Pune 413102', '18.16614870', '74.59705270'),
(48, 2668, 'Shop No. 71 to 74, 1st Floor, Sub Market Yard Shopping Centre,Opp. Tembhurni Bus Stand,Tembhurni,413211', '18.02945450', '75.19083950'),
(49, 1119, 'Ground Floor,Mahananda Chsl,Vajira Naka,Off L T Road Near Acharya Vidya  Mandir,Borivali,400092', '19.23718770', '72.84413580'),
(50, 1121, 'Ostwal Empire, Boisar, Maharashtra', '19.79689290', '72.74518170'),
(51, 1122, 'Ground Floor,Sterling Tower Apartment,Jehangir meherwaji road off murbad road.opp DCP Bunglow,Kalyan Murbad Road,kalyan,421301', '19.23950650', '73.13799850'),
(52, 1123, '1St Floor,Sankalp Heights,Opp.Jupiter Hospital,Nr.Flower Valley,Narlipada,Thane 400601', '19.20105020', '72.97853530');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areamaster`
--
ALTER TABLE `areamaster`
  ADD PRIMARY KEY (`areaid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areamaster`
--
ALTER TABLE `areamaster`
  MODIFY `areaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


update 		devices 
INNER JOIN 	vehicle on vehicle.uid = devices.uid
Inner JOIN 	areamaster on areamaster.vehid = vehicle.vehicleid
SET 		baselat = areamaster.lat
			, baselng=areamaster.lng
            , installlat = areamaster.lat
            , installlng=areamaster.lng 
where devices.customerno = 64;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 392;


