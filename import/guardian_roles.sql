-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 28, 2014 at 05:19 PM
-- Server version: 5.5.35-0ubuntu0.13.10.2
-- PHP Version: 5.5.3-1ubuntu2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `doom`
--

-- --------------------------------------------------------

--
-- Table structure for table `guardian_roles`
--

CREATE TABLE IF NOT EXISTS `guardian_roles` (
  `guardian_roles_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`guardian_roles_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `guardian_roles`
--

INSERT INTO `guardian_roles` (`guardian_roles_id`, `code`, `description`) VALUES
(1, 'FAMMEMB', 'Family member'),
(2, 'CHILD', 'Child  '),
(3, 'CHILDADOPT', 'Adopted child'),
(4, 'DAUADOPT', 'Adopted daughter'),
(5, 'SONADOPT', 'Adopted son'),
(6, 'CHLDFOST', 'Foster child'),
(7, 'DAUFOST', 'Foster daughter'),
(8, 'SONFOST', 'Foster son'),
(9, 'CHILDINLAW', 'Child in-law'),
(10, 'DAUINLAW', 'Daughter in-law'),
(11, 'SONINLAW', 'Son in-law'),
(12, 'DAUC', 'Daughter child'),
(13, 'DAU', 'Natural daughter'),
(14, 'STPDAU', 'Stepdaughter'),
(15, 'NCHILD', 'Natural child'),
(16, 'SON', 'Natural son'),
(17, 'SONC', 'Son child'),
(18, 'STPSON', 'Stepson'),
(19, 'STPCHILD', 'Stepchild'),
(20, 'EXT', 'Extended family member'),
(21, 'AUNT', 'Aunt'),
(22, 'MAUNT', 'Maternal aunt'),
(23, 'PAUNT', 'Paternal aunt'),
(24, 'COUSN', 'Cousin'),
(25, 'MCOUSN', 'Maternal cousin'),
(26, 'PCOUSN', 'Paternal cousin'),
(27, 'GGRPRN', 'Great grandparent'),
(28, 'GGRFTH', 'Great grandfather'),
(29, 'GGRMTH', 'Great grandmother'),
(30, 'MGGRFTH', 'Maternal great grandfather'),
(31, 'MGGRMTH', 'Maternal great grandmother'),
(32, 'MGGRPRN', 'Maternal great grandparent'),
(33, 'PGGRFTH', 'Paternal great grandfather'),
(34, 'PGGRMTH', 'Paternal great grandmother'),
(35, 'PGGRPRN', 'Paternal great grandparent'),
(36, 'GRNDCHILD', 'Grandchild'),
(37, 'GRNDDAU', 'Granddaughter'),
(38, 'GRNDSON', 'Grandson'),
(39, 'GRPRN', 'Grandparent'),
(40, 'GRFTH', 'Grandfather'),
(41, 'GRMTH', 'Grandmother'),
(42, 'MGRFTH', 'Maternal grandfather'),
(43, 'MGRMTH', 'Maternal grandmother'),
(44, 'MGRPRN', 'Maternal grandparent'),
(45, 'PGRFTH', 'Paternal grandfather'),
(46, 'PGRMTH', 'Paternal grandmother'),
(47, 'PGRPRN', 'Paternal grandparent'),
(48, 'NIENEPH', 'Neice/nephew'),
(49, 'NEPHEW', 'Nephew'),
(50, 'NIECE', 'Niece'),
(51, 'UNCLE', 'Uncle'),
(52, 'MUNCLE', 'Maternal uncle'),
(53, 'PUNCLE', 'Paternal uncle'),
(54, 'PRN', 'Parent'),
(55, 'FTH', 'Father'),
(56, 'MTH', 'Mother'),
(57, 'NPRN', 'Natural parent'),
(58, 'NFTH', 'Natural father'),
(59, 'NFTHF', 'Natural father of fetus'),
(60, 'NMTH', 'Natural mother'),
(61, 'PRNINLAW', 'Parent in-law'),
(62, 'FTHINLAW', 'Father in-law'),
(63, 'MTHINLAW', 'Mother in-law'),
(64, 'STPPRN', 'Step parent'),
(65, 'STPFTH', 'Stepfather'),
(66, 'STPMTH', 'Stepmother'),
(67, 'SIB', 'Sibling'),
(68, 'BRO', 'Brother'),
(69, 'HSIB', 'Half-sibling'),
(70, 'HBRO', 'Half-brother'),
(71, 'HSIS', 'Half-sister'),
(72, 'NSIB', 'Natural sibling'),
(73, 'NBRO', 'Natural brother'),
(74, 'NSIS', 'Natural sister'),
(75, 'SIBINLAW', 'Sibling in-law'),
(76, 'BROINLAW', 'Brother in-law'),
(77, 'SISINLAW', 'Sister in-law'),
(78, 'SIS ', 'Sister  '),
(79, 'STPSIB', 'Step sibling'),
(80, 'STPBRO', 'Stepbrother'),
(81, 'STPSIS', 'Stepsister'),
(82, 'SIGOTHR', 'Significant other'),
(83, 'DOMPART', 'Domestic partner'),
(84, 'SPS', 'Spouse'),
(85, 'HUSB', 'Husband'),
(86, 'WIFE', 'Wife'),
(87, 'FRND', 'Unrelated friend'),
(88, 'NBOR', 'Neighbor'),
(89, 'ROOM', 'Roommate'),
(90, 'RESPRSN', 'Responsible party'),
(91, 'EXCEST', 'Executor of estate'),
(92, 'GUADLTM', 'Guardian ad lidem'),
(93, 'GUARD', 'Guardian   '),
(94, 'POWATT', 'Power of attorney'),
(95, 'DPOWATT', 'Durable power of attorney'),
(96, 'HPOWATT', 'Healthcare power of attorney'),
(97, 'SPOWATT', 'Special power of attorney'),
(98, 'SELF', 'Self'),
(99, 'TMPGUARD', 'Temporary guardian'),
(100, 'BYSTND', 'Bystander');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
