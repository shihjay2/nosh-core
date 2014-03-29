-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 28, 2014 at 04:58 PM
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
-- Table structure for table `pos`
--

CREATE TABLE IF NOT EXISTS `pos` (
  `pos_id` int(11) NOT NULL,
  `pos_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pos`
--

INSERT INTO `pos` (`pos_id`, `pos_description`) VALUES
(1, 'Pharmacy'),
(3, 'School'),
(4, 'Homeless Shelter'),
(5, 'Indian Health Service - Free-standing Facility'),
(6, 'Indian Health Service - Provider-based Facility'),
(7, 'Tribal 638 - Free-standing Facility'),
(8, 'Tribal 638 - Provider-based Facility'),
(9, 'Prison/Correctional Facility'),
(11, 'Office'),
(12, 'Home'),
(13, 'Assisted Living Facility'),
(14, 'Group Home'),
(15, 'Mobile Unit'),
(16, 'Temporary Lodging'),
(17, 'Walk-in Retail Health Clinic'),
(20, 'Urgent Care Facility'),
(21, 'Inpatient Hospital'),
(22, 'Outpatient Hospital'),
(23, 'Emergency Room - Hospital'),
(24, 'Ambulatory Surgical Center'),
(25, 'Birthing Center'),
(26, 'Military Treatment Facility'),
(31, 'Skilled Nursing Facility'),
(32, 'Nursing Facility'),
(33, 'Custodial Care Facility'),
(34, 'Hospice'),
(41, 'Ambulance - Land'),
(42, 'Ambulance - Air or Water'),
(49, 'Independent Clinic'),
(50, 'Federally Qualified Health Center'),
(51, 'Inpatient Psychiatric Facility'),
(52, 'Psychiatric Facility - Partial Hospitalization'),
(53, 'Community Mental Health Center'),
(54, 'Intermediate Care Facility/Mentally Retarded'),
(55, 'Residential Substance Abuse Treatment Facility'),
(56, 'Psychiatric'),
(57, 'Non-residential Substance Abuse Treatment Facility'),
(60, 'Mass Immunization Center'),
(61, 'Comprehensive Inpatient Rehabilitation Facility'),
(62, 'Comprehensive Outpatient Rehabilitation Facility'),
(65, 'End-Stage Renal Disease Treatment Facility'),
(71, 'Public Health Clinic'),
(72, 'Rural Health Clinic'),
(81, 'Independent Laboratory'),
(99, 'Other Place of Service');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
