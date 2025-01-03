-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 17, 2024 at 07:18 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visiondesign`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(11, 'Mram', 'Ahmed', 'mramahmed@student.ksu.sa', '$2y$10$p3p9HajK3IsW7SOM.2UYxObC7PuDW84bxwIcCSXKHfhxfwZ2/GUX2'),
(12, 'Sara', 'Alqabbani', 'SaraAlqabbani@student.ksu.sa', '$2y$10$3Fk42rrOX8zaT5yDINlwpebQXcQEY3twBDBEzeq7Q2hibPEY86yJu'),
(13, 'Lama', 'Fahad', 'lamafahad@student.ksu.sa', '$2y$10$NKY40WgqgFwr5yxz6gnwB.BXTqFTAOHpagYJUhxtaKcF/tBomQxb.');

-- --------------------------------------------------------

--
-- Table structure for table `designcategory`
--

CREATE TABLE `designcategory` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `designcategory`
--

INSERT INTO `designcategory` (`id`, `category`) VALUES
(21, 'Modern'),
(22, 'Country'),
(23, 'Minimalist'),
(24, 'Coastal'),
(25, 'Bohemian');

-- --------------------------------------------------------

--
-- Table structure for table `designconsultation`
--

CREATE TABLE `designconsultation` (
  `id` int(11) NOT NULL,
  `requestID` int(11) DEFAULT NULL,
  `consultation` text NOT NULL,
  `consultationImgFileName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `designconsultation`
--

