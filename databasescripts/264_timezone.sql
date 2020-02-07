-- Insert SQL here.

create table timezone(
tid int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
timezone varchar(200) NOT NULL,
timediff varchar(25) NOT NULL
);

INSERT INTO `timezone` (`tid`, `timezone`, `timediff`) VALUES
(1, 'Africa/Abidjan', '-05:30'),
(2, 'Africa/Accra', '-05:30'),
(3, 'Africa/Addis_Ababa', '-02:30'),
(4, 'Africa/Algiers', '-04:30'),
(5, 'Africa/Asmara', '-02:30'),
(6, 'Africa/Bangui', '-04:30'),
(7, 'Africa/Banjul', '-05:30'),
(8, 'Africa/Bissau', '-05:30'),
(9, 'Africa/Brazzaville', '-04:30'),
(10, 'Africa/Bujumbura', '-03:30'),
(11, 'Africa/Cairo', '-03:30'),
(12, 'Africa/Casablanca ', '-04:30'),
(13, 'Africa/Conakry', '-05:30'),
(14, 'Africa/Dakar', '-05:30'),
(15, 'Africa/Djibouti', '-02:30'),
(16, 'Africa/Freetown', '-05:30'),
(17, 'Africa/Gaborone', '-03:30'),
(18, 'Africa/Harare', '-03:30'),
(19, 'Africa/Juba', '-02:30'),
(20, 'Africa/Kampala', '-02:30'),
(21, 'Africa/Khartoum', '-02:30'),
(22, 'Africa/Kigali', '-03:30'),
(23, 'Africa/Kinshasa', '-04:30'),
(24, 'Africa/Lagos', '-04:30'),
(25, 'Africa/Libreville', '-04:30'),
(26, 'Africa/Lome', '-05:30'),
(27, 'Africa/Luanda', '-04:30'),
(28, 'Africa/Lubumbashi', '-03:30'),
(29, 'Africa/Lusaka', '-03:30'),
(30, 'Africa/Malabo', '-04:30'),
(31, 'Africa/Maputo', '-03:30'),
(32, 'Africa/Maseru', '-03:30'),
(33, 'Africa/Mbabane', '-03:30'),
(34, 'Africa/Mogadishu', '-02:30'),
(35, 'Africa/Monrovia', '-05:30'),
(36, 'Africa/Nairobi', '-02:30'),
(37, 'Africa/Ndjamena', '-04:30'),
(38, 'Africa/Niamey', '-04:30'),
(39, 'Africa/Nouakchott', '-05:30'),
(40, 'Africa/Ouagadougou', '-05:30'),
(41, 'Africa/Porto_Novo', '-04:30'),
(42, 'Africa/Sao_Tome', '-05:30'),
(43, 'Africa/Tripoli', '-03:30'),
(44, 'Africa/Tunis', '-04:30'),
(45, 'Africa/Windhoek', '-04:30'),
(46, 'America/ Coral_Harbour', '-10:30'),
(47, 'America/ Montreal ', '-09:30'),
(48, 'America/ Winnipeg ', '-10:30'),
(49, 'America/Adak ', '-14:30'),
(50, 'America/Anchorage ', '-13:30'),
(51, 'America/Antigua', '-09:30'),
(52, 'America/Argentina/Buenos Aires', '-08:30'),
(53, 'America/Argentina/San_Juan', '-09:30'),
(54, 'America/Asuncion', '-09:30'),
(55, 'America/Bahia', '-08:30'),
(56, 'America/Barbados', '-09:30'),
(57, 'America/Belize', '-11:30'),
(58, 'America/Blanc-Sablon', '-09:30'),
(59, 'America/Bogota', '-10:30'),
(60, 'America/Boise ', '-11:30'),
(61, 'America/Caracas', '-10:00'),
(62, 'America/Cayenne', '-08:30'),
(63, 'America/Cayman', '-10:30'),
(64, 'America/Chicago ', '-10:30'),
(65, 'America/Danmarkshavn', '-05:30'),
(66, 'America/Denver ', '-11:30'),
(67, 'America/Detroit ', '-09:30'),
(68, 'America/Domingo', '-09:30'),
(69, 'America/Edmonton ', '-11:30'),
(70, 'America/El_Salvador', '-11:30'),
(71, 'America/Fortaleza', '-08:30'),
(72, 'America/Grenada', '-09:30'),
(73, 'America/Guadeloupe', '-09:30'),
(74, 'America/Guatemala', '-11:30'),
(75, 'America/Guayaquil', '-10:30'),
(76, 'America/Guyana', '-09:30'),
(77, 'America/Halifax ', '-08:30'),
(78, 'America/Havana ', '-09:30'),
(79, 'America/Hermosillo', '-12:30'),
(80, 'America/Inuvik ', '-11:30'),
(81, 'America/Jamaica ', '-10:30'),
(82, 'America/Kentucky/Louisville ', '-09:30'),
(83, 'America/Lima', '-10:30'),
(84, 'America/Los_Angeles ', '-12:30'),
(85, 'America/Managua', '-11:30'),
(86, 'America/Martinique', '-09:30'),
(87, 'America/Mazatlan ', '-11:30'),
(88, 'America/Mexico_City ', '-10:30'),
(89, 'America/Montevideo', '-08:30'),
(90, 'America/Panama', '-10:30'),
(91, 'America/Paramaribo', '-08:30'),
(92, 'America/Phoenix', '-12:30'),
(93, 'America/Recife', '-08:30'),
(94, 'America/Regina', '-11:30'),
(95, 'America/Resolute', '-10:30'),
(96, 'America/Rio_Branco', '-10:30'),
(97, 'America/Santiago', '-08:30'),
(98, 'America/Sao_Paulo', '-08:30'),
(99, 'America/St_Kitts', '-09:30'),
(100, 'America/St_Lucia', '-09:30'),
(101, 'America/St_Vincent', '-09:30'),
(102, 'America/Tegucigalpa', '-11:30'),
(103, 'America/Thule', '-08:30'),
(104, 'America/Tijuana ', '-12:30'),
(105, 'America/Toronto ', '-09:30'),
(106, 'America/Vancouver ', '-12:30'),
(107, 'America/Whitehorse ', '-12:30'),
(108, 'America/Yellowknife ', '-11:30'),
(109, 'America/Nassau ', '-09:30'),
(110, 'Arctic/Longyearbyen ', '-03:30'),
(111, 'Asia/Aden', '-02:30'),
(112, 'Asia/Almaty', '00:30'),
(113, 'Asia/Amman ', '-02:30'),
(114, 'Asia/Anadyr', '06:30'),
(115, 'Asia/Aqtobe', '-00:30'),
(116, 'Asia/Ashgabat', '-00:30'),
(117, 'Asia/Baghdad', '-02:30'),
(118, 'Asia/Bahrain', '-02:30'),
(119, 'Asia/Baku ', '-00:30'),
(120, 'Asia/Bangkok', '01:30'),
(121, 'Asia/Beirut ', '-02:30'),
(122, 'Asia/Bishkek', '00:30'),
(123, 'Asia/Brunei', '02:30'),
(124, 'Asia/Calcutta', '00:00'),
(125, 'Asia/Chita', '02:30'),
(126, 'Asia/Chongqing', '02:30'),
(127, 'Asia/Colombo', '00:00'),
(128, 'Asia/Damascus ', '-02:30'),
(129, 'Asia/Dhaka', '00:30'),
(130, 'Asia/Dili', '03:30'),
(131, 'Asia/Dubai', '-01:30'),
(132, 'Asia/Dushanbe', '-00:30'),
(133, 'Asia/Hong_Kong', '02:30'),
(134, 'Asia/Hovd ', '02:30'),
(135, 'Asia/Irkutsk', '02:30'),
(136, 'Asia/Istanbul ', '-02:30'),
(137, 'Asia/Jakarta', '01:30'),
(138, 'Asia/Jayapura', '03:30'),
(139, 'Asia/Jerusalem ', '-02:30'),
(140, 'Asia/Kabul', '-01:00'),
(141, 'Asia/Nicosia ', '-02:30'),
(142, 'Asia/Kathmandu', '00:15'),
(143, 'Asia/Kolkata', '00:00'),
(144, 'Asia/Krasnoyarsk', '01:30'),
(145, 'Asia/Kuala_Lumpur', '02:30'),
(146, 'Asia/Kuwait', '-02:30'),
(147, 'Asia/Magadan', '04:30'),
(148, 'Asia/Makassar', '02:30'),
(149, 'Asia/Manila', '02:30'),
(150, 'Asia/Muscat', '-01:30'),
(151, 'Asia/Novosibirsk', '00:30'),
(152, 'Asia/Omsk', '00:30'),
(153, 'Asia/Oral', '-00:30'),
(154, 'Asia/Phnom_Penh', '01:30'),
(155, 'Asia/Pontianak', '01:30'),
(156, 'Asia/Pyongyang', '03:30'),
(157, 'Asia/Qatar', '-02:30'),
(158, 'Asia/Riyadh', '-02:30'),
(159, 'Asia/Seoul', '03:30'),
(160, 'Asia/Shanghai', '02:30'),
(161, 'Asia/Singapore', '02:30'),
(162, 'Asia/Srednekolymsk', '05:30'),
(163, 'Asia/Taipei', '02:30'),
(164, 'Asia/Tashkent', '-00:30'),
(165, 'Asia/Tbilisi', '-01:30'),
(166, 'Asia/Tehran ', '-01:00'),
(167, 'Asia/Tel_ Aviv ', '-02:30'),
(168, 'Asia/Thimphu', '00:30'),
(169, 'Asia/Tokyo', '03:30'),
(170, 'Asia/Ulaanbaatar ', '03:30'),
(171, 'Asia/Vientiane', '01:30'),
(172, 'Asia/Vladivostok', '04:30'),
(173, 'Asia/Yakutsk', '03:30'),
(174, 'Asia/Yekaterinburg', '-00:30'),
(175, 'Asia/Yerevan', '-01:30'),
(176, 'Atlantic/Bermuda', '-08:30'),
(177, 'Atlantic/Faroe', '-04:30'),
(178, 'Atlantic/Reykjavik', '-05:30'),
(179, 'Atlantic/St_Helena', '-05:30'),
(180, 'Atlantic/Stanley', '-08:30'),
(181, 'Australia/Adelaide', '04:00'),
(182, 'Australia/Brisbane', '04:30'),
(183, 'Australia/Canberra', '04:30'),
(184, 'Australia/Darwin', '04:00'),
(185, 'Australia/Eucla', '03:15'),
(186, 'Australia/Hobart', '04:30'),
(187, 'Australia/Melbourne', '04:30'),
(188, 'Australia/Perth', '02:30'),
(189, 'Australia/Queensland', '04:30'),
(190, 'Australia/Sydney', '04:30'),
(191, 'Europe/ Gibraltar ', '-03:30'),
(192, 'Europe/ Madrid ', '-03:30'),
(193, 'Europe/Amsterdam ', '-03:30'),
(194, 'Europe/Andorra', '-03:30'),
(195, 'Europe/Athens ', '-02:30'),
(196, 'Europe/Belfast ', '-04:30'),
(197, 'Europe/Belgrade ', '-03:30'),
(198, 'Europe/Berlin ', '-03:30'),
(199, 'Europe/Bratislava ', '-03:30'),
(200, 'Europe/Brussels ', '-03:30'),
(201, 'Europe/Bucharest ', '-02:30'),
(202, 'Europe/Budapest ', '-03:30'),
(203, 'Europe/Chisinau ', '-02:30'),
(204, 'Europe/Dublin ', '-04:30'),
(205, 'Europe/Helsinki ', '-02:30'),
(206, 'Europe/Lisbon ', '-04:30'),
(207, 'Europe/Ljubljana ', '-03:30'),
(208, 'Europe/London ', '-04:30'),
(209, 'Europe/Luxembourg ', '-03:30'),
(210, 'Europe/Malta', '-03:30'),
(211, 'Europe/Minsk', '-02:30'),
(212, 'Europe/Monaco ', '-03:30'),
(213, 'Europe/Moscow', '-02:30'),
(214, 'Europe/Oslo ', '-03:30'),
(215, 'Europe/Paris ', '-03:30'),
(216, 'Europe/Podgorica ', '-03:30'),
(217, 'Europe/Prague ', '-03:30'),
(218, 'Europe/Riga ', '-02:30'),
(219, 'Europe/Rome ', '-03:30'),
(220, 'Europe/Samara', '-01:30'),
(221, 'Europe/San_Marino ', '-03:30'),
(222, 'Europe/Sarajevo ', '-03:30'),
(223, 'Europe/Simferopol', '-02:30'),
(224, 'Europe/Skopje ', '-03:30'),
(225, 'Europe/Sofia ', '-02:30'),
(226, 'Europe/Stockholm ', '-03:30'),
(227, 'Europe/Tallinn ', '-02:30'),
(228, 'Europe/Vaduz ', '-03:30'),
(229, 'Europe/Vatican', '-03:30'),
(230, 'Europe/Vilnius ', '-02:30'),
(231, 'Europe/Warsaw ', '-03:30'),
(232, 'Europe/Zagreb ', '-03:30'),
(233, 'Europe/Zurich', '-03:30'),
(234, 'Indian/Antananarivo', '-02:30'),
(235, 'Indian/Christmas', '08:30'),
(236, 'Indian/Maldives', '-00:30'),
(237, 'Indian/Mauritius', '-01:30'),
(238, 'Indian/Reunion', '-01:30'),
(239, 'Pacific/Apia', '07:30'),
(240, 'Pacific/Auckland', '06:30'),
(241, 'Pacific/Chatham', '07:15'),
(242, 'Pacific/Fakaofo', '07:30'),
(243, 'Pacific/Fiji', '06:30'),
(244, 'Pacific/Funafuti', '06:30'),
(245, 'Pacific/Galapago', '-11:30'),
(246, 'Pacific/Guam', '04:30'),
(247, 'Pacific/Honolulu', '-15:30'),
(248, 'Pacific/Midway', '-16:30'),
(249, 'Pacific/Nauru', '06:30'),
(250, 'Pacific/Niue', '-16:30'),
(251, 'Pacific/Palau', '03:30'),
(252, 'Pacific/Pitcairn', '-13:30'),
(253, 'Pacific/Pohnpei', '05:30'),
(254, 'Pacific/Rarotonga', '-15:30'),
(255, 'Pacific/Tahiti', '-15:30'),
(256, 'Pacific/Tarawa', '06:30'),
(257, 'Pacific/Wake', '06:30');



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (264, NOW(), 'Shrikanth Suryawanshi','Timezone');