-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 26, 2014 at 11:15 AM
-- Server version: 5.5.35-0ubuntu0.13.10.2
-- PHP Version: 5.5.3-1ubuntu2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nosh_laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `supplements_list`
--

CREATE TABLE IF NOT EXISTS `supplements_list` (
  `supplements_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplement_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`supplements_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=143 ;

--
-- Dumping data for table `supplements_list`
--

INSERT INTO `supplements_list` (`supplements_id`, `supplement_name`) VALUES
(1, 'Acai'),
(2, 'Alfalfa'),
(3, 'Aloe'),
(4, 'Aloe Vera'),
(5, 'Aristolochic Acids'),
(6, 'Asian Ginseng'),
(7, 'Astragalus'),
(8, 'Bacillus coagulans '),
(9, 'Belladonna'),
(10, 'Beta-carotene'),
(11, 'Bifidobacteria '),
(12, 'Bilberry'),
(13, 'Biotin'),
(14, 'Bitter Orange'),
(15, 'Black Cohosh'),
(16, 'Black psyllium'),
(17, 'Black tea'),
(18, 'Bladderwrack'),
(19, 'Blessed thistle'),
(20, 'Blond psyllium'),
(21, 'Blueberry'),
(22, 'Blue-green algae'),
(23, 'Boron'),
(24, 'Bromelain'),
(25, 'Butterbur'),
(26, 'Calcium'),
(27, 'Calendula'),
(28, 'Cancell/Cantron/Protocel (PDQ)'),
(29, 'Cartilage (Bovine and Shark) (PDQ)'),
(30, 'Cassia cinnamon'),
(31, 'Cat''s Claw'),
(32, 'Chamomile'),
(33, 'Chasteberry'),
(34, 'Chondroitin sulfate'),
(35, 'Chromium'),
(36, 'Cinnamon'),
(37, 'Clove'),
(38, 'Coenzyme Q-10'),
(39, 'Coenzyme Q10 (PDQ)'),
(40, 'Colloidal Silver Products'),
(41, 'Cranberry'),
(42, 'Creatine'),
(43, 'Dandelion'),
(44, 'Devil''s claw'),
(45, 'DHEA'),
(46, 'Dong quai'),
(47, 'Echinacea'),
(48, 'Ephedra'),
(49, 'Essiac/Flor-Essence (PDQ)'),
(50, 'Eucalyptus'),
(51, 'European Elder (Elderberry)'),
(52, 'European Mistletoe'),
(53, 'Evening Primrose Oil'),
(54, 'Fenugreek'),
(55, 'Feverfew'),
(56, 'Fish oil'),
(57, 'Flaxseed'),
(58, 'Flaxseed and Flaxseed Oil'),
(59, 'Flaxseed oil'),
(60, 'Folate'),
(61, 'Folic acid'),
(62, 'Garlic'),
(63, 'Ginger'),
(64, 'Gingko'),
(65, 'Ginkgo'),
(66, 'Ginseng, American'),
(67, 'Ginseng, Panax'),
(68, 'Ginseng, Siberian'),
(69, 'Glucosamine hydrochloride'),
(70, 'Glucosamine sulfate'),
(71, 'Goldenseal'),
(72, 'Grape Seed Extract'),
(73, 'Green Tea'),
(74, 'Hawthorn'),
(75, 'Hoodia'),
(76, 'Horse Chestnut'),
(77, 'Horsetail'),
(78, 'Hydrazine Sulfate (PDQ)'),
(79, 'Iodine'),
(80, 'Iron'),
(81, 'Kava'),
(82, 'Lactobacillus'),
(83, 'Laetrile/Amygdalin (PDQ)'),
(84, 'L-arginine'),
(85, 'Lavender'),
(86, 'Licorice'),
(87, 'Licorice Root'),
(88, 'Lycium '),
(89, 'Lycopene'),
(90, 'Magnesium'),
(91, 'Manganese'),
(92, 'Melatonin'),
(93, 'Milk Thistle'),
(94, 'Milk Thistle (PDQ)'),
(95, 'Mistletoe Extracts (PDQ)'),
(96, 'Niacin and niacinamide (Vitamin B3) '),
(97, 'Noni'),
(98, 'Oral Probiotics'),
(99, 'Pantothenic acid (Vitamin B5)'),
(100, 'Passionflower'),
(101, 'PC-SPES (PDQ)'),
(102, 'Pennyroyal'),
(103, 'Peppermint'),
(104, 'Peppermint Oil'),
(105, 'Phosphate salts'),
(106, 'Pomegranate'),
(107, 'Propolis'),
(108, 'Pycnogenol'),
(109, 'Pyridoxine (Vitamin B6)'),
(110, 'Red Clover'),
(111, 'Red yeast'),
(112, 'Red Yeast Rice: An Introduction'),
(113, 'Riboflavin (Vitamin B2)'),
(114, 'Roman chamomile'),
(115, 'Saccharomyces boulardii '),
(116, 'S-Adenosyl-L-Methionine (SAMe): An Introduction'),
(117, 'Sage'),
(118, 'SAMe'),
(119, 'Saw Palmetto'),
(120, 'Selected Vegetables/Sun''s Soup (PDQ)'),
(121, 'Selenium'),
(122, 'Senna'),
(123, 'Shark cartilage'),
(124, 'Soy'),
(125, 'St. John''s Wort'),
(126, 'Tea Tree Oil'),
(127, 'Thiamine (Vitamin B1)'),
(128, 'Thunder God Vine'),
(129, 'Turmeric'),
(130, 'Valerian'),
(131, 'Vitamin A'),
(132, 'Vitamin B12'),
(133, 'Vitamin B6'),
(134, 'Vitamin C'),
(135, 'Vitamin C (Ascorbic acid)'),
(136, 'Vitamin D'),
(137, 'Vitamin E'),
(138, 'Vitamin K'),
(139, 'Wild yam'),
(140, 'Yohimbe'),
(141, 'Zinc'),
(142, '5-HTP');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