INSERT INTO `designconsultation` (`id`, `requestID`, `consultation`, `consultationImgFileName`) VALUES
(31, 41, 'Final consultation provided', '81a3fec423a73851c2d6240c339090e3a73e69ca18bd875e3463ef09ff8dffc1_1713335412_Living room.png'),
(32, 42, 'Final consultation provided', 'f75ade0c62b2e3f812f9f0fb3cc1e4dd19f57558dd55a15b5d1e680cdfa819b7_1713335522_Bedroom.jpg'),
(33, 43, 'Final consultation provided', '6e56f2c8b937d7d47b92821cf60817d85a64fae0cbdf01097c3eabb50012cff8_1713335702_Minimalist Kitchen.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `designconsultationrequest`
--

CREATE TABLE `designconsultationrequest` (
  `id` int(11) NOT NULL,
  `clientID` int(11) DEFAULT NULL,
  `designerID` int(11) DEFAULT NULL,
  `roomTypeID` int(11) DEFAULT NULL,
  `designCategoryID` int(11) DEFAULT NULL,
  `roomWidth` float DEFAULT NULL,
  `roomLength` float DEFAULT NULL,
  `colorPreferences` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `statusID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `designconsultationrequest`
--

INSERT INTO `designconsultationrequest` (`id`, `clientID`, `designerID`, `roomTypeID`, `designCategoryID`, `roomWidth`, `roomLength`, `colorPreferences`, `date`, `statusID`) VALUES
(41, 11, 51, 91, 21, 5, 4, 'Beige and Green', '2024-01-09', 83),
(42, 11, 51, 92, 24, 4, 3, 'Blue and White', '2024-01-15', 83),
(43, 13, 53, 93, 22, 5, 7, 'Blue and White', '2024-01-20', 83);

-- --------------------------------------------------------

--
-- Table structure for table `designer`
--

CREATE TABLE `designer` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `brandName` varchar(100) NOT NULL,
  `logoImgFileName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `designer`
--

INSERT INTO `designer` (`id`, `firstName`, `lastName`, `emailAddress`, `password`, `brandName`, `logoImgFileName`) VALUES
(51, 'Raghad', 'AlQabbani ', 'Raghadalqabbani@ksu.sa', '$2y$10$xEpf/0wxGQ9d2uom23CD3uKbzZuwquFIrrKSlDX5TaJptXSPx3sqG', 'RQ Home and Decor', 'fbe4d96f1421bd2aa9849d874a3181fbe5882c8ca567da7bf75095abdb61d8c8_1713296125_Logo1.png'),
(52, 'Abeer', 'Khalid', 'Abeerkhalid@gmail.com', '$2y$10$Dw7GN1hQL8uiZdEcKT9LcuR01XcJ5pM7V8/JLIZPWkbUrT04AqDvG', 'MJ Home And Decor', '7873f43f7a060d3033590e379a6a5a0035084e63f59b500a58c98d162ec446bb_1713296313_Logo2.png'),
(53, 'Fahadh', 'Rashid', 'Fahadhrashid@gmail.com', '$2y$10$4BD1oXcPXfJjLkYx8ax9eOMjZnQ9kTnmMM1sPRhIeuEM.YgkbIpKq', 'AM Home And Decor', '35460866b199143aa416862891983025db552182237e1d636d73e53e7e603f18_1713296575_Logo3.png');

-- --------------------------------------------------------

--
-- Table structure for table `designerspeciality`
--

CREATE TABLE `designerspeciality` (
  `designerID` int(11) DEFAULT NULL,
  `designCategoryID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `designerspeciality`
--

INSERT INTO `designerspeciality` (`designerID`, `designCategoryID`) VALUES
(51, 22),
(52, 21),
(53, 23);

-- --------------------------------------------------------

--
-- Table structure for table `designportoflioproject`
--

CREATE TABLE `designportoflioproject` (
  `id` int(11) NOT NULL,
  `designerID` int(11) DEFAULT NULL,
  `projectName` varchar(100) NOT NULL,
  `projectImgFileName` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `designCategoryID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `designportoflioproject`
--

INSERT INTO `designportoflioproject` (`id`, `designerID`, `projectName`, `projectImgFileName`, `description`, `designCategoryID`) VALUES
(71, 51, 'Sun House', '3d65b2879eccc1e6f1134d18f4a72987e56f65209e2e8e792b724bd1fffce9c2_1713297252_Sun House.png', 'Rustic white sun house that has many green plants and windows with a Country style. ', 22),
(72, 52, 'Home Office', '73c5007ef2a8fc03ab7be1bbb3f60608c7c86ce0df746184968b56813d20a9e3_1713297397_Home Office.jpg', 'White and black minimalist home office with clean lines. ', 21),
(73, 53, 'Minimalist Oasis', '7f8dea81a17f8f8203a4a366cd1c593607496c6d40c91e7930da1f768478fb5e_1713297447_Minimalist Oasis.jpeg', 'Craft a serene living space with clean lines, neutral tones, and purposeful simplicity. Embrace functionality and natural light to create an oasis of calm, offering a retreat from the complexities of daily life.', 23);

-- --------------------------------------------------------

--
-- Table structure for table `requeststatus`
--

CREATE TABLE `requeststatus` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `requeststatus`
--

INSERT INTO `requeststatus` (`id`, `status`) VALUES
(81, 'pending consultation'),
(82, 'consultation declined'),
(83, 'consultation provided');

-- --------------------------------------------------------

--
-- Table structure for table `roomtype`
--

CREATE TABLE `roomtype` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roomtype`
--

INSERT INTO `roomtype` (`id`, `type`) VALUES
(91, 'Living Room'),
(92, 'Bedroom'),
(93, 'Kitchen');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designcategory`
--
ALTER TABLE `designcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designconsultation`
--
ALTER TABLE `designconsultation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requestID` (`requestID`);

--
-- Indexes for table `designconsultationrequest`
--
ALTER TABLE `designconsultationrequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientID` (`clientID`),
  ADD KEY `designerID` (`designerID`),
  ADD KEY `roomTypeID` (`roomTypeID`),
  ADD KEY `designCategoryID` (`designCategoryID`),
  ADD KEY `statusID` (`statusID`);

--
-- Indexes for table `designer`
--
ALTER TABLE `designer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designerspeciality`
--
ALTER TABLE `designerspeciality`
  ADD KEY `designerID` (`designerID`),
  ADD KEY `designCategoryID` (`designCategoryID`);

--
-- Indexes for table `designportoflioproject`
--
ALTER TABLE `designportoflioproject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designerID` (`designerID`),
  ADD KEY `designCategoryID` (`designCategoryID`);

--
-- Indexes for table `requeststatus`
--
ALTER TABLE `requeststatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtype`
--
ALTER TABLE `roomtype`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `designcategory`
--
ALTER TABLE `designcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `designconsultation`
--
ALTER TABLE `designconsultation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `designconsultationrequest`
--
ALTER TABLE `designconsultationrequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `designer`
--
ALTER TABLE `designer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `designportoflioproject`
--
ALTER TABLE `designportoflioproject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `requeststatus`
--
ALTER TABLE `requeststatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `roomtype`
--
ALTER TABLE `roomtype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `designconsultation`
--
ALTER TABLE `designconsultation`
  ADD CONSTRAINT `designconsultation_ibfk_1` FOREIGN KEY (`requestID`) REFERENCES `designconsultationrequest` (`id`);

--
-- Constraints for table `designconsultationrequest`
--
ALTER TABLE `designconsultationrequest`
  ADD CONSTRAINT `designconsultationrequest_ibfk_1` FOREIGN KEY (`clientID`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `designconsultationrequest_ibfk_2` FOREIGN KEY (`designerID`) REFERENCES `designer` (`id`),
  ADD CONSTRAINT `designconsultationrequest_ibfk_3` FOREIGN KEY (`roomTypeID`) REFERENCES `roomtype` (`id`),
  ADD CONSTRAINT `designconsultationrequest_ibfk_4` FOREIGN KEY (`designCategoryID`) REFERENCES `designcategory` (`id`),
  ADD CONSTRAINT `designconsultationrequest_ibfk_5` FOREIGN KEY (`statusID`) REFERENCES `requeststatus` (`id`);

--
-- Constraints for table `designerspeciality`
--
ALTER TABLE `designerspeciality`
  ADD CONSTRAINT `designerspeciality_ibfk_1` FOREIGN KEY (`designerID`) REFERENCES `designer` (`id`),
  ADD CONSTRAINT `designerspeciality_ibfk_2` FOREIGN KEY (`designCategoryID`) REFERENCES `designcategory` (`id`);

--
-- Constraints for table `designportoflioproject`
--
ALTER TABLE `designportoflioproject`
  ADD CONSTRAINT `designportoflioproject_ibfk_1` FOREIGN KEY (`designerID`) REFERENCES `designer` (`id`),
  ADD CONSTRAINT `designportoflioproject_ibfk_2` FOREIGN KEY (`designCategoryID`) REFERENCES `designcategory` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
