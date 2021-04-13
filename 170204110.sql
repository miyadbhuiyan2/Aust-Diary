-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2020 at 12:04 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `170204110`
--
CREATE DATABASE IF NOT EXISTS `170204110` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `170204110`;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `Notify_id` int(11) NOT NULL,
  `Post_id` int(11) NOT NULL,
  `React` varchar(5) NOT NULL,
  `Time` datetime NOT NULL DEFAULT current_timestamp(),
  `User_id` int(11) NOT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'UNREAD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`Notify_id`, `Post_id`, `React`, `Time`, `User_id`, `Status`) VALUES
(4, 30, 'Like', '2020-09-19 02:22:43', 2, 'READ'),
(9, 5, 'Like', '2020-09-20 02:34:33', 2, 'READ'),
(10, 11, 'Star', '2020-09-20 02:35:29', 3, 'UNREAD'),
(11, 13, 'Like', '2020-09-20 02:36:13', 4, 'UNREAD');

-- --------------------------------------------------------

--
-- Table structure for table `outerpages`
--

CREATE TABLE `outerpages` (
  `OP_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `outerpages`
--

INSERT INTO `outerpages` (`OP_id`, `name`, `value`) VALUES
(1, 'logo', 'aust_diary_logo.jpg'),
(2, 'logoWhite', 'aust_diary_logo_white.png'),
(3, 'pageName', 'Aust Diary'),
(4, 'paragraph', 'A community of students and teachers of Ahsanullah University of Science And Technology'),
(5, 'signInLogo', 'account.png'),
(6, 'slide1', 'slide1.JPG'),
(7, 'slide2', 'slide2.jpg'),
(8, 'slide3', 'slide3.jpg'),
(9, 'slide4', 'slide4.jpg'),
(10, 'slide5', 'slide5.jpg'),
(11, 'description', 'Aust Diary is the platform for the stundents and teachers of Ahsanullah University of Science and Technology.There are eight departments in this university for the students.During their academic life,They have to build up many projects for submission.They can upload and show up their projects here.Through this platform, Students are getting the opportunity to share their creativity,ideas and contributions with the other students.That\'s how, many more students will get inspiration to show their talents.Members can visit their profiles and tasks. They can also give feedback on these tasks.'),
(12, 'studentIcon', 'student.jpg'),
(13, 'teacherIcon', 'teacher.jpg'),
(14, 'companyRepresentativeIcon', 'companyrepresentative.jpg'),
(15, 'developerImage1', 'rahat.jpg'),
(16, 'developerName1', 'Rahat Kader Khan'),
(17, 'developerDescription1', '\"I am a student of Ahsanullah University of Science and Technology at Computer Science and Engineering department. My ID is 17.02.04.074\"'),
(18, 'developerImage2', 'miyad.jpg'),
(19, 'developerName2', 'Miyad Bhuiyan'),
(20, 'developerDescription2', '\"I am a student of Ahsanullah University of Science and Technology at Computer Science and Engineering department. My ID is 17.02.04.110\"'),
(21, 'address', '141 & 142, Love Road, Dhaka-1208'),
(22, 'phone', '(8802) 8870422, Ext. 107, 114'),
(23, 'fax', '(8802) 8870417-18'),
(24, 'message', 'info@aust.edu');

-- --------------------------------------------------------

--
-- Table structure for table `postimages`
--

CREATE TABLE `postimages` (
  `PI_id` int(11) NOT NULL,
  `NumofImage` int(11) NOT NULL,
  `ImageSource` varchar(250) NOT NULL,
  `PS_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `postimages`
--

INSERT INTO `postimages` (`PI_id`, `NumofImage`, `ImageSource`, `PS_id`) VALUES
(1, 1, 'postImages/1600373104_59422388_2012051635770776_9181329929139126272_o.jpg', 2),
(5, 3, 'postImages/1600445636_BingWallpaper-2020-06-09.jpg', 5),
(6, 3, 'postImages/1600445636_EYIWu0BUwAE44mh.jpg', 5),
(7, 3, 'postImages/1600445636_joker-harley-quinn-batman-two-face-drawing-scary.jpg', 5),
(8, 1, 'postImages/1600499264_Line_Follower_Robot_Competition8.jpg', 6),
(9, 1, 'postImages/1600499413_android-studio-featured-810x298_c.png', 7),
(10, 2, 'postImages/1600499579_docs.png', 8),
(11, 2, 'postImages/1600499579_photo-1563207153-f403bf289096.jpg', 8),
(14, 1, 'postImages/1600500005_netbeans-82.png', 11),
(16, 1, 'postImages/1600528759_docs.png', 13),
(37, 2, 'postImages/1600546172_0b0682324e4328a02c0e9ee5189e774f.jpg', 30),
(38, 2, 'postImages/1600546172_ap_resize.png', 30);

-- --------------------------------------------------------

--
-- Table structure for table `postshow`
--

CREATE TABLE `postshow` (
  `PS_id` int(11) NOT NULL,
  `ProjectName` varchar(50) NOT NULL,
  `ProjectDescription` varchar(250) NOT NULL,
  `NumofImage` int(11) NOT NULL,
  `Llike` int(11) NOT NULL,
  `Star` int(11) NOT NULL,
  `timedate` datetime NOT NULL,
  `tags` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `postshow`
--

INSERT INTO `postshow` (`PS_id`, `ProjectName`, `ProjectDescription`, `NumofImage`, `Llike`, `Star`, `timedate`, `tags`, `user_id`) VALUES
(2, 'First on Merge', 'This is the first merge post ...........', 1, 0, 0, '2020-09-17 22:05:04', 'SD,HD', 3),
(5, 'Multi Image 2', 'This is the second trial for multiple image .........', 3, 0, 0, '2020-09-18 18:13:56', 'SD,HD,Robo,Art,Docu', 1),
(6, 'Line Following Robot ', 'This is my first Line following robot . We made a group of three people to build this robot. We are expecting to compete in the upcoming competition. ', 1, 0, 0, '2020-09-19 09:07:44', 'HD,Robo', 2),
(7, 'Apps', 'I made an app build in android studio for my course project. I am sharing this with you guys.', 1, 0, 0, '2020-09-19 09:10:13', '', 3),
(8, 'Documentation', 'This is my first documentation. I work on this for about a month. I hope you guys will like it', 2, 0, 0, '2020-09-19 09:12:59', 'HD,Robo,Docu', 3),
(11, 'Netbeans Project', 'My first netbeans project .......... sharing the code view....................', 1, 0, 0, '2020-09-19 09:20:05', 'SD', 1),
(13, 'Doc', 'This is documentation', 1, 0, 0, '2020-09-19 17:19:19', 'Docu', 1),
(30, 'Android Project', 'This is my android project.', 2, 0, 0, '2020-09-19 22:09:32', 'SD', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_id` int(11) NOT NULL,
  `Email_id` varchar(100) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `ContactNumber` varchar(11) DEFAULT NULL,
  `Image` text DEFAULT NULL,
  `Password` text DEFAULT NULL,
  `IdNumber` varchar(15) DEFAULT NULL,
  `CompanyName` varchar(50) DEFAULT NULL,
  `CompanyType` varchar(50) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `Rank` varchar(25) DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `Semester` varchar(6) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL,
  `Description` longtext DEFAULT NULL,
  `Skills` text DEFAULT NULL,
  `Account_Status` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_id`, `Email_id`, `FirstName`, `LastName`, `ContactNumber`, `Image`, `Password`, `IdNumber`, `CompanyName`, `CompanyType`, `Address`, `Gender`, `Rank`, `Department`, `Semester`, `Year`, `Description`, `Skills`, `Account_Status`) VALUES
(1, 'miyadbhuiyan@gmail.com', 'Miyad', 'Bhuiyan', '01303760990', '1.jpg', '54baf7f8288c87badf5f2dfb62baa1c3', '17.02.04.110', NULL, NULL, '5,Avoy das lane,Dhaka																																																																																																																																																																																			', 'Male', 'Student', 'Computer Science and Engineering', 'Fall', 2017, 'Hi,I\'m Miyad Bhuiyan.', 'C,java programing,C++,php', 1),
(2, 'rahat@gmail.com', 'Rahat', 'Kader Khan', '01301212464', '2.jpg', 'e10adc3949ba59abbe56e057f20f883e', '17.02.04.074', NULL, NULL, 'Tangi								\r\n																																																																																																																																																																			', 'Male', 'Student', 'Computer Science and Engineering', 'Fall', 2017, NULL, NULL, 2),
(3, 'rahim@gmail.com', 'Abdur', 'Rahim', '01521234453', '3.jpg', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, 'House#5,Road#12,Shantinagar,Dhaka								\r\n																			', 'Male', 'Teacher', 'Electrical and Electronics Engineering', NULL, NULL, 'Hello! I am Abdur Rahim', ' Material characterization,Solid state devices, Optical communication, Earthquake detection', 1),
(4, 'sharif@yahoo.com', 'Shariful', 'Islam', '01912345452', '4.jpg', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'Enosis Solution', 'Software Company', 'House - 27 Rd No 8, Dhaka 1212								\r\n																																					', 'Male', 'Company Representative', NULL, NULL, NULL, 'Enosis is a premier provider of software development and testing services. Having talented software engineers on board, we craft compelling web, desktop, and mobile applications for our clients.Since our inception, we have partnered with numerous companies and delivered operational gains to startup, emerging, and established  organizations in the United States and Canada.\r\n', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`Notify_id`),
  ADD KEY `Post_id` (`Post_id`),
  ADD KEY `User_id` (`User_id`);

--
-- Indexes for table `outerpages`
--
ALTER TABLE `outerpages`
  ADD PRIMARY KEY (`OP_id`);

--
-- Indexes for table `postimages`
--
ALTER TABLE `postimages`
  ADD PRIMARY KEY (`PI_id`),
  ADD KEY `PS_id` (`PS_id`);

--
-- Indexes for table `postshow`
--
ALTER TABLE `postshow`
  ADD PRIMARY KEY (`PS_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Email_id` (`Email_id`),
  ADD UNIQUE KEY `ContactNumber` (`ContactNumber`),
  ADD UNIQUE KEY `CompanyName` (`CompanyName`),
  ADD UNIQUE KEY `IdNumber` (`IdNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `Notify_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `outerpages`
--
ALTER TABLE `outerpages`
  MODIFY `OP_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `postimages`
--
ALTER TABLE `postimages`
  MODIFY `PI_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `postshow`
--
ALTER TABLE `postshow`
  MODIFY `PS_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`Post_id`) REFERENCES `postshow` (`PS_id`),
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`User_id`) REFERENCES `user` (`User_id`);

--
-- Constraints for table `postimages`
--
ALTER TABLE `postimages`
  ADD CONSTRAINT `postimages_ibfk_1` FOREIGN KEY (`PS_id`) REFERENCES `postshow` (`PS_id`);

--
-- Constraints for table `postshow`
--
ALTER TABLE `postshow`
  ADD CONSTRAINT `postshow_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`User_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
