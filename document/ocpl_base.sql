-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2016 at 05:37 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ocpl_base`
--

-- --------------------------------------------------------

--
-- Table structure for table `area_info`
--

CREATE TABLE IF NOT EXISTS `area_info` (
  `area_id` int(11) DEFAULT NULL,
  `area_nm` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pare_id` int(11) DEFAULT NULL,
  `area_type` tinyint(4) DEFAULT NULL,
  `area_nm_ban` varchar(480) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nid_area_code` int(11) DEFAULT NULL,
  `sb_dist_code` int(11) DEFAULT NULL,
  `soundex_nm` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `area_info`
--

INSERT INTO `area_info` (`area_id`, `area_nm`, `pare_id`, `area_type`, `area_nm_ban`, `nid_area_code`, `sb_dist_code`, `soundex_nm`) VALUES
(1, 'BAGERHAT', 5, 2, 'বাগেরহাট', 1, 33, 'B263'),
(2, 'Dhaka', 0, 1, 'ঢাকা', NULL, NULL, 'D200'),
(3, 'BANDARBAN', 14, 2, 'বান্দরবান', 3, 23, 'B53615'),
(4, 'BARGUNA', 11, 2, 'বরগুনা', 4, 64, 'B625'),
(5, 'Khulna Metro', 0, 1, 'খুলনা মেট্রো', NULL, NULL, 'K4536'),
(6, 'BARISAL', 11, 2, 'বরিশাল', 6, 59, 'B624'),
(7, 'Rajshahi', 0, 1, 'রাজশাহী', NULL, NULL, 'R200'),
(8, 'Sylhet', 0, 1, 'সিলেট', NULL, NULL, 'S430'),
(9, 'BHOLA', 11, 2, 'ভোলা', 9, 80, 'B400'),
(10, 'BOGRA', 7, 2, 'বগুড়া', 10, 57, 'B260'),
(11, 'Barisal', 0, 1, 'বরিশাল', NULL, NULL, 'B624'),
(12, 'BRAHMANBARIA', 14, 2, 'ব্রাহ্মণবাড়িয়া', 12, 27, 'B6516'),
(13, 'CHANDPUR', 14, 2, 'চাঁদপুর', 13, 26, 'C5316'),
(14, 'Chittagong', 0, 1, 'চট্রগ্রাম', NULL, NULL, 'C3252'),
(15, 'CHITTAGONG', 14, 2, 'চট্টগ্রাম', 15, 20, 'C3252'),
(16, 'Rangpur', 0, 1, 'রংপুর', NULL, NULL, 'R5216'),
(18, 'CHUADANGA', 5, 2, 'চুয়াডাঙ্গা', 18, 40, 'C352'),
(19, 'COMILLA', 14, 2, 'কুমিল্লা', 19, 25, 'C540'),
(22, 'COX''S BAZAR', 14, 2, 'কক্সবাজার', 22, 21, 'C126'),
(26, 'DHAKA', 2, 2, 'ঢাকা', 26, 2, 'D200'),
(27, 'DINAJPUR', 16, 2, 'দিনাজপুর', 27, 52, 'D5216'),
(29, 'FARIDPUR', 2, 2, 'ফরিদপুর', 29, 15, 'F6316'),
(30, 'FENI', 14, 2, 'ফেনী', 30, 30, 'F500'),
(32, 'GAIBANDHA', 16, 2, 'গাইবান্ধা', 32, 48, 'G153'),
(33, 'GAZIPUR', 2, 2, 'গাজীপুর', 33, 4, 'G160'),
(35, 'GOPALGANJ', 2, 2, 'গোপালগঞ্জ', 35, 16, 'G14252'),
(36, 'HABIGANJ', 8, 2, 'হবিগঞ্জ', 36, 66, 'H1252'),
(38, 'JOYPURHAT', 7, 2, 'জয়পুরহাট', 38, 58, 'J163'),
(39, 'JAMALPUR', 2, 2, 'জামালপুর', 39, 14, 'J5416'),
(41, 'JESSORE', 5, 2, 'যশোর', 41, 35, 'J600'),
(42, 'JHALOKATI', 11, 2, 'ঝালকাঠী', 42, 60, 'J423'),
(44, 'JHENAIDAH', 5, 2, 'ঝিনাইদহ', 44, 37, 'J530'),
(46, 'KHAGRACHHARI', 14, 2, 'খাগড়াছড়ি', 46, 24, 'K626'),
(47, 'KHULNA', 5, 2, 'খুলনা', 47, 32, 'K450'),
(48, 'KISHOREGONJ', 2, 2, 'কিশোরগঞ্জ', 48, 11, 'K6252'),
(49, 'KURIGRAM', 16, 2, 'কুড়িগ্রাম', 49, 50, 'K6265'),
(50, 'KUSHTIA', 5, 2, 'কুষ্টিয়া', 50, 39, 'K300'),
(51, 'LAKSHMIPUR', 14, 2, 'লক্ষ্মীপুর', 51, 29, 'L2516'),
(52, 'LALMONIRHAT', 16, 2, 'লালমনিরহাট', 52, 51, 'L563'),
(54, 'MADARIPUR', 2, 2, 'মাদারীপুর', 54, 17, 'M3616'),
(55, 'MAGURA', 5, 2, 'মাগুরা', 55, 36, 'M260'),
(56, 'MANIKGANJ', 2, 2, 'মানিকগঞ্জ', 56, 5, 'M252'),
(57, 'MEHERPUR', 5, 2, 'মেহেরপুর', 57, 41, 'M616'),
(58, 'MAULVIBAZAR', 8, 2, 'মৌলভীবাজার', 58, 68, 'M4126'),
(59, 'MUNSHIGANJ', 2, 2, 'মুন্সীগঞ্জ', 59, 6, 'M252'),
(61, 'MYMENSINGH', 2, 2, 'ময়মনসিংহ', 61, 8, 'M252'),
(64, 'NAOGAON', 7, 2, 'নওগাঁ', 64, 45, 'N250'),
(65, 'NARAIL', 5, 2, 'নড়াইল', 65, 38, 'N640'),
(67, 'NARAYANGANJ', 2, 2, 'নারায়ণগঞ্জ', 67, 3, 'N65252'),
(68, 'NARSINGDI', 2, 2, 'নরসিংদী', 68, 7, 'N62523'),
(69, 'NATORE', 7, 2, 'নাটোর', 69, 46, 'N360'),
(70, 'CHAPAINABABGANJ', 7, 2, 'চাঁপাইনবাবগঞ্জ', 70, 44, 'C151252'),
(72, 'NETRAKONA', 2, 2, 'নেত্রকোনা', 72, 12, 'N3625'),
(73, 'NILPHAMARI ZILA', 16, 2, 'নীলফামারী', 73, 49, 'N415624'),
(75, 'NOAKHALI', 14, 2, 'নোয়াখালী', 75, 28, 'N240'),
(76, 'PABNA', 7, 2, 'পাবনা', 76, 55, 'P500'),
(77, 'PANCHAGARH', 16, 2, 'পঞ্চগড়', 77, 54, 'P526'),
(78, 'PATUAKHALI', 11, 2, 'পটুয়াখালী', 78, 62, 'P324'),
(79, 'PIROJPUR', 11, 2, 'পিরোজপুর', 79, 63, 'P6216'),
(81, 'RAJSHAHI', 7, 2, 'রাজশাহী', 81, 43, 'R200'),
(82, 'RAJBARI', 2, 2, 'রাজবাড়ী', 82, 18, 'R216'),
(84, 'RANGAMATI', 14, 2, 'রাঙ্গামাটি', 84, 22, 'R5253'),
(85, 'RANGPUR', 16, 2, 'রংপুর', 85, 47, 'R5216'),
(86, 'SHARIATPUR', 2, 2, 'শরিয়তপুর', 86, 9, 'S6316'),
(87, 'SATKHIRA', 5, 2, 'সাতক্ষীরা', 87, 34, 'S326'),
(88, 'SIRAJGANJ', 7, 2, 'সিরাজগঞ্জ', 88, 56, 'S6252'),
(89, 'SHERPUR', 2, 2, 'শেরপুর', 89, 13, 'S616'),
(90, 'SUNAMGANJ', 8, 2, 'সুনামগঞ্জ', 90, 67, 'S5252'),
(91, 'SYLHET', 8, 2, 'সিলেট', 91, 65, 'S430'),
(93, 'TANGAIL', 2, 2, 'টাঙ্গাইল', 93, 10, 'T524'),
(94, 'THAKURGAON', 16, 2, 'ঠাকুরগাঁও', 94, 53, 'T2625'),
(95, 'BAGERHAT SADAR', 1, 3, 'বাগেরহাট সদর', 8, NULL, 'B263236'),
(96, 'CHITALMARI', 1, 3, 'চিতলমারী', 14, NULL, 'C3456'),
(97, 'FAKIRHAT', 1, 3, 'ফকিরহাট', 34, NULL, 'F263'),
(98, 'KACHUA', 1, 3, 'কচুয়া', 38, NULL, 'K000'),
(99, 'MOLLAHAT', 1, 3, 'মোল্লাহাট', 56, NULL, 'M430'),
(100, 'MONGLA', 1, 3, 'মোংলা', 58, NULL, 'M240'),
(101, 'MORRELGANJ', 1, 3, 'মোড়লগঞ্জ', 60, NULL, 'M64252'),
(102, 'RAMPAL', 1, 3, 'রামপাল', 73, NULL, 'R514'),
(103, 'SARANKHOLA', 1, 3, 'শরণখোলা', 77, NULL, 'S6524'),
(104, 'ALIKADAM', 3, 3, 'আলীকদম', 4, NULL, 'A4235'),
(105, 'BANDARBAN SADAR', 3, 3, 'বান্দরবান সদর', 14, NULL, 'B53615236'),
(106, 'LAMA', 3, 3, 'লামা', 51, NULL, 'L500'),
(107, 'NAIKHONGCHHARI', 3, 3, 'নাইক্ষ্যংছড়ি', 73, NULL, 'N2526'),
(108, 'ROWANGCHHARI', 3, 3, 'রোয়াংছড়ি', 89, NULL, 'R526'),
(109, 'RUMA', 3, 3, 'রুমা', 91, NULL, 'R500'),
(110, 'THANCHI', 3, 3, 'থান্‌চি', 95, NULL, 'T520'),
(111, 'AMTALI', 4, 3, 'আমতলী', 9, NULL, 'A534'),
(112, 'BAMNA', 4, 3, 'বামনা', 19, NULL, 'B500'),
(113, 'BARGUNA SADAR', 4, 3, 'বরগুনা সদর', 28, NULL, 'B625236'),
(114, 'BETAGI', 4, 3, 'বেতাগী', 47, NULL, 'B320'),
(115, 'PATHARGHATA', 4, 3, 'পাথরঘাটা', 85, NULL, 'P3623'),
(116, 'TALTOLI', 4, 3, 'তালতলী', 92, NULL, 'T434'),
(117, 'AGAILJHARA', 6, 3, 'আগৈলঝাড়া', 2, NULL, 'A2426'),
(118, 'BABUGANJ', 6, 3, 'বাবুগঞ্জ', 3, NULL, 'B252'),
(119, 'BAKERGANJ', 6, 3, 'বাকেরগঞ্জ', 7, NULL, 'B26252'),
(120, 'BANARI PARA', 6, 3, 'বানারী পাড়া', 10, NULL, 'B5616'),
(121, 'GAURNADI', 6, 3, 'গৌরনদী', 32, NULL, 'G653'),
(122, 'HIZLA', 6, 3, 'হিজলা', 36, NULL, 'H240'),
(123, 'BARISAL SADAR', 6, 3, 'বরিশাল সদর', 51, NULL, 'B624236'),
(124, 'MHENDIGANJ', 6, 3, 'মেহেন্দীগঞ্জ', 62, NULL, 'M3252'),
(125, 'MULADI', 6, 3, 'মুলাদী', 69, NULL, 'M430'),
(126, 'WAZIRPUR', 6, 3, 'উজিরপুর', 94, NULL, 'W2616'),
(127, 'BHOLA SADAR', 9, 3, 'ভোলা সদর', 18, NULL, 'B4236'),
(128, 'BURHANUDDIN', 9, 3, 'বোরহানউদ্দীন', 21, NULL, 'B6535'),
(129, 'CHAR FASSON', 9, 3, 'চর ফ্যাশন', 25, NULL, 'C6125'),
(130, 'DAULAT KHAN', 9, 3, 'দৌলত খান', 29, NULL, 'D4325'),
(131, 'LALMOHAN', 9, 3, 'লালমোহন', 54, NULL, 'L500'),
(132, 'MANPURA', 9, 3, 'মনপুরা', 65, NULL, 'M160'),
(133, 'TAZUMUDDIN', 9, 3, 'তজুমুদ্দিন', 91, NULL, 'T2535'),
(134, 'ADAMDIGHI', 10, 3, 'আদমদিঘী', 6, NULL, 'A3532'),
(135, 'BOGRA SADAR', 10, 3, 'বগুড়া সদর', 20, NULL, 'B26236'),
(136, 'DHUNAT', 10, 3, 'ধুনট', 27, NULL, 'D530'),
(137, 'DHUPCHANCHIA', 10, 3, 'ধুপচাঁচিয়া', 33, NULL, 'D1252'),
(138, 'GABTALI', 10, 3, 'গাবতলী', 40, NULL, 'G134'),
(139, 'KAHALOO', 10, 3, 'কাহালু', 54, NULL, 'K400'),
(140, 'NANDIGRAM', 10, 3, 'নন্দীগ্রাম', 67, NULL, 'N3265'),
(141, 'SARIAKANDI', 10, 3, 'সারিয়াকান্দি', 81, NULL, 'S6253'),
(142, 'SHAJAHANPUR', 10, 3, 'শাজাহানপুর', 85, NULL, 'S516'),
(143, 'SHERPUR', 10, 3, 'শেরপুর', 88, NULL, 'S616'),
(144, 'SHIBGANJ', 10, 3, 'শিব্‌গঞ্জ', 94, NULL, 'S1252'),
(145, 'SONATOLA', 10, 3, 'সোনাতলা', 95, NULL, 'S534'),
(146, 'AKHAURA', 12, 3, 'আখাউড়া', 2, NULL, 'A260'),
(147, 'BANCHHARAMPUR', 12, 3, 'বাঞ্ছারামপুর', 4, NULL, 'B526516'),
(148, 'BIJOYNAGAR', 12, 3, 'বিজয়নগর', 7, NULL, 'B2526'),
(149, 'BRAHMANBARIA SADAR', 12, 3, 'ব্রাক্ষ্মণবাড়িয়া সদর', 13, NULL, 'B6516236'),
(150, 'ASHUGANJ', 12, 3, 'আশুগঞ্জ', 33, NULL, 'A252'),
(151, 'KASBA', 12, 3, 'কস্‌বা', 63, NULL, 'K100'),
(152, 'NABINAGAR', 12, 3, 'নবীনগর', 85, NULL, 'N1526'),
(153, 'NASIRNAGAR', 12, 3, 'নাসিরনগর', 90, NULL, 'N26526'),
(154, 'SARAIL', 12, 3, 'সরাইল', 94, NULL, 'S640'),
(155, 'CHANDPUR SADAR', 13, 3, 'চাঁদপুর সদর', 22, NULL, 'C5316236'),
(156, 'FARIDGANJ', 13, 3, 'ফরিদগঞ্জ', 45, NULL, 'F63252'),
(157, 'HAIM CHAR', 13, 3, 'হাইমচর', 47, NULL, 'H526'),
(158, 'HAJIGANJ', 13, 3, 'হাজীগঞ্জ', 49, NULL, 'H252'),
(159, 'KACHUA', 13, 3, 'কচুয়া', 58, NULL, 'K000'),
(160, 'MATLAB DAKSHIN', 13, 3, 'মতলব দক্ষিণ', 76, NULL, 'M341325'),
(161, 'MATLAB UTTAR', 13, 3, 'মতলব উত্তর', 79, NULL, 'M34136'),
(162, 'SHAHRASTI', 13, 3, 'শাহরাস্তি', 95, NULL, 'S623'),
(163, 'ANOWARA', 15, 3, 'আনোয়ারা', 4, NULL, 'A560'),
(164, 'BAYEJID BOSTAMI', 15, 3, 'বায়জিদ বোস্তামী', 6, NULL, 'B231235'),
(165, 'BANSHKHALI', 15, 3, 'বাঁশখালী', 8, NULL, 'B524'),
(166, 'BAKALIA', 15, 3, 'বাকলীয়া', 10, NULL, 'B240'),
(167, 'BOALKHALI', 15, 3, 'বোয়ালখালী', 12, NULL, 'B424'),
(168, 'CHANDANAISH', 15, 3, 'চন্দনাইশ', 18, NULL, 'C5352'),
(169, 'CHANDGAON', 15, 3, 'চাঁদগাও', 19, NULL, 'C5325'),
(170, 'CHITTAGONG PORT', 15, 3, 'চট্টগ্রাম পোর্ট', 20, NULL, 'C3252163'),
(171, 'DOUBLE MOORING', 15, 3, 'ডবলমুরিং', 28, NULL, 'D145652'),
(172, 'FATIKCHHARI', 15, 3, 'ফটিকছড়ি', 33, NULL, 'F326'),
(173, 'HALISHAHAR', 15, 3, 'হালিশহর', 35, NULL, 'H426'),
(174, 'HATHAZARI', 15, 3, 'হাটহাজারী', 37, NULL, 'H326'),
(175, 'KARNAFULI (POLICE STATION)', 15, 3, 'কর্ণফুলী (পুলিশ ষ্টেশন)', 39, NULL, 'K651414235'),
(176, 'KOTWALI', 15, 3, 'কোতয়ালী', 41, NULL, 'K340'),
(177, 'KHULSHI', 15, 3, 'খুল্‌শী', 43, NULL, 'K420'),
(178, 'LOHAGARA', 15, 3, 'লোহাগড়া', 47, NULL, 'L260'),
(179, 'MIRSHARAI', 15, 3, 'মিরশরাই', 53, NULL, 'M626'),
(180, 'PAHARTALI', 15, 3, 'পাহাড়তলী', 55, NULL, 'P634'),
(181, 'PANCHLAISH', 15, 3, 'পাঁচলাইশ', 57, NULL, 'P5242'),
(182, 'PATIYA', 15, 3, 'পটিয়া', 61, NULL, 'P300'),
(183, 'PATENGA', 15, 3, 'পতেঙ্গা', 65, NULL, 'P352'),
(184, 'RANGUNIA', 15, 3, 'রাংগুনীয়া', 70, NULL, 'R525'),
(185, 'RAOZAN', 15, 3, 'রাউজান', 74, NULL, 'R250'),
(186, 'SANDWIP', 15, 3, 'সন্দ্বীপ', 78, NULL, 'S531'),
(187, 'SATKANIA', 15, 3, 'সাতকানিয়া', 82, NULL, 'S325'),
(188, 'SITAKUNDA', 15, 3, 'সীতাকুন্ড', 86, NULL, 'S3253'),
(189, 'ALAMDANGA', 18, 3, 'আলমডাংগা', 7, NULL, 'A45352'),
(190, 'CHUADANGA SADAR', 18, 3, 'চুয়াডাঙ্গা সদর', 23, NULL, 'C35236'),
(191, 'DAMURHUDA', 18, 3, 'দামুরহুদা', 31, NULL, 'D563'),
(192, 'JIBAN NAGAR', 18, 3, 'জীবন নগর', 55, NULL, 'J1526'),
(193, 'BARURA', 19, 3, 'বরুড়া', 9, NULL, 'B600'),
(194, 'BRAHMAN PARA', 19, 3, 'ব্রাক্ষ্মণ পাড়া', 15, NULL, 'B6516'),
(195, 'BURICHANG', 19, 3, 'বুড়িচং', 18, NULL, 'B6252'),
(196, 'CHANDINA', 19, 3, 'চন্দিনা', 27, NULL, 'C535'),
(197, 'CHAUDDAGRAM', 19, 3, 'চৌদ্দগ্রাম', 31, NULL, 'C3265'),
(198, 'COMILLA SADAR DAKSHIN', 19, 3, 'কুমিল্লা সদর দক্ষিণ', 33, NULL, 'C54236325'),
(199, 'DAUDKANDI', 19, 3, 'দাউদকান্দি', 36, NULL, 'D253'),
(200, 'DEBIDWAR', 19, 3, 'দেবিদ্বার', 40, NULL, 'D136'),
(201, 'HOMNA', 19, 3, 'হোমনা', 54, NULL, 'H500'),
(202, 'COMILLA ADARSHA SADAR', 19, 3, 'কুমিল্লা আদর্শ সদর', 67, NULL, 'C5436236'),
(203, 'LAKSAM', 19, 3, 'লাকসাম', 72, NULL, 'L250'),
(204, 'MANOHARGANJ', 19, 3, 'মনোহরগঞ্জ', 74, NULL, 'M6252'),
(205, 'MEGHNA', 19, 3, 'মেঘনা', 75, NULL, 'M250'),
(206, 'MURADNAGAR', 19, 3, 'মুরাদনগর', 81, NULL, 'M63526'),
(207, 'NANGALKOT', 19, 3, 'নাঙ্গলকোট', 87, NULL, 'N2423'),
(208, 'TITAS', 19, 3, 'তিতাস', 94, NULL, 'T200'),
(209, 'CHAKARIA', 22, 3, 'চকরিয়া', 16, NULL, 'C600'),
(210, 'COX''S BAZAR SADAR', 22, 3, 'কক্সবাজার সদর', 24, NULL, 'C126236'),
(211, 'KUTUBDIA', 22, 3, 'কুতুবদিয়া', 45, NULL, 'K313'),
(212, 'MAHESHKHALI', 22, 3, 'মহেশখালী', 49, NULL, 'M240'),
(213, 'PEKUA', 22, 3, 'পেকুয়া', 56, NULL, 'P200'),
(214, 'RAMU', 22, 3, 'রামু', 66, NULL, 'R500'),
(215, 'TEKNAF', 22, 3, 'টেক্‌নাফ', 90, NULL, 'T251'),
(216, 'UKHIA', 22, 3, 'উখিয়া', 94, NULL, 'U200'),
(217, 'ADABOR', 26, 3, 'আদাবর', 2, NULL, 'A316'),
(218, 'BADDA', 26, 3, 'বাড্ডা', 4, NULL, 'B300'),
(219, 'BIMAN BANDAR', 26, 3, 'বিমান বন্দর', 6, NULL, 'B51536'),
(220, 'CANTONMENT', 26, 3, 'ক্যান্টনমেন্ট', 8, NULL, 'C5353'),
(221, 'DAKSHINKHAN', 26, 3, 'দক্ষিণখান', 10, NULL, 'D2525'),
(222, 'DEMRA', 26, 3, 'ডেমরা', 12, NULL, 'D560'),
(223, 'DHAMRAI', 26, 3, 'ধামরাই', 14, NULL, 'D560'),
(224, 'DHANMONDI', 26, 3, 'ধানমন্ডি', 16, NULL, 'D530'),
(225, 'DOHAR', 26, 3, 'দোহার', 18, NULL, 'D600'),
(226, 'GANDARIA', 26, 3, 'গেন্ডারিয়া', 24, NULL, 'G536'),
(227, 'GULSHAN', 26, 3, 'গুলশান', 26, NULL, 'G425'),
(228, 'HAZARIBAGH', 26, 3, 'হাজারীবাগ', 28, NULL, 'H2612'),
(229, 'JATRABARI', 26, 3, 'যাত্রাবাড়ী', 29, NULL, 'J3616'),
(230, 'KAFRUL', 26, 3, 'কাফরুল', 30, NULL, 'K164'),
(231, 'KADAMTALI', 26, 3, 'কদমতলী', 32, NULL, 'K3534'),
(232, 'KAMRANGIR CHAR', 26, 3, 'কামরাঙ্গীর চর', 34, NULL, 'K5652626'),
(233, 'KHILGAON', 26, 3, 'খিলগাও', 36, NULL, 'K425'),
(234, 'KHILKHET', 26, 3, 'খিলক্ষেত', 37, NULL, 'K423'),
(235, 'KERANIGANJ', 26, 3, 'কেরানীগঞ্জ', 38, NULL, 'K65252'),
(236, 'KOTWALI', 26, 3, 'কোতয়ালী', 40, NULL, 'K340'),
(237, 'LALBAGH', 26, 3, 'লালবাগ', 42, NULL, 'L120'),
(238, 'MIRPUR', 26, 3, 'মিরপুর', 48, NULL, 'M616'),
(239, 'MOHAMMADPUR', 26, 3, 'মোহাম্মদপুর', 50, NULL, 'M316'),
(240, 'MOTIJHEEL', 26, 3, 'মতিঝিল', 54, NULL, 'M324'),
(241, 'NAWABGANJ', 26, 3, 'নবাবগঞ্জ', 62, NULL, 'N1252'),
(242, 'NEW MARKET', 26, 3, 'নিউ মার্কেট', 63, NULL, 'N623'),
(243, 'PALLABI', 26, 3, 'পল্লবী', 64, NULL, 'P410'),
(244, 'PALTAN', 26, 3, 'পল্টন', 65, NULL, 'P435'),
(245, 'RAMNA', 26, 3, 'রমনা', 66, NULL, 'R500'),
(246, 'SABUJBAGH', 26, 3, 'সবুজবাগ', 68, NULL, 'S1212'),
(247, 'SAVAR', 26, 3, 'সাভার', 72, NULL, 'S160'),
(248, 'SHAH ALI', 26, 3, 'শাহ্‌ আলী', 74, NULL, 'S400'),
(249, 'SHAHBAGH', 26, 3, 'শাহবাগ', 75, NULL, 'S120'),
(250, 'SHYAMPUR', 26, 3, 'শ্যামপুর', 76, NULL, 'S516'),
(251, 'SUTRAPUR', 26, 3, 'সুত্রাপুর', 88, NULL, 'S3616'),
(252, 'TEJGAON', 26, 3, 'তেজগাঁও', 90, NULL, 'T250'),
(253, 'TEJGAON IND. AREA', 26, 3, 'তেজগাঁও শিল্প এলাকা', 92, NULL, 'T2536'),
(254, 'TURAG', 26, 3, 'তুরাগ', 93, NULL, 'T620'),
(255, 'UTTARA', 26, 3, 'উত্তরা', 95, NULL, 'U360'),
(256, 'UTTAR KHAN', 26, 3, 'উত্তর খান', 96, NULL, 'U3625'),
(257, 'BIRAMPUR', 27, 3, 'বিরামপুর', 10, NULL, 'B6516'),
(258, 'BIRGANJ', 27, 3, 'বীরগঞ্জ', 12, NULL, 'B6252'),
(259, 'BIRAL', 27, 3, 'বিরল', 17, NULL, 'B640'),
(260, 'BOCHAGANJ', 27, 3, 'বোচাগঞ্জ', 21, NULL, 'B252'),
(261, 'CHIRIRBANDAR', 27, 3, 'চিরিরবন্দর', 30, NULL, 'C61536'),
(262, 'FULBARI', 27, 3, 'ফুলবাড়ী', 38, NULL, 'F416'),
(263, 'GHORAGHAT', 27, 3, 'ঘোড়াঘাট', 43, NULL, 'G623'),
(264, 'HAKIMPUR', 27, 3, 'হাকিমপুর', 47, NULL, 'H2516'),
(265, 'KAHAROLE', 27, 3, 'কাহারোল', 56, NULL, 'K640'),
(266, 'KHANSAMA', 27, 3, 'খান্‌সামা', 60, NULL, 'K525'),
(267, 'DINAJPUR SADAR', 27, 3, 'দিনাজপুর সদর', 64, NULL, 'D5216236'),
(268, 'NAWABGANJ', 27, 3, 'নবাবগঞ্জ', 69, NULL, 'N1252'),
(269, 'PARBATIPUR', 27, 3, 'পার্বতীপুর', 77, NULL, 'P61316'),
(270, 'ALFADANGA', 29, 3, 'আল্‌ফাডাঙ্গা', 3, NULL, 'A41352'),
(271, 'BHANGA', 29, 3, 'ভাংগা', 10, NULL, 'B520'),
(272, 'BOALMARI', 29, 3, 'বোয়ালমারী', 18, NULL, 'B456'),
(273, 'CHAR BHADRASAN', 29, 3, 'চর ভদ্রাশন', 21, NULL, 'C613625'),
(274, 'FARIDPUR SADAR', 29, 3, 'ফরিদপুর সদর', 47, NULL, 'F6316236'),
(275, 'MADHUKHALI', 29, 3, 'মধুখালী', 56, NULL, 'M324'),
(276, 'NAGARKANDA', 29, 3, 'নগরকান্দা', 62, NULL, 'N26253'),
(277, 'SADARPUR', 29, 3, 'সদরপুর', 84, NULL, 'S3616'),
(278, 'SALTHA', 29, 3, 'সালথা', 90, NULL, 'S430'),
(279, 'CHHAGALNAIYA', 30, 3, 'ছাগলনাইয়া', 14, NULL, 'C450'),
(280, 'DAGANBHUIYAN', 30, 3, 'দাগনভূঁঞা', 25, NULL, 'D2515'),
(281, 'FENI SADAR', 30, 3, 'ফেনী সদর', 29, NULL, 'F5236'),
(282, 'FULGAZI', 30, 3, 'ফুলগাজী', 41, NULL, 'F420'),
(283, 'PARSHURAM', 30, 3, 'পরশুরাম', 51, NULL, 'P6265'),
(284, 'SONAGAZI', 30, 3, 'সোনাগাজী', 94, NULL, 'S520'),
(285, 'FULCHHARI', 32, 3, 'ফুলছড়ি', 21, NULL, 'F426'),
(286, 'GAIBANDHA SADAR', 32, 3, 'গাইবান্ধা সদর', 24, NULL, 'G153236'),
(287, 'GOBINDAGANJ', 32, 3, 'গোবিন্দগঞ্জ', 30, NULL, 'G153252'),
(288, 'PALASHBARI', 32, 3, 'পলাশবাড়ী', 67, NULL, 'P4216'),
(289, 'SADULLAPUR', 32, 3, 'সাদুল্লাপুর', 82, NULL, 'S3416'),
(290, 'SAGHATA', 32, 3, 'সাঘাটা', 88, NULL, 'S300'),
(291, 'SUNDARGANJ', 32, 3, 'সুন্দরগঞ্জ', 91, NULL, 'S536252'),
(292, 'GAZIPUR SADAR', 33, 3, 'গাজীপুর সদর', 30, NULL, 'G16236'),
(293, 'KALIAKAIR', 33, 3, 'কালিয়াকৈর', 32, NULL, 'K426'),
(294, 'KALIGANJ', 33, 3, 'কালিগঞ্জ', 34, NULL, 'K4252'),
(295, 'KAPASIA', 33, 3, 'কাপাসিয়া', 36, NULL, 'K120'),
(296, 'SREEPUR', 33, 3, 'শ্রীপুর', 86, NULL, 'S616'),
(297, 'GOPALGANJ SADAR', 35, 3, 'গোপালগঞ্জ সদর', 32, NULL, 'G1425236'),
(298, 'KASHIANI', 35, 3, 'কাশিয়ানী', 43, NULL, 'K500'),
(299, 'KOTALIPARA', 35, 3, 'কোটালিপাড়া', 51, NULL, 'K3416'),
(300, 'MUKSUDPUR', 35, 3, 'মুকসুদপুর', 58, NULL, 'M2316'),
(301, 'TUNGIPARA', 35, 3, 'টংগীপাড়া', 91, NULL, 'T5216'),
(302, 'AJMIRIGANJ', 36, 3, 'আজমিরিগঞ্জ', 2, NULL, 'A256252'),
(303, 'BAHUBAL', 36, 3, 'বাহুবল', 5, NULL, 'B400'),
(304, 'BANIACHONG', 36, 3, 'বানিয়াচং', 11, NULL, 'B5252'),
(305, 'CHUNARUGHAT', 36, 3, 'চুনারুঘাট', 26, NULL, 'C5623'),
(306, 'HABIGANJ SADAR', 36, 3, 'হবিগঞ্জ সদর', 44, NULL, 'H125236'),
(307, 'LAKHAI', 36, 3, 'লাখাই', 68, NULL, 'L200'),
(308, 'MADHABPUR', 36, 3, 'মাধবপুর', 71, NULL, 'M316'),
(309, 'NABIGANJ', 36, 3, 'নবীগঞ্জ', 77, NULL, 'N1252'),
(310, 'AKKELPUR', 38, 3, 'আক্কেলপুর', 13, NULL, 'A2416'),
(311, 'JOYPURHAT SADAR', 38, 3, 'জয়পুরহাট সদর', 47, NULL, 'J163236'),
(312, 'KALAI', 38, 3, 'কালাই', 58, NULL, 'K400'),
(313, 'KHETLAL', 38, 3, 'ক্ষেতলাল', 61, NULL, 'K340'),
(314, 'PANCHBIBI', 38, 3, 'পাঁচবিবি', 74, NULL, 'P521'),
(315, 'BAKSHIGANJ', 39, 3, 'বকশিগঞ্জ', 7, NULL, 'B252'),
(316, 'DEWANGANJ', 39, 3, 'দেওয়ানগঞ্জ', 15, NULL, 'D5252'),
(317, 'ISLAMPUR', 39, 3, 'ইসলামপুর', 29, NULL, 'I24516'),
(318, 'JAMALPUR SADAR', 39, 3, 'জামালপুর সদর', 36, NULL, 'J5416236'),
(319, 'MADARGANJ', 39, 3, 'মাদারগঞ্জ', 58, NULL, 'M36252'),
(320, 'MELANDAHA', 39, 3, 'মেলান্দহ', 61, NULL, 'M453'),
(321, 'SARISHABARI', 39, 3, 'সরিষাবাড়ী', 85, NULL, 'S6216'),
(322, 'ABHAYNAGAR', 41, 3, 'অভয়নগর', 4, NULL, 'A1526'),
(323, 'BAGHER PARA', 41, 3, 'বাঘারপাড়া', 9, NULL, 'B2616'),
(324, 'CHAUGACHHA', 41, 3, 'চৌগাছা', 11, NULL, 'C000'),
(325, 'JHIKARGACHHA', 41, 3, 'ঝিকরগাছা', 23, NULL, 'J620'),
(326, 'KESHABPUR', 41, 3, 'কেশবপুর', 38, NULL, 'K160'),
(327, 'JESSOR SADAR', 41, 3, 'যশোর সদর', 47, NULL, 'J6236'),
(328, 'MANIRAMPUR', 41, 3, 'মনিরামপুর', 61, NULL, 'M6516'),
(329, 'SHARSHA', 41, 3, 'শার্শা', 90, NULL, 'S620'),
(330, 'JHALOKATI SADAR', 42, 3, 'ঝালকাঠী সদর', 40, NULL, 'J423236'),
(331, 'KANTHALIA', 42, 3, 'কাঠালিয়া', 43, NULL, 'K534'),
(332, 'NALCHITY', 42, 3, 'নলছিটি', 73, NULL, 'N423'),
(333, 'RAJAPUR', 42, 3, 'রাজাপুর', 84, NULL, 'R216'),
(334, 'HARINAKUNDA', 44, 3, 'হরিনাকুন্ডু', 14, NULL, 'H65253'),
(335, 'JHENAIDAH SADAR', 44, 3, 'ঝিনাইদহ সদর', 19, NULL, 'J53236'),
(336, 'KALIGANJ', 44, 3, 'কালীগঞ্জ', 33, NULL, 'K4252'),
(337, 'KOTCHANDPUR', 44, 3, 'কোটচাঁদপুর', 42, NULL, 'K325316'),
(338, 'MAHESHPUR', 44, 3, 'মহেশপুর', 71, NULL, 'M216'),
(339, 'SHAILKUPA', 44, 3, 'শৈলকুপা', 80, NULL, 'S421'),
(340, 'DIGHINALA', 46, 3, 'দিঘিনালা', 43, NULL, 'D254'),
(341, 'KHAGRACHHARI SADAR', 46, 3, 'খাগরাছড়ি সদর', 49, NULL, 'K626236'),
(342, 'LAKSHMICHHARI', 46, 3, 'লক্ষীছড়ি', 61, NULL, 'L2526'),
(343, 'MAHALCHHARI', 46, 3, 'মহালছড়ি', 65, NULL, 'M426'),
(344, 'MANIKCHHARI', 46, 3, 'মানিকছড়ি', 67, NULL, 'M260'),
(345, 'MATIRANGA', 46, 3, 'মাটিরাংগা', 70, NULL, 'M3652'),
(346, 'PANCHHARI', 46, 3, 'পানছড়ি', 77, NULL, 'P526'),
(347, 'RAMGARH', 46, 3, 'রামগর', 80, NULL, 'R526'),
(348, 'BATIAGHATA', 47, 3, 'বটিয়াঘাটা', 12, NULL, 'B323'),
(349, 'DACOPE', 47, 3, 'দাকোপ', 17, NULL, 'D210'),
(350, 'DAULATPUR', 47, 3, 'দৌলতপুর', 21, NULL, 'D4316'),
(351, 'DUMURIA', 47, 3, 'ডুমুরিয়া', 30, NULL, 'D560'),
(352, 'DIGHALIA', 47, 3, 'দিঘলিয়া', 40, NULL, 'D240'),
(353, 'KHALISHPUR', 47, 3, 'খালিসপুর', 45, NULL, 'K4216'),
(354, 'KHAN JAHAN ALI', 47, 3, 'খান জাহান আলী', 48, NULL, 'K5254'),
(355, 'KHULNA SADAR', 47, 3, 'খুলনা সদর', 51, NULL, 'K45236'),
(356, 'KOYRA', 47, 3, 'কয়রা', 53, NULL, 'K600'),
(357, 'PAIKGACHHA', 47, 3, 'পাইকগাছা', 64, NULL, 'P200'),
(358, 'PHULTALA', 47, 3, 'ফুলতলা', 69, NULL, 'P434'),
(359, 'RUPSA', 47, 3, 'রূপসা', 75, NULL, 'R120'),
(360, 'SONADANGA', 47, 3, 'সোনাডাঙ্গা', 85, NULL, 'S5352'),
(361, 'TEROKHADA', 47, 3, 'তেরখাদা', 94, NULL, 'T623'),
(362, 'AUSTAGRAM', 48, 3, 'অষ্টগ্রাম', 2, NULL, 'A23265'),
(363, 'BAJITPUR', 48, 3, 'বাজিতপুর', 6, NULL, 'B2316'),
(364, 'BHAIRAB', 48, 3, 'ভৈরব', 11, NULL, 'B610'),
(365, 'HOSSAINPUR', 48, 3, 'হোসেনপুর', 27, NULL, 'H2516'),
(366, 'ITNA', 48, 3, 'ইটনা', 33, NULL, 'I350'),
(367, 'KARIMGANJ', 48, 3, 'করিমগঞ্জ', 42, NULL, 'K65252'),
(368, 'KATIADI', 48, 3, 'কটিয়াদী', 45, NULL, 'K300'),
(369, 'KISHOREGANJ SADAR', 48, 3, 'কিশোরগঞ্জ সদর', 49, NULL, 'K625236'),
(370, 'KULIARCHAR', 48, 3, 'কুলিয়ারচর', 54, NULL, 'K4626'),
(371, 'MITHAMAIN', 48, 3, 'মিঠামইন', 59, NULL, 'M350'),
(372, 'NIKLI', 48, 3, 'নিক্‌লী', 76, NULL, 'N240'),
(373, 'PAKUNDIA', 48, 3, 'পাকুন্দিয়া', 79, NULL, 'P253'),
(374, 'TARAIL', 48, 3, 'তারাইল', 92, NULL, 'T640'),
(375, 'BHURUNGAMARI', 49, 3, 'ভুরুংগামারী', 6, NULL, 'B65256'),
(376, 'CHAR RAJIBPUR', 49, 3, 'রাজিবপুর', 8, NULL, 'C6216'),
(377, 'CHILMARI', 49, 3, 'চিলমারী', 9, NULL, 'C456'),
(378, 'PHULBARI', 49, 3, 'ফুলবাড়ী', 18, NULL, 'P416'),
(379, 'KURIGRAM SADAR', 49, 3, 'কুড়িগ্রাম সদর', 52, NULL, 'K6265236'),
(380, 'NAGESHWARI', 49, 3, 'নাগেশ্বরী', 61, NULL, 'N260'),
(381, 'RAJARHAT', 49, 3, 'রাজারহাট', 77, NULL, 'R263'),
(382, 'RAUMARI', 49, 3, 'রৌমারী', 79, NULL, 'R560'),
(383, 'ULIPUR', 49, 3, 'উলিপুর', 94, NULL, 'U416'),
(384, 'BHERAMARA', 50, 3, 'ভেড়ামারা', 15, NULL, 'B656'),
(385, 'DAULATPUR', 50, 3, 'দৌলতপুর', 39, NULL, 'D4316'),
(386, 'KHOKSA', 50, 3, 'খোক্‌সা', 63, NULL, 'K000'),
(387, 'KUMARKHALI', 50, 3, 'কুমারখালী', 71, NULL, 'K5624'),
(388, 'KUSHTIA SADAR', 50, 3, 'কুষ্টিয়া সদর', 79, NULL, 'K3236'),
(389, 'MIRPUR', 50, 3, 'মিরপুর', 94, NULL, 'M616'),
(390, 'KAMALNAGAR', 51, 3, 'কমলনগর', 33, NULL, 'K54526'),
(391, 'LAKSHMIPUR SADAR', 51, 3, 'লক্ষ্ণীপুর সদর', 43, NULL, 'L2516236'),
(392, 'ROYPUR', 51, 3, 'রায়পুর', 58, NULL, 'R160'),
(393, 'RAMGANJ', 51, 3, 'রামগঞ্জ', 65, NULL, 'R5252'),
(394, 'RAMGATI', 51, 3, 'রামগতী', 73, NULL, 'R523'),
(395, 'ADITMARI', 52, 3, 'আদিতমারী', 2, NULL, 'A356'),
(396, 'HATIBANDHA', 52, 3, 'হাতীবান্ধা', 33, NULL, 'H3153'),
(397, 'KALIGANJ', 52, 3, 'কালীগঞ্জ', 39, NULL, 'K4252'),
(398, 'LALMONIRHAT SADAR', 52, 3, 'লালমনিরহাট সদর', 55, NULL, 'L563236'),
(399, 'PATGRAM', 52, 3, 'পাটগ্রাম', 70, NULL, 'P3265'),
(400, 'KALKINI', 54, 3, 'কালকিনী', 40, NULL, 'K425'),
(401, 'MADARIPUR SADAR', 54, 3, 'মাদারিপুর সদর', 54, NULL, 'M3616236'),
(402, 'RAJOIR', 54, 3, 'রাজৈর', 80, NULL, 'R260'),
(403, 'SHIB CHAR', 54, 3, 'শিব্‌ চর', 87, NULL, 'S126'),
(404, 'MAGURA SADAR', 55, 3, 'মাগুরা সদর', 57, NULL, 'M26236'),
(405, 'MOHAMMADPUR', 55, 3, 'মোহাম্মদপুর', 66, NULL, 'M316'),
(406, 'SHALIKHA', 55, 3, 'শালিখা', 85, NULL, 'S420'),
(407, 'SREEPUR', 55, 3, 'শ্রীপুর', 95, NULL, 'S616'),
(408, 'DAULATPUR', 56, 3, 'দৌলতপুর', 10, NULL, 'D4316'),
(409, 'GHIOR', 56, 3, 'ঘিওর', 22, NULL, 'G600'),
(410, 'HARIRAMPUR', 56, 3, 'হরিরামপুর', 28, NULL, 'H6516'),
(411, 'MANIKGANJ SADAR', 56, 3, 'মানিকগঞ্জ সদর', 46, NULL, 'M25236'),
(412, 'SATURIA', 56, 3, 'সাটুরিয়া', 70, NULL, 'S360'),
(413, 'SHIBALAYA', 56, 3, 'শিবালয়', 78, NULL, 'S140'),
(414, 'SINGAIR', 56, 3, 'শিংগাইর', 82, NULL, 'S526'),
(415, 'GANGNI', 57, 3, 'গাংনী', 47, NULL, 'G525'),
(416, 'MUJIB NAGAR', 57, 3, 'মজিব নগর', 60, NULL, 'M21526'),
(417, 'MEHERPUR SADAR', 57, 3, 'মেহেরপুর সদর', 87, NULL, 'M616236'),
(418, 'BARLEKHA', 58, 3, 'বড়লেখা', 14, NULL, 'B642'),
(419, 'JURI', 58, 3, 'জুড়ী', 35, NULL, 'J600'),
(420, 'KAMALGANJ', 58, 3, 'কমলগঞ্জ', 56, NULL, 'K54252'),
(421, 'KULAURA', 58, 3, 'কুলাউড়া', 65, NULL, 'K460'),
(422, 'MAULVIBAZAR SADAR', 58, 3, 'মৌলভীবাজার সদর', 74, NULL, 'M4126236'),
(423, 'RAJNAGAR', 58, 3, 'রাজনগর', 80, NULL, 'R2526'),
(424, 'SREEMANGAL', 58, 3, 'শ্রীমঙ্গল', 83, NULL, 'S6524'),
(425, 'GAZARIA', 59, 3, 'গজারিয়া', 24, NULL, 'G600'),
(426, 'LOHAJANG', 59, 3, 'লৌহজং', 44, NULL, 'L252'),
(427, 'MUNSHIGANJ SADAR', 59, 3, 'মুন্সীগঞ্জ সদর', 56, NULL, 'M25236'),
(428, 'SERAJDIKHAN', 59, 3, 'সিরাজদিখান', 74, NULL, 'S62325'),
(429, 'SREENAGAR', 59, 3, 'শ্রীনগর', 84, NULL, 'S6526'),
(430, 'TONGIBARI', 59, 3, 'টুঙ্গিবাড়ী', 94, NULL, 'T5216'),
(431, 'BHALUKA', 61, 3, 'ভালুকা', 13, NULL, 'B420'),
(432, 'DHOBAURA', 61, 3, 'ধোবাউড়া', 16, NULL, 'D160'),
(433, 'FULBARIA', 61, 3, 'ফুলবাড়ীয়া', 20, NULL, 'F416'),
(434, 'GAFFARGAON', 61, 3, 'গফরগাঁও', 22, NULL, 'G1625'),
(435, 'GAURIPUR', 61, 3, 'গৌরীপুর', 23, NULL, 'G616'),
(436, 'HALUAGHAT', 61, 3, 'হালুয়াঘাট', 24, NULL, 'H423'),
(437, 'ISHWARGANJ', 61, 3, 'ঈশ্বরগঞ্জ', 31, NULL, 'I26252'),
(438, 'MYMENSINGH SADAR', 61, 3, 'ময়মনসিংহ সদর', 52, NULL, 'M25236'),
(439, 'MUKTAGACHHA', 61, 3, 'মুক্তাগাছা', 65, NULL, 'M232'),
(440, 'NANDAIL', 61, 3, 'নান্দাইল', 72, NULL, 'N340'),
(441, 'PHULPUR', 61, 3, 'ফুলপুর', 81, NULL, 'P416'),
(442, 'TRISHAL', 61, 3, 'ত্রিশাল', 94, NULL, 'T624'),
(443, 'ATRAI', 64, 3, 'আত্রাই', 3, NULL, 'A360'),
(444, 'BADALGACHHI', 64, 3, 'বদলগাছী', 6, NULL, 'B342'),
(445, 'DHAMOIRHAT', 64, 3, 'ধামুইরহাট', 28, NULL, 'D563'),
(446, 'MANDA', 64, 3, 'মান্দা', 47, NULL, 'M300'),
(447, 'MAHADEBPUR', 64, 3, 'মহাদেবপুর', 50, NULL, 'M316'),
(448, 'NAOGAON SADAR', 64, 3, 'নওগাঁ সদর', 60, NULL, 'N25236'),
(449, 'NIAMATPUR', 64, 3, 'নিয়ামতপুর', 69, NULL, 'N316'),
(450, 'PATNITALA', 64, 3, 'পত্নীতলা', 75, NULL, 'P3534'),
(451, 'PORSHA', 64, 3, 'পোরশা', 79, NULL, 'P620'),
(452, 'RANINAGAR', 64, 3, 'রাণীনগর', 85, NULL, 'R526'),
(453, 'SAPAHAR', 64, 3, 'সাপাহার', 86, NULL, 'S160'),
(454, 'KALIA', 65, 3, 'কালিয়া', 28, NULL, 'K400'),
(455, 'LOHAGARA', 65, 3, 'লোহাগড়া', 52, NULL, 'L260'),
(456, 'NARAIL SADAR', 65, 3, 'নড়াইল সদর', 76, NULL, 'N64236'),
(457, 'ARAIHAZAR', 67, 3, 'আড়াইহাজার', 2, NULL, 'A626'),
(458, 'SONARGAON', 67, 3, 'সোনারগাঁও', 4, NULL, 'S5625'),
(459, 'BANDAR', 67, 3, 'বন্দর', 6, NULL, 'B536'),
(460, 'NARAYANGANJ SADAR', 67, 3, 'নারায়নগঞ্জ সদর', 58, NULL, 'N6525236'),
(461, 'RUPGANJ', 67, 3, 'রূপগঞ্জ', 68, NULL, 'R1252'),
(462, 'BELABO', 68, 3, 'বেলাবো', 7, NULL, 'B410'),
(463, 'MANOHARDI', 68, 3, 'মনোহরদী', 52, NULL, 'M630'),
(464, 'NARSINGDI SADAR', 68, 3, 'নরসিংদী সদর', 60, NULL, 'N62523236'),
(465, 'PALASH', 68, 3, 'পলাশ', 63, NULL, 'P420'),
(466, 'ROYPURA', 68, 3, 'রায়পুরা', 64, NULL, 'R160'),
(467, 'SHIBPUR', 68, 3, 'শিবপুর', 76, NULL, 'S160'),
(468, 'BAGATIPARA', 69, 3, 'বাগাতিপাড়া', 9, NULL, 'B2316'),
(469, 'BARAIGRAM', 69, 3, 'বড়ইগ্রাম', 15, NULL, 'B6265'),
(470, 'GURUDASPUR', 69, 3, 'গুরুদাসপুর', 41, NULL, 'G63216'),
(471, 'LALPUR', 69, 3, 'লালপুর', 44, NULL, 'L160'),
(472, 'NALDANGA', 69, 3, 'নলডাঙ্গা', 55, NULL, 'N4352'),
(473, 'NATORE SADAR', 69, 3, 'নাটোর সদর', 63, NULL, 'N36236'),
(474, 'SINGRA', 69, 3, 'সিংড়া', 91, NULL, 'S526'),
(475, 'BHOLAHAT', 70, 3, 'ভোলাহাট', 18, NULL, 'B430'),
(476, 'GOMASTAPUR', 70, 3, 'গোমস্তাপুর', 37, NULL, 'G52316'),
(477, 'NACHOLE', 70, 3, 'নাচোল', 56, NULL, 'N240'),
(478, 'CHAPAI NABABGANJ SADAR', 70, 3, 'চাঁপাই নবাবগঞ্জ সদর', 66, NULL, 'C15125236'),
(479, 'SHIBGANJ', 70, 3, 'শিবগঞ্জ', 88, NULL, 'S1252'),
(480, 'ATPARA', 72, 3, 'আটপাড়া', 4, NULL, 'A316'),
(481, 'BARHATTA', 72, 3, 'বারহাট্টা', 9, NULL, 'B630'),
(482, 'DURGAPUR', 72, 3, 'দূর্গাপুর', 18, NULL, 'D6216'),
(483, 'KHALIAJURI', 72, 3, 'খলিয়াজুরী', 38, NULL, 'K426'),
(484, 'KALMAKANDA', 72, 3, 'কলমাকান্দা', 40, NULL, 'K45253'),
(485, 'KENDUA', 72, 3, 'কেন্দুয়া', 47, NULL, 'K530'),
(486, 'MADAN', 72, 3, 'মদন ', 56, NULL, 'M350'),
(487, 'MOHANGANJ', 72, 3, 'মোহনগঞ্জ', 63, NULL, 'M252'),
(488, 'NETROKONA SADAR', 72, 3, 'নেত্রকোনা সদর', 74, NULL, 'N3625236'),
(489, 'PURBADHALA', 72, 3, 'পূর্বধলা', 83, NULL, 'P6134'),
(490, 'DIMLA', 73, 3, 'ডিমলা', 12, NULL, 'D540'),
(491, 'DOMAR', 73, 3, 'ডোমার', 15, NULL, 'D560'),
(492, 'JALDHAKA', 73, 3, 'জলঢাকা', 36, NULL, 'J432'),
(493, 'KISHOREGANJ', 73, 3, 'কিশোরগঞ্জ', 45, NULL, 'K6252'),
(494, 'NILPHAMARI SADAR', 73, 3, 'নীলফামারী সদর', 64, NULL, 'N4156236'),
(495, 'SAIDPUR', 73, 3, 'সৈয়দপুর', 85, NULL, 'S316'),
(496, 'BEGUMGANJ', 75, 3, 'বেগমগঞ্জ', 7, NULL, 'B25252'),
(497, 'CHATKHIL', 75, 3, 'চাট্‌খিল', 10, NULL, 'C324'),
(498, 'COMPANIGANJ', 75, 3, 'কোম্পানীগঞ্জ', 21, NULL, 'C515252'),
(499, 'HATIYA', 75, 3, 'হাতিয়া', 36, NULL, 'H300'),
(500, 'KABIRHAT', 75, 3, 'কবিরহাট', 47, NULL, 'K163'),
(501, 'SENBAGH', 75, 3, 'সেনবাগ', 80, NULL, 'S512'),
(502, 'SONAIMURI', 75, 3, 'সোনাইমুড়ি', 83, NULL, 'S560'),
(503, 'SUBARNACHAR', 75, 3, 'সুবর্ণচর', 85, NULL, 'S16526'),
(504, 'NOAKHALI SADAR', 75, 3, 'নোয়াখালী সদর', 87, NULL, 'N24236'),
(505, 'ATGHARIA', 76, 3, 'আটঘরিয়া', 5, NULL, 'A326'),
(506, 'BERA', 76, 3, 'বেড়া', 16, NULL, 'B600'),
(507, 'BHANGURA', 76, 3, 'ভাংগুড়া', 19, NULL, 'B526'),
(508, 'CHATMOHAR', 76, 3, 'চাট্‌মোহর', 22, NULL, 'C356'),
(509, 'FARIDPUR', 76, 3, 'ফরিদপুর', 33, NULL, 'F6316'),
(510, 'ISHWARDI', 76, 3, 'ঈশ্বরদী', 39, NULL, 'I263'),
(511, 'PABNA SADAR', 76, 3, 'পাবনা সদর', 55, NULL, 'P5236'),
(512, 'SANTHIA', 76, 3, 'সাঁথিয়া', 72, NULL, 'S530'),
(513, 'SUJANAGAR', 76, 3, 'সুজানগর', 83, NULL, 'S526'),
(514, 'ATWARI', 77, 3, 'আটোয়ারী', 4, NULL, 'A360'),
(515, 'BODA', 77, 3, 'বোদা', 25, NULL, 'B300'),
(516, 'DEBIGANJ', 77, 3, 'দেবীগঞ্জ', 34, NULL, 'D1252'),
(517, 'PANCHAGARH SADAR', 77, 3, 'পঞ্চগড় সদর', 73, NULL, 'P526236'),
(518, 'TENTULIA', 77, 3, 'তেঁতুলিয়া', 90, NULL, 'T534'),
(519, 'BAUPHAL', 78, 3, 'বাউফল', 38, NULL, 'B400'),
(520, 'DASHMINA', 78, 3, 'দশমিনা', 52, NULL, 'D250'),
(521, 'DUMKI', 78, 3, 'দুম্‌কী', 55, NULL, 'D520'),
(522, 'GALACHIPA', 78, 3, 'গলাচিপা', 57, NULL, 'G421'),
(523, 'KALA PARA', 78, 3, 'কলাপাড়া', 66, NULL, 'K416'),
(524, 'MIRZAGANJ', 78, 3, 'মির্জাগঞ্জ', 76, NULL, 'M6252'),
(525, 'PATUAKHALI SADAR', 78, 3, 'পটুয়াখালী সদর', 95, NULL, 'P324236'),
(526, 'RANGABALI', 78, 3, 'রাংগাবালী', 97, NULL, 'R5214'),
(527, 'BHANDARIA', 79, 3, 'ভান্ডারিয়া', 14, NULL, 'B536'),
(528, 'KAWKHALI', 79, 3, 'কাউখালী', 47, NULL, 'K400'),
(529, 'MATHBARIA', 79, 3, 'মঠবাড়ীয়া', 58, NULL, 'M316'),
(530, 'NAZIRPUR UPAZILA', 79, 3, 'নাজিরপুর', 76, NULL, 'N2616124'),
(531, 'PIROJPUR SADAR', 79, 3, 'পিরোজপুর সদর', 80, NULL, 'P6216236'),
(532, 'NESARABAD (SWARUPKATI)', 79, 3, 'নেছারাবাদ(স্বরূপকাঠী)', 87, NULL, 'N261326123'),
(533, 'ZIANAGAR', 79, 3, 'জিয়ানগর', 90, NULL, 'Z526'),
(534, 'BAGHA', 81, 3, 'বাঘা', 10, NULL, 'B200'),
(535, 'BAGHMARA', 81, 3, 'বাগমারা', 12, NULL, 'B256'),
(536, 'BOALIA', 81, 3, 'বোয়ালিয়া', 22, NULL, 'B400'),
(537, 'CHARGHAT', 81, 3, 'চারঘাট', 25, NULL, 'C623'),
(538, 'DURGAPUR', 81, 3, 'দূর্গাপুর', 31, NULL, 'D6216'),
(539, 'GODAGARI', 81, 3, 'গোদাগাড়ী', 34, NULL, 'G326'),
(540, 'MATIHAR', 81, 3, 'মতিহার', 40, NULL, 'M360'),
(541, 'MOHANPUR', 81, 3, 'মোহনপুর', 53, NULL, 'M160'),
(542, 'PABA', 81, 3, 'পবা', 72, NULL, 'P000'),
(543, 'PUTHIA', 81, 3, 'পুঠিয়া', 82, NULL, 'P300'),
(544, 'RAJPARA', 81, 3, 'রাজপাড়া', 85, NULL, 'R216'),
(545, 'SHAH MAKHDUM', 81, 3, 'শাহ্‌ মখদুম', 90, NULL, 'S5235'),
(546, 'TANORE', 81, 3, 'তানোর', 94, NULL, 'T560'),
(547, 'BALIAKANDI', 82, 3, 'বালিয়াকান্দি', 7, NULL, 'B4253'),
(548, 'GOALANDA', 82, 3, 'গোয়ালন্দ', 29, NULL, 'G453'),
(549, 'KALUKHALI', 82, 3, 'কালুখালী', 47, NULL, 'K424'),
(550, 'PANGSHA', 82, 3, 'পাংশা', 73, NULL, 'P520'),
(551, 'RAJBARI SADAR', 82, 3, 'রাজবাড়ী সদর', 76, NULL, 'R216236'),
(552, 'BAGHAICHHARI', 84, 3, 'বাঘাইছড়ি', 7, NULL, 'B260'),
(553, 'BARKAL', 84, 3, 'বরকল', 21, NULL, 'B624'),
(554, 'KAWKHALI', 84, 3, 'কাউখালী', 25, NULL, 'K400'),
(555, 'BELAI CHHARI', 84, 3, 'বিলাইছড়ি', 29, NULL, 'B426'),
(556, 'KAPTAI', 84, 3, 'কাপ্তাই', 36, NULL, 'K130'),
(557, 'JURAI CHHARI', 84, 3, 'জুরাইছড়ি', 47, NULL, 'J626'),
(558, 'LANGADU', 84, 3, 'লংগদু', 58, NULL, 'L523'),
(559, 'NANIARCHAR', 84, 3, 'নানিয়ারচর', 75, NULL, 'N626'),
(560, 'RAJASTHALI', 84, 3, 'রাজস্থালী', 78, NULL, 'R234'),
(561, 'RANGAMATI SADAR', 84, 3, 'রাঙ্গামাটি সদর', 87, NULL, 'R5253236'),
(562, 'BADARGANJ', 85, 3, 'বদরগঞ্জ', 3, NULL, 'B36252'),
(563, 'GANGACHARA', 85, 3, 'গংগাচড়া', 27, NULL, 'G526'),
(564, 'KAUNIA', 85, 3, 'কাউনিয়া', 42, NULL, 'K500'),
(565, 'RANGPUR SADAR', 85, 3, 'রংপুর সদর', 49, NULL, 'R5216236'),
(566, 'MITHA PUKUR', 85, 3, 'মিঠাপুকুর', 58, NULL, 'M3126'),
(567, 'PIRGACHHA', 85, 3, 'পীরগাছা', 73, NULL, 'P620'),
(568, 'PIRGANJ', 85, 3, 'পীরগঞ্জ', 76, NULL, 'P6252'),
(569, 'TARAGANJ', 85, 3, 'তারাগঞ্জ', 92, NULL, 'T6252'),
(570, 'BHEDARGANJ', 86, 3, 'ভেদরগঞ্জ', 14, NULL, 'B36252'),
(571, 'DAMUDYA', 86, 3, 'ডামুড্যা', 25, NULL, 'D530'),
(572, 'GOSAIRHAT', 86, 3, 'গোসাইরহাট', 36, NULL, 'G630'),
(573, 'NARIA', 86, 3, 'নড়িয়া', 65, NULL, 'N600'),
(574, 'SHARIATPUR SADAR', 86, 3, 'শরিয়তপুর সদর', 69, NULL, 'S6316236'),
(575, 'ZANJIRA', 86, 3, 'জাজিরা', 94, NULL, 'Z526'),
(576, 'ASSASUNI', 87, 3, 'আশাশুনি', 4, NULL, 'A250'),
(577, 'DEBHATA', 87, 3, 'দেবহাটা', 25, NULL, 'D130'),
(578, 'KALAROA', 87, 3, 'কলারোয়া', 43, NULL, 'K460'),
(579, 'KALIGANJ', 87, 3, 'কালিগঞ্জ', 47, NULL, 'K4252'),
(580, 'SATKHIRA SADAR', 87, 3, 'সাতক্ষিরা সদর', 82, NULL, 'S326236'),
(581, 'SHYAMNAGAR', 87, 3, 'শ্যামনগর', 86, NULL, 'S526'),
(582, 'TALA', 87, 3, 'তালা', 90, NULL, 'T400'),
(583, 'BELKUCHI', 88, 3, 'বেলকুচি', 11, NULL, 'B420'),
(584, 'CHAUHALI', 88, 3, 'চৌহালী', 27, NULL, 'C400'),
(585, 'KAMARKHANDA', 88, 3, 'কামারখন্দ', 44, NULL, 'K56253'),
(586, 'KAZIPUR', 88, 3, 'কাজীপুর', 50, NULL, 'K160'),
(587, 'ROYGANJ', 88, 3, 'রায়গঞ্জ', 61, NULL, 'R252'),
(588, 'SHAHJADPUR', 88, 3, 'শাহাজাদপুর', 67, NULL, 'S316'),
(589, 'SIRAJGANJ SADAR', 88, 3, 'সিরাজগঞ্জ সদর', 78, NULL, 'S625236'),
(590, 'TARASH', 88, 3, 'তাড়াশ', 89, NULL, 'T620'),
(591, 'ULLAH PARA', 88, 3, 'উল্লা পাড়া', 94, NULL, 'U416'),
(592, 'JHENAIGATI', 89, 3, 'ঝিনাইঘাতি', 37, NULL, 'J523'),
(593, 'NAKLA', 89, 3, 'নকলা', 67, NULL, 'N240'),
(594, 'NALITABARI', 89, 3, 'নালিতাবাড়ী', 70, NULL, 'N4316'),
(595, 'SHERPUR SADAR', 89, 3, 'শেরপুর সদর', 88, NULL, 'S616236'),
(596, 'SREEBARDI', 89, 3, 'শ্রীবর্দি', 90, NULL, 'S6163'),
(597, 'BISHWAMBARPUR', 90, 3, 'বিশ্বম্ভরপুর', 18, NULL, 'B251616'),
(598, 'CHHATAK', 90, 3, 'ছাতক', 23, NULL, 'C320'),
(599, 'DAKSHIN SUNAMGANJ', 90, 3, 'দক্ষিন সুনামগঞ্জ', 27, NULL, 'D2525252'),
(600, 'DERAI', 90, 3, 'দিরাই', 29, NULL, 'D600'),
(601, 'DHARAMPASHA', 90, 3, 'ধর্মপাশা', 32, NULL, 'D6512'),
(602, 'DOWARABAZAR', 90, 3, 'দোয়ারাবাজার', 33, NULL, 'D6126'),
(603, 'JAGANNATHPUR', 90, 3, 'জগন্নাথপুর', 47, NULL, 'J5316'),
(604, 'JAMALGANJ', 90, 3, 'জামালগঞ্জ', 50, NULL, 'J54252'),
(605, 'SULLA', 90, 3, 'শুল্লা', 86, NULL, 'S400'),
(606, 'SUNAMGANJ SADAR', 90, 3, 'সুনামগঞ্জ সদর', 89, NULL, 'S525236'),
(607, 'TAHIRPUR', 90, 3, 'তাহিরপুর', 92, NULL, 'T616'),
(608, 'BALAGANJ', 91, 3, 'বালাগঞ্জ', 8, NULL, 'B4252'),
(609, 'BEANI BAZAR', 91, 3, 'বিয়ানী বাজার', 17, NULL, 'B5126'),
(610, 'BISHWANATH', 91, 3, 'বিশ্বনাথ', 20, NULL, 'B253'),
(611, 'COMPANIGANJ', 91, 3, 'কোম্পানীগঞ্জ', 27, NULL, 'C515252'),
(612, 'DAKSHIN SURMA', 91, 3, 'দক্ষিণ সুরমা', 31, NULL, 'D25265'),
(613, 'FENCHUGANJ', 91, 3, 'ফেঞ্চুগঞ্জ', 35, NULL, 'F5252'),
(614, 'GOLAPGANJ', 91, 3, 'গোলাপগঞ্জ', 38, NULL, 'G41252'),
(615, 'GOWAINGHAT', 91, 3, 'গোয়াইনঘাট', 41, NULL, 'G523'),
(616, 'JAINTIAPUR', 91, 3, 'জৈন্তাপুর', 53, NULL, 'J5316'),
(617, 'KANAIGHAT', 91, 3, 'কানাইঘাট', 59, NULL, 'K523'),
(618, 'SYLHET SADAR', 91, 3, 'সিলেট সদর', 62, NULL, 'S43236'),
(619, 'ZAKIGANJ', 91, 3, 'জকিগঞ্জ', 94, NULL, 'Z520'),
(620, 'BASAIL', 93, 3, 'বাসাইল', 9, NULL, 'B240'),
(621, 'BHUAPUR', 93, 3, 'ভূঞাপুর', 19, NULL, 'B600'),
(622, 'DELDUAR', 93, 3, 'দেলদুয়ার', 23, NULL, 'D436'),
(623, 'DHANBARI', 93, 3, 'ধনবাড়ী', 25, NULL, 'D516'),
(624, 'GHATAIL', 93, 3, 'ঘাটাইল', 28, NULL, 'G340'),
(625, 'GOPALPUR', 93, 3, 'গোপালপুর', 38, NULL, 'G1416'),
(626, 'KALIHATI', 93, 3, 'কালিহাতি', 47, NULL, 'K430'),
(627, 'MADHUPUR', 93, 3, 'মধুপুর', 57, NULL, 'M316'),
(628, 'MIRZAPUR', 93, 3, 'মির্জাপুর', 66, NULL, 'M6216'),
(629, 'NAGARPUR', 93, 3, 'নাগরপুর', 76, NULL, 'N2616'),
(630, 'SAKHIPUR', 93, 3, 'সখিপুর', 85, NULL, 'S160'),
(631, 'TANGAIL SADAR', 93, 3, 'টাঙ্গাইল সদর', 95, NULL, 'T524236'),
(632, 'BALIADANGI', 94, 3, 'বালিয়াডাংগী', 8, NULL, 'B4352'),
(633, 'HARIPUR', 94, 3, 'হরিপুর', 51, NULL, 'H616'),
(634, 'PIRGANJ', 94, 3, 'পীরগঞ্জ', 82, NULL, 'P6252'),
(635, 'RANISANKAIL', 94, 3, 'রানীশংকাইল', 86, NULL, 'R52524'),
(636, 'THAKURGAON SADAR', 94, 3, 'ঠাকুরগাঁও সদর', 94, NULL, 'T2625236'),
(638, 'Wari', 26, 3, 'ওয়ারী', NULL, NULL, 'W600'),
(640, 'Sher-e-Bangla Nagor ', 26, 3, 'শেরেই-বাংলা নগর', NULL, NULL, 'S61524526'),
(644, 'Darusalam', 26, 3, 'দারুস সালাম', NULL, NULL, 'D6245'),
(646, 'Osmani Nagar', 91, 3, 'ওসমানী নগর', NULL, NULL, 'O2526'),
(650, 'shibganj', 70, 3, 'শিবগঞ্জ', NULL, NULL, 'S1252'),
(651, 'ck', 30, 3, 'ck', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
`id` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `value2` varchar(45) DEFAULT NULL,
  `value3` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `is_locked` int(15) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`id`, `caption`, `value`, `details`, `value2`, `value3`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_locked`) VALUES
(1, 'REG_FEES', '2000', 'Pre-Registration Fees for Govornment Pilgrim', '1515', '15152', '2016-02-29 07:08:01', 1, '2016-02-29 07:03:48', 62, 0),
(2, 'REG_DEPOSIT', '28000', 'Pre-Registration Deposit for Govornment Pilgrim', NULL, NULL, '2016-02-05 06:14:47', 1, '2016-01-30 01:43:29', 1, 0),
(3, 'REG_FEES_PRIVATE', '2000', 'REG_FEES_PRIVATE', NULL, NULL, '2016-01-30 01:53:59', 1, '2016-01-30 01:43:29', 1, 0),
(4, 'REG_DEPOSIT_PRIVATE', '38752', 'REG_DEPOSIT_PRIVATE', '', '', '2016-02-28 04:24:21', 1, '2016-02-28 04:20:21', 62, 0),
(10, 'PRE_REG_GOVT_PERIOD', '2016-01-01', '', '2016-03-31', '6000', '2016-01-30 01:54:00', 1, '2016-01-30 01:43:29', 1, 0),
(11, 'PRE_REG_PRIVATE_PERIOD', '2016-01-01', '', '2016-03-15', '125000', '2016-01-30 01:54:02', 1, '2016-01-30 01:43:29', 1, 0),
(12, 'APP_MODE', '80', '', NULL, NULL, '2016-02-04 13:48:00', 1, '2016-02-04 13:48:00', 0, 1),
(13, 'HTTP_AUTH', '0', '', NULL, NULL, '2016-02-04 13:48:00', 1, '2016-02-04 13:48:00', 0, 1),
(14, 'DATABASE_MODE', 'UAT', '', NULL, NULL, '2016-02-04 13:47:05', 1, '2016-01-30 01:43:29', 0, 1),
(15, 'NID_MODE', '1', 'verification_flag == 0 // Just inserted\r\n1=Directly calling nid\r\nverification_flag == -1 // Sent to EC Server\r\nverification_flag == 1 // Valid NID\r\nverification_flag == -9 // Invalid NID', NULL, NULL, '2016-02-04 13:48:00', 1, '2016-02-04 13:48:00', 0, 1),
(16, 'HTTP_TYPE', '0', '', NULL, NULL, '2016-02-04 13:48:00', 0, '2016-02-04 13:48:00', 0, 1),
(17, 'PASSPORT_REQUIRED', '0', '1=Passport information required\r\n0=Passport information not required', NULL, NULL, '2016-02-24 01:34:13', 1, '2016-02-04 13:48:00', 1, 0),
(19, 'UDC_PASS', '789654231', 'The field contains the default password of UDC', NULL, NULL, '2016-02-04 13:48:00', 1, '2016-02-04 13:48:00', 1, 1),
(20, 'IMAGE_SIZE', '140x170~10', '[File Format: *.jpg / *.png, File size({$filesize})KB]', '3-25', '', '2016-03-04 23:36:18', 1, '0000-00-00 00:00:00', 1, 0),
(21, 'DOC_IMAGE_SIZE', '1000x1250~10', '[File Format: *.pdf , File size({$filesize})KB]', '75-125', NULL, '2016-03-05 03:20:06', 1, '0000-00-00 00:00:00', 1, 0),
(22, 'DRAFT_PILGRIM_DATA_VALIDITY', '4', '', NULL, NULL, '2016-02-08 03:19:56', 1, '0000-00-00 00:00:00', 1, 0),
(23, 'SYSTEM_MODE', '1', '0= online,1=limited,2=sysadmin,3=offline', NULL, NULL, '2016-02-10 09:10:30', 1, '2016-02-04 13:48:00', 0, 1),
(24, 'MAX_PILGRIM_PER_VOUCHER', '15', 'maximum pilgrim per payment voucher', NULL, NULL, '2016-02-24 02:00:39', 1, '0000-00-00 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `country_info`
--

CREATE TABLE IF NOT EXISTS `country_info` (
`id` int(11) NOT NULL,
  `country_code` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `nicename` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `nationality` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso3` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL,
  `country_priority` tinyint(4) DEFAULT '2',
  `country_status` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=240 ;

--
-- Dumping data for table `country_info`
--

INSERT INTO `country_info` (`id`, `country_code`, `iso`, `name`, `nicename`, `nationality`, `iso3`, `numcode`, `phonecode`, `country_priority`, `country_status`) VALUES
(1, '004', 'AF', 'AFGHANISTAN', 'Afghanistan', 'Afghan', 'AFG', 4, 93, 2, 'Yes'),
(2, '006', 'AL', 'ALBANIA', 'Albania', 'Albanian', 'ALB', 8, 355, 2, 'Yes'),
(3, '007', 'DZ', 'ALGERIA', 'Algeria', 'Algerian', 'DZA', 12, 213, 2, 'Yes'),
(4, '256', 'AS', 'AMERICAN SAMOA', 'American Samoa', 'Lao', 'ASM', 16, 1684, 2, 'Yes'),
(5, '008', 'AD', 'ANDORRA', 'Andorra', 'Andorran', 'AND', 20, 376, 2, 'Yes'),
(6, '009', 'AO', 'ANGOLA', 'Angola', 'Angolan', 'AGO', 24, 244, 2, 'Yes'),
(7, '010', 'AI', 'ANGUILLA', 'Anguilla', 'Antiguans', 'AIA', 660, 1264, 2, 'Yes'),
(8, '231', 'AQ', 'ANTARCTICA', 'Antarctica', 'ANTARCTICA', NULL, NULL, 0, 2, 'Yes'),
(9, '011', 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', '', 'ATG', 28, 1268, 2, 'Yes'),
(10, '014', 'AR', 'ARGENTINA', 'Argentina', 'Argentinean', 'ARG', 32, 54, 2, 'Yes'),
(11, '155', 'AM', 'ARMENIA', 'Armenia', 'Armenian', 'ARM', 51, 374, 2, 'Yes'),
(12, '015', 'AW', 'ARUBA', 'Aruba', 'Aruban', 'ABW', 533, 297, 2, 'Yes'),
(13, '017', 'AU', 'AUSTRALIA', 'Australia', 'Australian', 'AUS', 36, 61, 2, 'Yes'),
(14, '018', 'AT', 'AUSTRIA', 'Austria', 'Austrian', 'AUT', 40, 43, 2, 'Yes'),
(15, '156', 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'Azerbaijani', 'AZE', 31, 994, 2, 'Yes'),
(16, '020', 'BS', 'BAHAMAS', 'Bahamas', 'Bahamian', 'BHS', 44, 1242, 2, 'Yes'),
(17, '021', 'BH', 'BAHRAIN', 'Bahrain', 'Bahraini', 'BHR', 48, 973, 2, 'Yes'),
(18, '001', 'BD', 'BANGLADESH', 'Bangladesh', 'Bangladeshi', 'BGD', 50, 880, 2, 'Yes'),
(19, '022', 'BB', 'BARBADOS', 'Barbados', 'Barbadian', 'BRB', 52, 1246, 2, 'Yes'),
(20, '157', 'BY', 'BELARUS', 'Belarus', 'Belarusian', 'BLR', 112, 375, 2, 'Yes'),
(21, '023', 'BE', 'BELGIUM', 'Belgium', 'Belgian', 'BEL', 56, 32, 2, 'Yes'),
(22, '062', 'BZ', 'BELIZE', 'Belize', 'Netherland', 'BLZ', 84, 501, 2, 'Yes'),
(23, '232', 'BJ', 'BENIN', 'Benin', 'Beninese', 'BEN', 204, 229, 2, 'Yes'),
(24, '025', 'BM', 'BERMUDA', 'Bermuda', 'Bermuda', 'BMU', 60, 1441, 2, 'Yes'),
(25, '026', 'BT', 'BHUTAN', 'Bhutan', 'Bhutanese', 'BTN', 64, 975, 2, 'Yes'),
(26, '060', 'BO', 'BOLIVIA', 'Bolivia', '', 'BOL', 68, 591, 2, 'Yes'),
(27, '027', 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'Bolivian', 'BIH', 70, 387, 2, 'Yes'),
(28, '233', 'BW', 'BOTSWANA', 'Botswana', '', 'BWA', 72, 267, 2, 'Yes'),
(29, '154', 'BV', 'BOUVET ISLAND', 'Bouvet Island', 'Bosnian', NULL, NULL, 0, 2, 'Yes'),
(30, '028', 'BR', 'BRAZIL', 'Brazil', 'Botswanan', 'BRA', 76, 55, 2, 'Yes'),
(31, '249', 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', '', NULL, NULL, 246, 2, 'Yes'),
(32, '029', 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'Brazilian', 'BRN', 96, 673, 2, 'Yes'),
(33, '030', 'BG', 'BULGARIA', 'Bulgaria', 'Bruneian', 'BGR', 100, 359, 2, 'Yes'),
(34, '031', 'BF', 'BURKINA FASO', 'Burkina Faso', 'Bulgarian', 'BFA', 854, 226, 2, 'Yes'),
(35, '032', 'BI', 'BURUNDI', 'Burundi', '', 'BDI', 108, 257, 2, 'Yes'),
(36, '033', 'KH', 'CAMBODIA', 'Cambodia', 'Burundian', 'KHM', 116, 855, 2, 'Yes'),
(37, '159', 'CM', 'CAMEROON', 'Cameroon', 'Cambodian', 'CMR', 120, 237, 2, 'Yes'),
(38, '160', 'CA', 'CANADA', 'Canada', 'Cameroonian', 'CAN', 124, 1, 2, 'Yes'),
(39, '034', 'CV', 'CAPE VERDE', 'Cape Verde', 'Canadian', 'CPV', 132, 238, 2, 'Yes'),
(40, '161', 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', '', 'CYM', 136, 1345, 2, 'Yes'),
(41, '250', 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', '', 'CAF', 140, 236, 2, 'Yes'),
(42, '162', 'TD', 'CHAD', 'Chad', '', 'TCD', 148, 235, 2, 'Yes'),
(43, '037', 'CL', 'CHILE', 'Chile', 'Chadian', 'CHL', 152, 56, 2, 'Yes'),
(44, '038', 'CN', 'CHINA', 'China', 'Chilean', 'CHN', 156, 86, 2, 'Yes'),
(45, '039', 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', 'Chinese', NULL, NULL, 61, 2, 'Yes'),
(46, '251', 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', '', NULL, NULL, 672, 2, 'Yes'),
(47, '252', 'CO', 'COLOMBIA', 'Colombia', '', 'COL', 170, 57, 2, 'Yes'),
(48, '040', 'KM', 'COMOROS', 'Comoros', 'Colombian', 'COM', 174, 269, 2, 'Yes'),
(49, '235', 'CG', 'CONGO', 'Congo', '', 'COG', 178, 242, 2, 'Yes'),
(50, '042', 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', '', 'COD', 180, 242, 2, 'Yes'),
(51, '041', 'CK', 'COOK ISLANDS', 'Cook Islands', 'Costa Rican', 'COK', 184, 682, 2, 'Yes'),
(52, '237', 'CR', 'COSTA RICA', 'Costa Rica', 'Cote D''Ivoire', 'CRI', 188, 506, 2, 'Yes'),
(53, '163', 'CI', 'COTE D''IVOIRE', 'Cote D''Ivoire', 'Croatian', 'CIV', 384, 225, 2, 'Yes'),
(54, '043', 'HR', 'CROATIA', 'Croatia', '', 'HRV', 191, 385, 2, 'Yes'),
(55, '238', 'CU', 'CUBA', 'Cuba', '', 'CUB', 192, 53, 2, 'Yes'),
(56, '044', 'CY', 'CYPRUS', 'Cyprus', 'Cyprus', 'CYP', 196, 357, 2, 'Yes'),
(57, '164', 'CZ', 'CZECH REPUBLIC', 'Czech Republic', '', 'CZE', 203, 420, 2, 'Yes'),
(58, '081', 'DK', 'DENMARK', 'Denmark', 'Congolese', 'DNK', 208, 45, 2, 'Yes'),
(59, '046', 'DJ', 'DJIBOUTI', 'Djibouti', 'Danish', 'DJI', 262, 253, 2, 'Yes'),
(60, '165', 'DM', 'DOMINICA', 'Dominica', '', 'DMA', 212, 1767, 2, 'Yes'),
(61, '047', 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'Dominican', 'DOM', 214, 1809, 2, 'Yes'),
(62, '048', 'EC', 'ECUADOR', 'Ecuador', 'Dominican', 'ECU', 218, 593, 2, 'Yes'),
(63, '012', 'EG', 'EGYPT', 'Egypt', 'East Timor', 'EGY', 818, 20, 2, 'Yes'),
(64, '049', 'SV', 'EL SALVADOR', 'El Salvador', 'Ecuadorean', 'SLV', 222, 503, 2, 'Yes'),
(65, '050', 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'Egyptian', 'GNQ', 226, 240, 2, 'Yes'),
(66, '051', 'ER', 'ERITREA', 'Eritrea', 'Salvadorean', 'ERI', 232, 291, 2, 'Yes'),
(67, '052', 'EE', 'ESTONIA', 'Estonia', 'English', 'EST', 233, 372, 2, 'Yes'),
(68, '166', 'ET', 'ETHIOPIA', 'Ethiopia', 'Eritrean', 'ETH', 231, 251, 2, 'Yes'),
(69, '167', 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'Estonian', 'FLK', 238, 500, 2, 'Yes'),
(70, '054', 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'Ethiopian', 'FRO', 234, 298, 2, 'Yes'),
(71, '255', 'FJ', 'FIJI', 'Fiji', '', 'FJI', 242, 679, 2, 'Yes'),
(72, '055', 'FI', 'FINLAND', 'Finland', '', 'FIN', 246, 358, 2, 'Yes'),
(73, '189', 'FR', 'FRANCE', 'France', '', 'FRA', 250, 33, 2, 'Yes'),
(74, '056', 'GF', 'FRENCH GUIANA', 'French Guiana', 'Fijian', 'GUF', 254, 594, 2, 'Yes'),
(75, '057', 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'Finnish', 'PYF', 258, 689, 2, 'Yes'),
(76, '058', 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', 'French', NULL, NULL, 0, 2, 'Yes'),
(77, '295', 'GA', 'GABON', 'Gabon', NULL, 'GAB', 266, 241, 2, 'Yes'),
(78, '239', 'GM', 'GAMBIA', 'Gambia', 'French Guiana', 'GMB', 270, 220, 2, 'Yes'),
(79, '240', 'GE', 'GEORGIA', 'Georgia', '', 'GEO', 268, 995, 2, 'Yes'),
(80, '168', 'DE', 'GERMANY', 'Germany', '', 'DEU', 276, 49, 2, 'Yes'),
(81, '059', 'GH', 'GHANA', 'Ghana', 'Gabonese', 'GHA', 288, 233, 2, 'Yes'),
(82, '241', 'GI', 'GIBRALTAR', 'Gibraltar', 'Gambian', 'GIB', 292, 350, 2, 'Yes'),
(83, '169', 'GR', 'GREECE', 'Greece', 'Georgian', 'GRC', 300, 30, 2, 'Yes'),
(84, '061', 'GL', 'GREENLAND', 'Greenland', 'German', 'GRL', 304, 299, 2, 'Yes'),
(85, '063', 'GD', 'GRENADA', 'Grenada', 'Ghanaian', 'GRD', 308, 1473, 2, 'Yes'),
(86, '064', 'GP', 'GUADELOUPE', 'Guadeloupe', 'Gibraltar', 'GLP', 312, 590, 2, 'Yes'),
(87, '065', 'GU', 'GUAM', 'Guam', 'Greek', 'GUM', 316, 1671, 2, 'Yes'),
(88, '066', 'GT', 'GUATEMALA', 'Guatemala', '', 'GTM', 320, 502, 2, 'Yes'),
(89, '067', 'GN', 'GUINEA', 'Guinea', 'Grenadian', 'GIN', 324, 224, 2, 'Yes'),
(90, '243', 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', '', 'GNB', 624, 245, 2, 'Yes'),
(91, '242', 'GY', 'GUYANA', 'Guyana', '', 'GUY', 328, 592, 2, 'Yes'),
(92, '068', 'HT', 'HAITI', 'Haiti', '', 'HTI', 332, 509, 2, 'Yes'),
(93, '069', 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', '', NULL, NULL, 0, 2, 'Yes'),
(94, '053', 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', '', 'VAT', 336, 39, 2, 'Yes'),
(95, '070', 'HN', 'HONDURAS', 'Honduras', '', 'HND', 340, 504, 2, 'Yes'),
(96, '072', 'HK', 'HONG KONG', 'Hong Kong', '', 'HKG', 344, 852, 2, 'Yes'),
(97, '073', 'HU', 'HUNGARY', 'Hungary', 'Haitian', 'HUN', 348, 36, 2, 'Yes'),
(98, '257', 'IS', 'ICELAND', 'Iceland', '', 'ISL', 352, 354, 2, 'Yes'),
(99, '227', 'IN', 'INDIA', 'India', 'Indian', 'IND', 356, 91, 2, 'Yes'),
(100, '258', 'ID', 'INDONESIA', 'Indonesia', '', 'IDN', 360, 62, 2, 'Yes'),
(101, '075', 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'Honduran', 'IRN', 364, 98, 2, 'Yes'),
(102, '076', 'IQ', 'IRAQ', 'Iraq', 'Hong Kong', 'IRQ', 368, 964, 2, 'Yes'),
(103, '077', 'IE', 'IRELAND', 'Ireland', 'Hungarian', 'IRL', 372, 353, 2, 'Yes'),
(104, '078', 'IL', 'ISRAEL', 'Israel', 'Icelandic', 'ISR', 376, 972, 2, 'Yes'),
(105, '002', 'IT', 'ITALY', 'Italy', 'Indian', 'ITA', 380, 39, 2, 'Yes'),
(106, '079', 'JM', 'JAMAICA', 'Jamaica', 'Indonesian', 'JAM', 388, 1876, 2, 'Yes'),
(107, '112', 'JP', 'JAPAN', 'Japan', 'Iranian', 'JPN', 392, 81, 2, 'Yes'),
(108, '082', 'JO', 'JORDAN', 'Jordan', 'Iraqi', 'JOR', 400, 962, 2, 'Yes'),
(109, '083', 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'Irish', 'KAZ', 398, 7, 2, 'Yes'),
(110, '170', 'KE', 'KENYA', 'Kenya', '', 'KEN', 404, 254, 2, 'Yes'),
(111, '084', 'KI', 'KIRIBATI', 'Kiribati', 'Italian', 'KIR', 296, 686, 2, 'Yes'),
(112, '085', 'KP', 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', 'Korea, Democratic People''s Republic of', '', 'PRK', 408, 850, 2, 'Yes'),
(113, '171', 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'Jamaican', 'KOR', 410, 82, 2, 'Yes'),
(114, '086', 'KW', 'KUWAIT', 'Kuwait', 'Japanese', 'KWT', 414, 965, 2, 'Yes'),
(115, '284', 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', '', 'KGZ', 417, 996, 2, 'Yes'),
(116, '285', 'LA', 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', 'Lao People''s Democratic Republic', '', 'LAO', 418, 856, 2, 'Yes'),
(117, '172', 'LV', 'LATVIA', 'Latvia', 'Jordanian', 'LVA', 428, 371, 2, 'Yes'),
(118, '173', 'LB', 'LEBANON', 'Lebanon', 'Kazakhstan', 'LBN', 422, 961, 2, 'Yes'),
(119, '087', 'LS', 'LESOTHO', 'Lesotho', 'Kenyan', 'LSO', 426, 266, 2, 'Yes'),
(120, '174', 'LR', 'LIBERIA', 'Liberia', '', 'LBR', 430, 231, 2, 'Yes'),
(121, '286', 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', '', 'LBY', 434, 218, 2, 'Yes'),
(122, '253', 'LI', 'LIECHTENSTEIN', 'Liechtenstein', '', 'LIE', 438, 423, 2, 'Yes'),
(123, '175', 'LT', 'LITHUANIA', 'Lithuania', 'South Korean', 'LTU', 440, 370, 2, 'Yes'),
(124, '088', 'LU', 'LUXEMBOURG', 'Luxembourg', 'DPR Korea', 'LUX', 442, 352, 2, 'Yes'),
(125, '226', 'MO', 'MACAO', 'Macao', '', 'MAC', 446, 853, 2, 'Yes'),
(126, '089', 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'Kuwaiti', 'MKD', 807, 389, 2, 'Yes'),
(127, '176', 'MG', 'MADAGASCAR', 'Madagascar', '', 'MDG', 450, 261, 2, 'Yes'),
(128, '178', 'MW', 'MALAWI', 'Malawi', 'Latvian', 'MWI', 454, 265, 2, 'Yes'),
(129, '091', 'MY', 'MALAYSIA', 'Malaysia', 'Lebanese', 'MYS', 458, 60, 2, 'Yes'),
(130, '090', 'MV', 'MALDIVES', 'Maldives', '', 'MDV', 462, 960, 2, 'Yes'),
(131, '179', 'ML', 'MALI', 'Mali', 'Liberian', 'MLI', 466, 223, 2, 'Yes'),
(132, '092', 'MT', 'MALTA', 'Malta', 'Libyan', 'MLT', 470, 356, 2, 'Yes'),
(133, '180', 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'Liechtenstein', 'MHL', 584, 692, 2, 'Yes'),
(134, '181', 'MQ', 'MARTINIQUE', 'Martinique', 'Lithuanian', 'MTQ', 474, 596, 2, 'Yes'),
(135, '182', 'MR', 'MAURITANIA', 'Mauritania', 'Luxembourgian', 'MRT', 478, 222, 2, 'Yes'),
(136, '183', 'MU', 'MAURITIUS', 'Mauritius', 'Macedonian', 'MUS', 480, 230, 2, 'Yes'),
(137, '184', 'YT', 'MAYOTTE', 'Mayotte', 'Madagascan', NULL, NULL, 269, 2, 'Yes'),
(138, '287', 'MX', 'MEXICO', 'Mexico', '', 'MEX', 484, 52, 2, 'Yes'),
(139, '185', 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'Malawian', 'FSM', 583, 691, 2, 'Yes'),
(140, '093', 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'Malaysian', 'MDA', 498, 373, 2, 'Yes'),
(141, '094', 'MC', 'MONACO', 'Monaco', 'Maldivian', 'MCO', 492, 377, 2, 'Yes'),
(142, '186', 'MN', 'MONGOLIA', 'Mongolia', 'Malian', 'MNG', 496, 976, 2, 'Yes'),
(143, '095', 'MS', 'MONTSERRAT', 'Montserrat', 'Maltese', 'MSR', 500, 1664, 2, 'Yes'),
(144, '187', 'MA', 'MOROCCO', 'Morocco', '', 'MAR', 504, 212, 2, 'Yes'),
(145, '244', 'MZ', 'MOZAMBIQUE', 'Mozambique', '', 'MOZ', 508, 258, 2, 'Yes'),
(146, '188', 'MM', 'MYANMAR', 'Myanmar', 'Mauritanian', 'MMR', 104, 95, 2, 'Yes'),
(147, '096', 'NA', 'NAMIBIA', 'Namibia', 'Mauritian', 'NAM', 516, 264, 2, 'Yes'),
(148, '259', 'NR', 'NAURU', 'Nauru', '', 'NRU', 520, 674, 2, 'Yes'),
(149, '097', 'NP', 'NEPAL', 'Nepal', 'Mexican', 'NPL', 524, 977, 2, 'Yes'),
(150, '283', 'NL', 'NETHERLANDS', 'Netherlands', '', 'NLD', 528, 31, 2, 'Yes'),
(151, '288', 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', '', 'ANT', 530, 599, 2, 'Yes'),
(152, '294', 'NC', 'NEW CALEDONIA', 'New Caledonia', NULL, 'NCL', 540, 687, 2, 'Yes'),
(153, '190', 'NZ', 'NEW ZEALAND', 'New Zealand', 'Moldovan', 'NZL', 554, 64, 2, 'Yes'),
(154, '098', 'NI', 'NICARAGUA', 'Nicaragua', 'Monacan', 'NIC', 558, 505, 2, 'Yes'),
(155, '191', 'NE', 'NIGER', 'Niger', 'Mongolian', 'NER', 562, 227, 2, 'Yes'),
(156, '245', 'NG', 'NIGERIA', 'Nigeria', 'Montenegrin', 'NGA', 566, 234, 2, 'Yes'),
(157, '246', 'NU', 'NIUE', 'Niue', '', 'NIU', 570, 683, 2, 'Yes'),
(158, '099', 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'Morocaine', 'NFK', 574, 672, 2, 'Yes'),
(159, '192', 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'Mozambican', 'MNP', 580, 1670, 2, 'Yes'),
(160, '222', 'NO', 'NORWAY', 'Norway', 'Burmese', 'NOR', 578, 47, 2, 'Yes'),
(161, '193', 'OM', 'OMAN', 'Oman', 'Namibian', 'OMN', 512, 968, 2, 'Yes'),
(162, '194', 'PK', 'PAKISTAN', 'Pakistan', '', 'PAK', 586, 92, 2, 'Yes'),
(163, '132', 'PW', 'PALAU', 'Palau', 'Nepalese', 'PLW', 585, 680, 2, 'Yes'),
(164, '131', 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', 'Dutch', NULL, NULL, 970, 2, 'Yes'),
(165, '247', 'PA', 'PANAMA', 'Panama', '', 'PAN', 591, 507, 2, 'Yes'),
(166, '100', 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'New Zealander', 'PNG', 598, 675, 2, 'Yes'),
(167, '221', 'PY', 'PARAGUAY', 'Paraguay', 'Nicaraguan', 'PRY', 600, 595, 2, 'Yes'),
(168, '195', 'PE', 'PERU', 'Peru', 'Nigerien', 'PER', 604, 51, 2, 'Yes'),
(169, '101', 'PH', 'PHILIPPINES', 'Philippines', 'Nigerian', 'PHL', 608, 63, 2, 'Yes'),
(170, '248', 'PN', 'PITCAIRN', 'Pitcairn', '', 'PCN', 612, 0, 2, 'Yes'),
(171, '260', 'PL', 'POLAND', 'Poland', '', 'POL', 616, 48, 2, 'Yes'),
(172, '197', 'PT', 'PORTUGAL', 'Portugal', '', 'PRT', 620, 351, 2, 'Yes'),
(173, '102', 'PR', 'PUERTO RICO', 'Puerto Rico', 'Norwegian', 'PRI', 630, 1787, 2, 'Yes'),
(174, '103', 'QA', 'QATAR', 'Qatar', 'Omani', 'QAT', 634, 974, 2, 'Yes'),
(175, '003', 'RE', 'REUNION', 'Reunion', 'Pakistani', 'REU', 638, 262, 2, 'Yes'),
(176, '104', 'RO', 'ROMANIA', 'Romania', '', 'ROM', 642, 40, 2, 'Yes'),
(177, '153', 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'Palestinian', 'RUS', 643, 70, 2, 'Yes'),
(178, '105', 'RW', 'RWANDA', 'Rwanda', 'Panamanian', 'RWA', 646, 250, 2, 'Yes'),
(179, '106', 'SH', 'SAINT HELENA', 'Saint Helena', 'Papua New Guinean', 'SHN', 654, 290, 2, 'Yes'),
(180, '107', 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'Paraguayan', 'KNA', 659, 1869, 2, 'Yes'),
(181, '108', 'LC', 'SAINT LUCIA', 'Saint Lucia', 'Peruvian', 'LCA', 662, 1758, 2, 'Yes'),
(182, '109', 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'Filipino', 'SPM', 666, 508, 2, 'Yes'),
(183, '261', 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', '', 'VCT', 670, 1784, 2, 'Yes'),
(184, '110', 'WS', 'SAMOA', 'Samoa', 'Polish', 'WSM', 882, 684, 2, 'Yes'),
(185, '111', 'SM', 'SAN MARINO', 'San Marino', 'Portuguese', 'SMR', 674, 378, 2, 'Yes'),
(186, '113', 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', '', 'STP', 678, 239, 2, 'Yes'),
(187, '114', 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'Qatari', 'SAU', 682, 966, 2, 'Yes'),
(188, '278', 'SN', 'SENEGAL', 'Senegal', '', 'SEN', 686, 221, 2, 'Yes'),
(189, '296', 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL, NULL, 381, 2, 'Yes'),
(190, '262', 'SC', 'SEYCHELLES', 'Seychelles', '', 'SYC', 690, 248, 2, 'Yes'),
(191, '263', 'SL', 'SIERRA LEONE', 'Sierra Leone', '', 'SLE', 694, 232, 2, 'Yes'),
(192, '115', 'SG', 'SINGAPORE', 'Singapore', '', 'SGP', 702, 65, 2, 'Yes'),
(193, '290', 'SK', 'SLOVAKIA', 'Slovakia', '', 'SVK', 703, 421, 2, 'Yes'),
(194, '116', 'SI', 'SLOVENIA', 'Slovenia', 'Romanian', 'SVN', 705, 386, 2, 'Yes'),
(195, '118', 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', '', 'SLB', 90, 677, 2, 'Yes'),
(196, '117', 'SO', 'SOMALIA', 'Somalia', 'Russian', 'SOM', 706, 252, 2, 'Yes'),
(197, '282', 'ZA', 'SOUTH AFRICA', 'South Africa', 'Rwandan', 'ZAF', 710, 27, 2, 'Yes'),
(198, '264', 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', '', NULL, NULL, 0, 2, 'Yes'),
(199, '198', 'ES', 'SPAIN', 'Spain', '', 'ESP', 724, 34, 2, 'Yes'),
(200, '199', 'LK', 'SRI LANKA', 'Sri Lanka', 'Sri Lankan', 'LKA', 144, 94, 2, 'Yes'),
(201, '265', 'SD', 'SUDAN', 'Sudan', 'Sudanese', 'SDN', 736, 249, 2, 'Yes'),
(202, '200', 'SR', 'SURINAME', 'Suriname', '', 'SUR', 740, 597, 2, 'Yes'),
(203, '119', 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', '', 'SJM', 744, 47, 2, 'Yes'),
(204, '121', 'SZ', 'SWAZILAND', 'Swaziland', '', 'SWZ', 748, 268, 2, 'Yes'),
(205, '122', 'SE', 'SWEDEN', 'Sweden', 'Swedish ', 'SWE', 752, 46, 2, 'Yes'),
(206, '123', 'CH', 'SWITZERLAND', 'Switzerland', 'Saudi Arabian', 'CHE', 756, 41, 2, 'Yes'),
(207, '281', 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'Scottish', 'SYR', 760, 963, 2, 'Yes'),
(208, '124', 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'Senegalese', 'TWN', 158, 886, 2, 'Yes'),
(209, '152', 'TJ', 'TAJIKISTAN', 'Tajikistan', 'Serbian', 'TJK', 762, 992, 2, 'Yes'),
(210, '125', 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'Seychellois', 'TZA', 834, 255, 2, 'Yes'),
(211, '126', 'TH', 'THAILAND', 'Thailand', 'Sierra Leonian', 'THA', 764, 66, 2, 'Yes'),
(212, '127', 'TL', 'TIMOR-LESTE', 'Timor-Leste', 'Singaporean', NULL, NULL, 670, 2, 'Yes'),
(213, '291', 'TG', 'TOGO', 'Togo', '', 'TGO', 768, 228, 2, 'Yes'),
(214, '201', 'TK', 'TOKELAU', 'Tokelau', 'Slovak', 'TKL', 772, 690, 2, 'Yes'),
(215, '202', 'TO', 'TONGA', 'Tonga', 'Slovenian', 'TON', 776, 676, 2, 'Yes'),
(216, '128', 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'Solomon Islander', 'TTO', 780, 1868, 2, 'Yes'),
(217, '129', 'TN', 'TUNISIA', 'Tunisia', 'Somali', 'TUN', 788, 216, 2, 'Yes'),
(218, '130', 'TR', 'TURKEY', 'Turkey', 'South African', 'TUR', 792, 90, 2, 'Yes'),
(219, '266', 'TM', 'TURKMENISTAN', 'Turkmenistan', '', 'TKM', 795, 7370, 2, 'Yes'),
(220, '203', 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'Spanish', 'TCA', 796, 1649, 2, 'Yes'),
(221, '134', 'TV', 'TUVALU', 'Tuvalu', 'Sri Lankan', 'TUV', 798, 688, 2, 'Yes'),
(222, '133', 'UG', 'UGANDA', 'Uganda', 'Ugandan', 'UGA', 800, 256, 2, 'Yes'),
(223, '135', 'UA', 'UKRAINE', 'Ukraine', '', 'UKR', 804, 380, 2, 'Yes'),
(224, '267', 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', '', 'ARE', 784, 971, 2, 'Yes'),
(225, '204', 'GB', 'UNITED KINGDOM', 'United Kingdom', 'English', 'GBR', 826, 44, 2, 'Yes'),
(226, '136', 'US', 'UNITED STATES', 'United States', 'Swedish', 'USA', 840, 1, 2, 'Yes'),
(227, '137', 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', 'Swiss', NULL, NULL, 1, 2, 'Yes'),
(228, '205', 'UY', 'URUGUAY', 'Uruguay', 'Syrian', 'URY', 858, 598, 2, 'Yes'),
(229, '138', 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'Taiwanese', 'UZB', 860, 998, 2, 'Yes'),
(230, '206', 'VU', 'VANUATU', 'Vanuatu', '', 'VUT', 548, 678, 2, 'Yes'),
(231, '139', 'VE', 'VENEZUELA', 'Venezuela', 'Tanzanian', 'VEN', 862, 58, 2, 'Yes'),
(232, '140', 'VN', 'VIET NAM', 'Viet Nam', 'Thai', 'VNM', 704, 84, 2, 'Yes'),
(233, '292', 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', NULL, 'VGB', 92, 1284, 2, 'Yes'),
(234, '293', 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', '', 'VIR', 850, 1340, 2, 'Yes'),
(235, '207', 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', '', 'WLF', 876, 681, 2, 'Yes'),
(236, '269', 'EH', 'WESTERN SAHARA', 'Western Sahara', '', 'ESH', 732, 212, 2, 'Yes'),
(237, '208', 'YE', 'YEMEN', 'Yemen', '', 'YEM', 887, 967, 2, 'Yes'),
(238, '141', 'ZM', 'ZAMBIA', 'Zambia', '', 'ZMB', 894, 260, 2, 'Yes'),
(239, '142', 'ZW', 'ZIMBABWE', 'Zimbabwe', 'Tunisian', 'ZWE', 716, 263, 2, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `economic_zones`
--

CREATE TABLE IF NOT EXISTS `economic_zones` (
`id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `upazilla` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `district` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_locked` tinyint(4) NOT NULL DEFAULT '0',
  `is_archieved` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `economic_zones`
--

INSERT INTO `economic_zones` (`id`, `name`, `upazilla`, `district`, `area`, `remarks`, `is_locked`, `is_archieved`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Mongla EZ', 'Mongla', 'Bagerhat', '205', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(2, 'Mongla EZ (For Indians)', 'Mongla,', 'Bagerhat', '110.15', 'For Indian Investors (G2G)', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(3, 'Rampal EZ', 'Rampal', 'Bagerhat', '300', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(4, 'Sundarban Tourism Park', 'Shoronkhola', 'Bagerhat', '1546.35', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(5, 'Agailjhara EZ', 'Agailjhara', 'Barisal', '300', '', 0, 0, 0, '0000-00-00 00:00:00', 228, '2016-10-17 00:25:23'),
(6, 'Bhola sadar EZ', 'Bhola Sadar', 'Bhola', '304.07', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(7, 'Bogra EZ -1', 'Ushajahanpur', 'Bogra', '251.43', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(8, 'Ashugonj EZ', 'Ashugonj', 'Brahmanbaria', '328.61', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(9, 'Anawra -2 (CEIZ)', 'Anawra', 'Chittagong', '774.48', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(10, 'Anwara EZ', 'Gahira, Anwara', 'Chittagong', '503.7', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(11, 'Mirsarai EZ', 'Mirsarai', 'Chittagong', '13117.7824', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(12, 'Patiya EZ', 'Patiya', 'Chittagong', '774.48', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(13, 'Comilla EZ', 'Meghna', 'Comilla', '272', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(14, 'Coxâ€™s Bazar Special EZ', 'Moheshkhali', 'Chittagong', '8784.77', '', 0, 0, 0, '0000-00-00 00:00:00', 228, '2016-10-17 00:25:48'),
(15, 'Jaliardip Economic Zone EZ', 'Taknaf', 'Coxâ€™s Bazar', '271.93', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(16, 'Moheshkhali -1 EZ', 'Moheshkhali', 'Coxâ€™s Bazar', '1438.52', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(17, 'Moheshkhali -2 EZ', 'Moheshkhali', 'Coxâ€™s Bazar', '827.31', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(18, 'Moheshkhali -3 EZ', 'Dholghata', 'Coxâ€™s Bazar', '1501.04', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(19, 'Moheshkhali Special Economic Zone', 'Ghotibagha', 'Coxâ€™s Bazar', '1000', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(20, 'Moheshkhali Special Economic Zone Cox''s ', 'Ghotibagha and Sonadiya, Moheshkhali', 'Coxâ€™s Bazar', '12962.22', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(21, 'Moheshkhali Special Economic Zone Kalama', 'Moheshkhali', 'Coxâ€™s Bazar', '3980.07', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(22, 'Sabrang, Tourism SEZ', 'Tacknaf', 'Coxâ€™s Bazar', '1027.56', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(23, 'Dhaka EZ', 'Dhoha', 'Dhaka', '316.35', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(24, 'Dhaka SEZ', 'Karanigonj', 'Dhaka', '105', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(25, 'Feni Economic Zone', 'Sonagazi', 'Feni', '7219.79', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(26, 'Shreepur EZ', '(Nayanpur), Shreepur', 'Gazipur', '510', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(27, 'Sreepur EZ', 'Sreepur', 'Gazipur', '510', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(28, 'Gopalganj EZ', 'Kotalipara', 'Gopalganj', '201.83', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(29, 'Gopalganj EZ - 2', 'Gopalganj Sadar', 'Gopalganj', '200', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(30, 'Habigonj EZ', 'Chunurughat', 'Habigong', '511.83', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(31, 'Jamalpur EZ -2', 'Jamalpur Sadar', 'Jamalpur', '263.25', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(32, 'Jamalpur EZ', 'Jamalpur sadar', 'Jamalpur', '457.77', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(33, 'Khulna EZ -1', 'Boiyaghata', 'Khulna', '519.52', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(34, 'Khulna EZ -2', 'Terkhada', 'Khulna', '509.64', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(35, 'Kustia EZ', 'Bheramara', 'Kustia', '506.77', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(36, 'Manikgnnj EZ', '(BIWTA old Aricha Ferighat), Shibaloy', 'Manikganj', '300', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(37, 'Shrihatta EZ', 'Sherpur', 'Moulavibazar', '352.12', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(38, 'Munshiganj Gazaria EZ', 'Gazaria', 'Munshiganj', '97.98', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(39, 'Mymensingh EZ', 'Mymensingh', 'Mymensingh', '487.77', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(40, 'Mymensingh EZ', 'Mymensingh Sadar', 'Mymensingh', '494', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(41, 'Araihazar -2 Economic Zone', 'Araihazar', 'Narayanganj', '400', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(42, 'Araihazar Economic Zone', 'Araihazar', 'Narayanganj', '1010.9', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(43, 'Narayanganj EZ', 'Bandar & Sonarga', 'Narayanganj', '875.65', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(44, 'Narsingdi EZ', 'Narsingdi Sadar', 'Narsingdi', '690.2016', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(45, 'Nator Economic Zone', 'Lalpur', 'Nator', '3220', 'Agro Food Processing Zone', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(46, 'Netrokona EZ -1', 'Netrokona Sadar', 'Netrokona', '266.755', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(47, 'Nilphamarai EZ', 'Nilphamari Sadar', 'Nilphamari', '357.76', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(48, 'Panchghar EZ', 'Debiganj', 'Panchghar', '595.01', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(49, 'Rajshahi Economic Zone', 'Paba', 'Rajshahi', '204.06', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(50, 'Rajshai EZ', 'Poba', 'Rajshai', '204.6', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(51, 'Shariatpur Economic Zone', 'Jajira', 'Shariatpur', '525.265', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(52, 'Shariatpur Economic Zone', 'Gosharhat', 'Shariatpur', '750', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(53, 'Sherpur Economic Zone', 'Sherpur', 'Sherpur', '361.08', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(54, 'Sirajganj EZ', '(Adjacent to Bangabandhu Bridge), Sirajg', 'Sirajganj', '1041.43', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(55, 'Narayanganj EZ Sonargaon', 'Sonargaon', 'Sonargaon', '1000', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(56, 'Sylhet Special EZ, Gowainghat', 'Gowainghat', 'Sylhet', '255.83', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(57, 'Meghna EZ', 'Sonargaon', 'Narayanganj', '67.9163', '', 0, 0, 0, '0000-00-00 00:00:00', 0, '2016-10-16 23:35:21'),
(58, 'Sample EZ', 'Mirshorai', 'Chittagong', '100.45', '', 0, 0, 228, '2016-10-17 12:09:51', 228, '2016-10-17 00:09:51');

-- --------------------------------------------------------

--
-- Table structure for table `failed_login_history`
--

CREATE TABLE IF NOT EXISTS `failed_login_history` (
`id` int(11) NOT NULL,
  `remote_address` varchar(50) NOT NULL,
  `user_email` varchar(40) NOT NULL,
  `is_archived` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `img_user_profile`
--

CREATE TABLE IF NOT EXISTS `img_user_profile` (
`id` int(11) NOT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `details` longtext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_100000_create_password_resets_table', 1),
('2016_11_16_103646_create_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `security_profile`
--

CREATE TABLE IF NOT EXISTS `security_profile` (
`id` int(11) NOT NULL,
  `profile_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `allowed_remote_ip` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `week_off_days` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `work_hour_start` time NOT NULL,
  `work_hour_end` time NOT NULL,
  `active_status` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `security_profile`
--

INSERT INTO `security_profile` (`id`, `profile_name`, `user_email`, `user_type`, `allowed_remote_ip`, `week_off_days`, `work_hour_start`, `work_hour_end`, `active_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'default', '', '', '0.0.0.0', '', '09:00:00', '23:59:59', 'yes', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Individual Test', 'm1@gmail.com', '1x101', '0.0.0.0', 'FRI', '06:00:00', '17:00:00', 'yes', 62, 1, '2016-02-13 14:29:12', '2016-09-22 12:28:24'),
(3, 'Group Test', '', '', '0.0.0.0', 'FRI', '04:00:00', '23:59:59', 'yes', 62, 62, '2016-02-13 14:30:21', '2016-02-13 16:06:20'),
(4, 'weekday', '', '', '192.168.0.101', 'SAT', '09:00:00', '17:00:00', 'yes', 62, 62, '2016-02-13 15:45:39', '2016-02-13 15:46:29'),
(5, 't50', 'milonfci@gmail.com', '1x101', '0.0.0.0', 'FRI', '12:00:00', '05:00:00', 'yes', 1, 1, '2016-09-21 12:34:52', '2016-09-21 12:45:45'),
(6, '24 format', '', '1x101', '0.0.0.0', 'FRI', '09:00:00', '22:00:00', 'yes', 1, 1, '2016-09-21 12:39:06', '2016-09-21 12:44:28'),
(7, 'without type', '', '', '0.0.0.0', 'FRI', '00:00:00', '00:00:00', 'yes', 1, 1, '2016-09-21 13:34:12', '2016-09-21 13:34:12'),
(8, 'w1', 'milonfci@gmail.com', '', 'feni trank road', 'FRI', '09:00:00', '06:00:00', 'yes', 1, 228, '2016-09-21 13:35:28', '2016-09-24 14:44:55'),
(9, 'ssd', 'mm@gmail.com', '', '0.0.0.0', 'FRI', '00:00:00', '17:00:00', 'yes', 0, 0, '2016-09-21 13:39:07', '2016-09-21 16:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `user_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_sub_type` int(11) NOT NULL DEFAULT '0',
  `eco_zone_id` int(11) NOT NULL DEFAULT '0',
  `desk_id` int(11) NOT NULL DEFAULT '0',
  `user_full_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `delegate_to_user_id` int(11) DEFAULT NULL,
  `delegate_by_user_id` int(11) DEFAULT NULL,
  `user_hash` text COLLATE utf8_unicode_ci NOT NULL,
  `user_status` enum('active','inactive','rejected') CHARACTER SET utf8 NOT NULL,
  `user_verification` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `user_pic` text COLLATE utf8_unicode_ci NOT NULL,
  `user_nid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_DOB` date NOT NULL,
  `user_gender` enum('Male','Female','Not defined') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Not defined',
  `user_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorization_file` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `passport_nid_file` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `user_first_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_language` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'bn',
  `security_profile_id` int(11) NOT NULL DEFAULT '0',
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `division` int(11) NOT NULL,
  `district` int(11) NOT NULL DEFAULT '0',
  `thana` int(11) NOT NULL DEFAULT '0',
  `country` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nationality` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `passport_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `road_no` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `house_no` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `post_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_fax` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `login_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_hash_expire_time` datetime NOT NULL,
  `auth_token_allow` int(11) NOT NULL DEFAULT '0',
  `auth_token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_agreement` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `first_login` tinyint(4) NOT NULL DEFAULT '0',
  `identity_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 = passport, 2 = NID',
  `is_approved` tinyint(4) NOT NULL DEFAULT '0',
  `is_locked` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `user_sub_type`, `eco_zone_id`, `desk_id`, `user_full_name`, `user_email`, `password`, `delegate_to_user_id`, `delegate_by_user_id`, `user_hash`, `user_status`, `user_verification`, `user_pic`, `user_nid`, `user_DOB`, `user_gender`, `user_phone`, `authorization_file`, `passport_nid_file`, `signature`, `user_first_login`, `user_language`, `security_profile_id`, `details`, `division`, `district`, `thana`, `country`, `nationality`, `passport_no`, `state`, `province`, `road_no`, `house_no`, `post_code`, `user_fax`, `remember_token`, `login_token`, `user_hash_expire_time`, `auth_token_allow`, `auth_token`, `user_agreement`, `first_login`, `identity_type`, `is_approved`, `is_locked`, `created_by`, `created_at`, `updated_at`, `updated_by`) VALUES
(1, '1x101', 0, 0, 0, 'System Admin', 'sysadmin@batworld.com', '$2y$10$QXwS0Okz1UuPmNEdabm2reX1yYRer16oCP0eCTVt117yJEs04z20C', NULL, NULL, '5736e2ef45fd6372a414c45c5f3b51eae7344ced7b66e9b92bd12f06b8bead13', 'active', 'yes', 'Desert.jpg', '2147483647', '1988-02-05', 'Not defined', '+8801767957181', '5653f6065b6cemozilla.pdf', NULL, 'Lighthouse.jpg', '2016-08-02 02:08:59', 'en', 0, 'heolloooooooo', 0, 0, 0, '0', 'BD', '6+9+9', NULL, NULL, '', '', '', '', 'aczXM1cAtXy1fE2XrOHx5tqc0w4VFNuxt0diyl9MslLSPdum3PfgFtjo5h1J', '', '0000-00-00 00:00:00', 0, '', 1, 1, 0, 1, 0, 0, '2016-11-10 04:48:06', '2016-11-10 15:48:06', 1),
(7, '7x707', 0, 0, 0, 'Rafid', 'shahriar.cste@gmail.com', '$2y$10$gdsLGWJm0F4ogOxTeoOYs.XMR5GCiKTIqlOAhQGB/J4kXckOgpjri', NULL, NULL, 'V44OwK35nJoUlEOeSy-mB8pHAdleKq1XzreYJkoQw9aUJcgMIThLFNVibAqvYPg_hTgz9m_hBPnuRRXv_JMr1ejhxOLIcGvfp5zYFkBm0peGtBAunrJXbNloGAEDZ7oy', 'active', 'yes', '', '', '1992-11-21', 'Not defined', '+8801521527826', '2016_582fcf594082d2.23433406test - Copy (2).pdf', '14795282812016_582fcf59415075.46226133test - Copy (3).pdf', '', '2016-11-18 22:28:57', 'bn', 0, '', 0, 26, 224, 'BD', 'BD', 'BD4787471', 'Dhaka', 'Dhaka Division', 'Rd No 13, Dhaka, Bangladesh', 'Rd No 13', '', '', 'F82KibnOplom8zI0V2Y9XQJeSKnsmWfExXzjmMkGyMVzP9nb2O9rmmkxR7CV', 'vGt3OVrG7ahdFshAvby43NoJ_AsUJCPc9CctENNzM-friiWCnWxnoWITrWmuufRJPrRP99QBkQYTUcyqU1f4pg', '0000-00-00 00:00:00', 0, '', 1, 1, 1, 0, 0, 0, '2016-11-19 10:42:24', '2016-11-19 10:42:24', 7);

-- --------------------------------------------------------

--
-- Table structure for table `user_desk`
--

CREATE TABLE IF NOT EXISTS `user_desk` (
`desk_id` int(11) NOT NULL,
  `desk_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `desk_status` tinyint(1) NOT NULL COMMENT '1 = active, 0 = inactive',
  `is_registarable` int(11) NOT NULL,
  `deligate_to_desk` varchar(60) COLLATE utf8_unicode_ci DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user_desk`
--

INSERT INTO `user_desk` (`desk_id`, `desk_name`, `desk_status`, `is_registarable`, `deligate_to_desk`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(3, 'RD1', 1, 2, '0', 0, '2016-08-17 03:32:56', 0, '0000-00-00 00:00:00'),
(4, 'RD2', 1, 2, '0', 0, '2016-08-17 03:32:56', 0, '0000-00-00 00:00:00'),
(5, 'RD3', 1, 2, '0', 0, '2016-08-17 03:32:56', 0, '0000-00-00 00:00:00'),
(6, 'RD4', 1, 2, '0', 0, '2016-08-17 03:32:56', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE IF NOT EXISTS `user_logs` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_log_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `login_dt` datetime NOT NULL,
  `logout_dt` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `ip_address`, `access_log_id`, `login_dt`, `logout_dt`, `created_at`, `updated_at`) VALUES
(1, 7, '::1', 'JCqi44Xzc0', '2016-11-19 11:32:12', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 7, '::1', 'kCb12TY3qK', '2016-11-19 11:52:44', '2016-11-19 15:27:40', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 7, '::1', 'hYE3NDuzNl', '2016-11-19 15:27:50', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 7, '::1', 'bNvpJP1ErT', '2016-11-19 16:42:24', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `is_registarable` int(11) NOT NULL,
  `access_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `permission_json` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `security_profile_id` int(11) DEFAULT '1',
  `auth_token_type` enum('optional','mandatory') COLLATE utf8_unicode_ci DEFAULT 'optional',
  `db_access_data` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `type_name`, `is_registarable`, `access_code`, `permission_json`, `status`, `security_profile_id`, `auth_token_type`, `db_access_data`, `created_at`, `updated_at`, `updated_by`, `updated_on`) VALUES
('1x101', 'SysAdmin', -1, '1_101', NULL, 'active', 1, 'optional', '', '2015-12-13 18:04:16', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
('2x202', 'IT Help Desk', 4, '2_202', NULL, 'active', 1, 'optional', '', '2016-11-15 18:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
('4x404', 'Developers', 1, '4_404', NULL, 'active', 1, 'optional', '', '2016-10-15 00:21:56', '2014-12-31 00:00:00', 0, '0000-00-00 00:00:00'),
('5x505', 'Unit Investor', 1, '5_505', NULL, 'active', 1, 'optional', '', '2016-09-25 04:35:23', '2014-12-31 00:00:00', 0, '0000-00-00 00:00:00'),
('6x606', 'Visa Assistance', 2, '6_606', NULL, 'active', 1, 'optional', '0', '2016-10-05 05:35:30', '2016-10-05 05:13:15', 1, '0000-00-00 00:00:00'),
('7x707', 'Super MIS', 1, '7_707', NULL, 'active', 1, 'optional', '0', '2016-10-05 15:35:30', '2016-10-05 15:13:15', 1, '0000-00-00 00:00:00'),
('8x808', 'Zone MIS', 1, '8_808', NULL, 'active', 1, 'optional', '', '2016-10-05 15:35:30', '2016-10-05 15:13:15', 0, '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configuration`
--
ALTER TABLE `configuration`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country_info`
--
ALTER TABLE `country_info`
 ADD PRIMARY KEY (`id`), ADD KEY `nationality` (`nationality`);

--
-- Indexes for table `economic_zones`
--
ALTER TABLE `economic_zones`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_login_history`
--
ALTER TABLE `failed_login_history`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `img_user_profile`
--
ALTER TABLE `img_user_profile`
 ADD KEY `id` (`id`);

--
-- Indexes for table `security_profile`
--
ALTER TABLE `security_profile`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_email` (`user_email`);

--
-- Indexes for table `user_desk`
--
ALTER TABLE `user_desk`
 ADD PRIMARY KEY (`desk_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
 ADD PRIMARY KEY (`id`), ADD KEY `is_registrable` (`is_registarable`), ADD KEY `security_profile.id` (`security_profile_id`), ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `configuration`
--
ALTER TABLE `configuration`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `country_info`
--
ALTER TABLE `country_info`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=240;
--
-- AUTO_INCREMENT for table `economic_zones`
--
ALTER TABLE `economic_zones`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `failed_login_history`
--
ALTER TABLE `failed_login_history`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `img_user_profile`
--
ALTER TABLE `img_user_profile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `security_profile`
--
ALTER TABLE `security_profile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `user_desk`
--
ALTER TABLE `user_desk`
MODIFY `desk_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
